<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminUser;

class CreateAdminUsers extends Command
{
    protected $signature = 'admin:create-defaults {--password1=} {--password2=}';
    protected $description = 'Create two admin users admin1 and admin2 with provided passwords';

    public function handle()
    {
        $p1 = $this->option('password1') ?? 'sanandres12345';
        $p2 = $this->option('password2') ?? 'balite12345';

        AdminUser::updateOrCreate(['name' => 'admin1'], [
            'email' => 'janarafael.sanandres@gmail.com',
            'password' => Hash::make($p1),
        ]);

        AdminUser::updateOrCreate(['name' => 'admin2'], [
            'email' => 'janarafael.sanandres@gmail.com',
            'password' => Hash::make($p2),
        ]);

        $this->info('Admin users created/updated: admin1, admin2');
        return 0;
    }
}
