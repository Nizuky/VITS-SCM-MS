<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateNewUser
{
    /**
     * Validate and create a new user.
     */
    public function create(array $input): User
    {
        $validated = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'regex:/^\d{2}-\d{4}$/', 'unique:users,student_id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email', 'regex:/^[^@\s]+@plv\.edu\.ph$/i'],
            'password' => ['required', 'string'],
        ])->validate();

        $validated['password'] = Hash::make($validated['password']);

        return User::create($validated);
    }
}
