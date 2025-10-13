<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SendTestVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test-verification {email} {--name=Test User} {--student_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test VerifyStudentEmail notification to an address';

    public function handle(): int
    {
        $email = $this->argument('email');
        $name = $this->option('name') ?? 'Test User';
        $studentId = $this->option('student_id');

        $this->info("Sending test verification to {$email}...");

        // Persist a temporary user so the verification URL can be generated (it needs an id)
        $created = false;
        if (! User::where('email', $email)->exists()) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'student_id' => $studentId,
                'password' => bcrypt(bin2hex(random_bytes(8)))
            ]);
            $created = true;
        } else {
            $user = User::where('email', $email)->first();
        }

        try {
            // Use the notification to send the verification (it builds the signed URL)
            $user->notify(new \App\Notifications\VerifyStudentEmail());
            $this->info('Notification dispatched (check mail logs or configured SMTP).');
        } finally {
            // Clean up temporary user if we created one
            if ($created) {
                $user->delete();
            }
        }

        return 0;
    }
}
