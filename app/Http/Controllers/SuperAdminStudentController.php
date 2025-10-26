<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SuperAdminStudentController extends Controller
{
    /**
     * Get all students
     */
    public function index(Request $request)
    {
        try {
            // Ensure session marker is present (restore if missing but user is authenticated)
            if (!$request->session()->has('superadmin_session_active')) {
                $request->session()->put('superadmin_session_active', true);
                $request->session()->save();
                Log::info('Restored missing superadmin session marker in students index');
            }
            
            // Use a subquery approach - join through social_contracts table
            $students = User::select('users.*')
                ->selectSub(function ($query) {
                    $query->selectRaw('COALESCE(SUM(social_contract_records.hours_rendered), 0)')
                          ->from('social_contract_records')
                          ->join('social_contracts', 'social_contract_records.social_contract_id', '=', 'social_contracts.id')
                          ->whereColumn('social_contracts.student_id', 'users.id')
                          ->where('social_contract_records.status', 'Approved');
                }, 'approved_hours')
                ->orderBy('users.name', 'asc')
                ->get()
                ->map(function($student) {
                    // Get the actual status from the database
                    $student->status = $student->status ?? 'active';
                    // Ensure approved_hours is numeric
                    $student->approved_hours = (int) $student->approved_hours;
                    return $student;
                });
            
            return response()->json([
                'success' => true,
                'students' => $students
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
        } catch (\Exception $e) {
            Log::error('Failed to fetch students', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch students'
            ], 500);
        }
    }
    
    /**
     * Update student information
     */
    public function update(Request $request, $id)
    {
        try {
            // Ensure session marker is present
            if (!$request->session()->has('superadmin_session_active')) {
                $request->session()->put('superadmin_session_active', true);
                $request->session()->save();
                Log::info('Restored missing superadmin session marker in student update');
            }
            
            $student = User::findOrFail($id);
            
            // Validate the request
            $validated = $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'student_id' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($student->id)],
                'email' => ['required', 'email', Rule::unique('users')->ignore($student->id)],
                'status' => ['required', 'string', 'in:active,inactive']
            ]);
            
            $updated = false;
            $updatedFields = [];
            $previousStatus = $student->status;
            
            // Update name
            if (isset($validated['name']) && $validated['name'] !== $student->name) {
                $student->name = $validated['name'];
                $updated = true;
                $updatedFields[] = 'name';
            }
            
            // Update student_id
            if (isset($validated['student_id']) && $validated['student_id'] !== $student->student_id) {
                $student->student_id = $validated['student_id'];
                $updated = true;
                $updatedFields[] = 'student_id';
            }
            
            // Update email
            if (isset($validated['email']) && $validated['email'] !== $student->email) {
                $student->email = $validated['email'];
                $updated = true;
                $updatedFields[] = 'email';
            }
            
            // Update status
            if (isset($validated['status']) && $validated['status'] !== $student->status) {
                $student->status = $validated['status'];
                $updated = true;
                $updatedFields[] = 'status';
                
                // If changing to inactive, set inactive_at timestamp
                if ($validated['status'] === 'inactive') {
                    $student->inactive_at = now();
                    
                    // Create notification for the student
                    $daysRemaining = 7;
                    $deletionDate = now()->addDays($daysRemaining)->format('F d, Y');
                    
                    \App\Models\StudentNotification::create([
                        'user_id' => $student->id,
                        'title' => 'Account Status Changed to Inactive',
                        'message' => "Your account has been set to inactive by the administrator. Your account will be permanently deleted on {$deletionDate} ({$daysRemaining} days from now) unless it is reactivated. Please contact your administrator if you believe this is an error.",
                        'type' => 'warning',
                        'is_read' => false
                    ]);
                    
                    Log::info('Student account set to inactive', [
                        'student_id' => $student->id,
                        'inactive_at' => $student->inactive_at,
                        'deletion_scheduled' => $deletionDate
                    ]);
                } else {
                    // If reactivating, clear inactive_at
                    $student->inactive_at = null;
                    
                    // Create notification for reactivation
                    \App\Models\StudentNotification::create([
                        'user_id' => $student->id,
                        'title' => 'Account Reactivated',
                        'message' => 'Your account has been reactivated by the administrator. You now have full access to the system.',
                        'type' => 'success',
                        'is_read' => false
                    ]);
                    
                    Log::info('Student account reactivated', [
                        'student_id' => $student->id
                    ]);
                }
            }
            
            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'No changes detected'
                ], 422);
            }
            
            $student->save();
            
            Log::info('Super Admin updated student profile', [
                'super_admin_id' => auth()->guard('superadmin')->id(),
                'student_id' => $student->id,
                'updated_fields' => $updatedFields
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Student information updated successfully',
                'student' => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'student_id' => $student->student_id
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to update student', [
                'student_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update student information'
            ], 500);
        }
    }
    
    /**
     * Delete a student account permanently
     */
    public function destroy(Request $request, $id)
    {
        try {
            // Ensure session marker is present
            if (!$request->session()->has('superadmin_session_active')) {
                $request->session()->put('superadmin_session_active', true);
                $request->session()->save();
                Log::info('Restored missing superadmin session marker in student destroy');
            }
            
            $student = User::findOrFail($id);
            
            // Store student info for logging before deletion
            $studentInfo = [
                'id' => $student->id,
                'name' => $student->name,
                'student_id' => $student->student_id,
                'email' => $student->email
            ];
            
            // Delete the student account
            $student->delete();
            
            Log::info('Super Admin deleted student account', [
                'super_admin_id' => auth()->guard('superadmin')->id(),
                'deleted_student' => $studentInfo,
                'timestamp' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Student account has been permanently deleted'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to delete student account', [
                'student_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete student account'
            ], 500);
        }
    }
}
