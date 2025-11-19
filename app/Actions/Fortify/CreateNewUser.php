<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateNewUser
{
    /**
     * Validate and create a new user.
     */
    public function create(array $input): User
    {
        $validated = Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'student_id' => [
                'required',
                'regex:/^\d{2}-\d{4}$/',
                'unique:users,student_id'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
                'regex:/^[^@\s]+@plv\.edu\.ph$/i'
            ],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->numbers()
            ],
        ], [
            'name.min' => 'Name must be at least 3 characters.',
            'student_id.regex' => 'Student ID must be in format: 00-0000 (example: 23-3402).',
            'student_id.unique' => 'This student ID is already registered.',
            'email.regex' => 'Email must be a valid PLV email address (@plv.edu.ph).',
            'email.unique' => 'This email is already registered.',
            'password.min' => 'Password must be at least 8 characters.',
        ])->validate();

        // Hash password before storing
        $validated['password'] = Hash::make($validated['password']);
        
        // Log successful registration
        \Log::info('New student registered', [
            'student_id' => $validated['student_id'],
            'email' => $validated['email'],
            'name' => $validated['name']
        ]);

        return User::create($validated);
    }
}
