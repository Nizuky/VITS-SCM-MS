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
    public function index()
    {
        try {
            $students = User::select('id', 'name', 'student_id', 'email', 'email_verified_at', 'created_at', 'updated_at')
                ->orderBy('name', 'asc')
                ->get()
                ->map(function($student) {
                    // Add status field (default to active for now)
                    $student->status = 'active';
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
            $student = User::findOrFail($id);
            
            // Validate the request
            $validated = $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'student_id' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($student->id)],
                'email' => ['required', 'email', Rule::unique('users')->ignore($student->id)],
                'status' => ['sometimes', 'string', 'in:active,inactive']
            ]);
            
            $updated = false;
            $updatedFields = [];
            
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
            
            // Note: Status field can be added to users table in the future
            // if (isset($validated['status'])) {
            //     $student->status = $validated['status'];
            //     $updated = true;
            //     $updatedFields[] = 'status';
            // }
            
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
}
