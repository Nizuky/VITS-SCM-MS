<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StudentNotification;
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
            
            // Log the request for debugging
            Log::info('SuperAdminStudentController@index called', [
                'auth_check' => auth()->guard('superadmin')->check(),
                'user_id' => auth()->guard('superadmin')->id(),
                'session_id' => $request->session()->getId(),
            ]);
            
            // Get all users first
            $users = User::orderBy('name', 'asc')->get();
            
            // Get approved hours for all students in one query
            $approvedHours = \DB::table('social_contract_records')
                ->join('social_contracts', 'social_contract_records.social_contract_id', '=', 'social_contracts.id')
                ->where('social_contract_records.status', 'Approved')
                ->groupBy('social_contracts.student_id')
                ->select('social_contracts.student_id', \DB::raw('SUM(social_contract_records.hours_rendered) as total_hours'))
                ->pluck('total_hours', 'student_id');
            
            // Map the hours to users
            $students = $users->map(function($student) use ($approvedHours) {
                $student->status = $student->status ?? 'active';
                $student->approved_hours = (int) ($approvedHours[$student->id] ?? 0);
                return $student;
            });
            
            Log::info('Fetched students successfully', [
                'count' => $students->count()
            ]);
            
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

    /**
     * Send a warning to a student
     */
    public function sendWarning(Request $request, $id)
    {
        try {
            // Ensure session marker is present
            if (!$request->session()->has('superadmin_session_active')) {
                $request->session()->put('superadmin_session_active', true);
                $request->session()->save();
                Log::info('Restored missing superadmin session marker in send warning');
            }

            $validated = $request->validate([
                'warning_level' => ['required', 'integer', 'in:1,2,3'],
                'message' => ['required', 'string', 'max:1000'],
            ]);

            $student = User::findOrFail($id);

            // Determine warning type based on level
            $warningTypes = [
                1 => ['type' => 'warning', 'label' => 'First Warning', 'color' => 'yellow'],
                2 => ['type' => 'warning', 'label' => 'Second Warning', 'color' => 'orange'],
                3 => ['type' => 'danger', 'label' => 'Third Warning (Final)', 'color' => 'red'],
            ];

            $warningInfo = $warningTypes[$validated['warning_level']];

            // Update student's warning level
            $student->warning_level = $validated['warning_level'];
            
            // If third warning, flag account for deletion
            if ($validated['warning_level'] >= 3) {
                $student->flagged_for_deletion = true;
            }
            
            $student->save();

            // Create notification for the student
            StudentNotification::create([
                'user_id' => $student->id,
                'title' => $warningInfo['label'],
                'type' => $warningInfo['type'],
                'message' => $validated['message'],
                'is_read' => false,
            ]);

            Log::info('Super Admin sent warning to student', [
                'super_admin_id' => auth()->guard('superadmin')->id(),
                'student_id' => $student->id,
                'warning_level' => $validated['warning_level'],
                'flagged_for_deletion' => $student->flagged_for_deletion,
            ]);

            $responseMessage = "Warning sent successfully.";
            if ($validated['warning_level'] >= 3) {
                $responseMessage .= " Account has been flagged for possible deletion.";
            }

            return response()->json([
                'success' => true,
                'message' => $responseMessage,
                'warning_level' => $student->warning_level,
                'flagged_for_deletion' => $student->flagged_for_deletion,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to send warning', [
                'student_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send warning: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send a message to a student (without warning)
     */
    public function sendMessage(Request $request, $id)
    {
        try {
            // Ensure session marker is present
            if (!$request->session()->has('superadmin_session_active')) {
                $request->session()->put('superadmin_session_active', true);
                $request->session()->save();
                Log::info('Restored missing superadmin session marker in send message');
            }

            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'message' => ['required', 'string', 'max:2000'],
            ]);

            $student = User::findOrFail($id);

            // Create notification for the student
            StudentNotification::create([
                'user_id' => $student->id,
                'title' => $validated['title'],
                'type' => 'info',
                'message' => $validated['message'],
                'is_read' => false,
            ]);

            Log::info('Super Admin sent message to student', [
                'super_admin_id' => auth()->guard('superadmin')->id(),
                'student_id' => $student->id,
                'title' => $validated['title'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully to ' . $student->name,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to send message', [
                'student_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send message'
            ], 500);
        }
    }
}
