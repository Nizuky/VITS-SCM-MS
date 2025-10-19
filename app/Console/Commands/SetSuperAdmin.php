<?php

namespace App\Console\Commands;

use App\Models\SuperAdmin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SetSuperAdmin extends Command
{
    protected $signature = 'superadmin:set
                            {--name= : Super admin display name}
                            {--email= : Super admin email (unique key)}
                            {--password= : Plain password to set}
                            {--verify : Mark email as verified now}
                            ';

    protected $description = 'Create or update the Super Admin account by email';

    public function handle(): int
    {
        $name = $this->option('name') ?? '';
        $email = $this->option('email') ?? '';
        $password = $this->option('password') ?? '';
        $verify = (bool) $this->option('verify');

        // Interactive prompts if options missing
        if ($email === '') {
            $email = $this->ask('Super admin email');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address.');
            return self::FAILURE;
        }
        if ($name === '') {
            $name = $this->ask('Super admin name (display)', 'admin');
        }
        if ($password === '') {
            $password = $this->secret('New password (input hidden)');
        }
        if ($password === '' || strlen($password) < 8) {
            $this->error('Password must be at least 8 characters.');
            return self::FAILURE;
        }

        $payload = [
            'name' => $name,
            'password' => Hash::make($password),
        ];
        $admin = SuperAdmin::updateOrCreate(['email' => $email], $payload);
        if ($verify && !$admin->email_verified_at) {
            $admin->email_verified_at = now();
            $admin->save();
        }

        $this->info('Super Admin saved:');
        $this->line('  ID:     ' . $admin->id);
        $this->line('  Name:   ' . $admin->name);
        $this->line('  Email:  ' . $admin->email);
        $this->line('  Verified: ' . ($admin->email_verified_at ? 'yes' : 'no'));

        return self::SUCCESS;
    }
}
