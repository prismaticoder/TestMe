<?php

namespace App\Console\Commands;

use App\Teacher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetTeacherPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teacher-password:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset a teacher\'s password';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $adminUsername = $this->ask('Enter your username to authenticate this action');

        $admin = Teacher::where('username', $adminUsername)->first();

        if (! $admin) {
            $this->error('Invalid username');

            return 1;
        }

        if ($admin->isTeacher()) {
            $this->error('Only an admin user is authorized to perform this action.');

            return 1;
        }

        $adminPassword = $this->secret('Enter your password');

        if (! Hash::check($adminPassword, $admin->password)) {
            $this->error('Invalid password');

            return 1;
        }

        $teacherUsername = $this->ask('Enter the username of the teacher whose password you want to reset');

        $teacher = Teacher::where('username', $teacherUsername)->first();

        if (! $teacher) {
            $this->error('No teacher with the username '.$teacherUsername.' was found');

            return 1;
        }

        if ($teacher->isAdmin()) {
            $this->error('You can only update passwords for users with teacher role.');

            return 1;
        }

        $newPassword = Str::random(8);

        $teacher->password = $newPassword;
        $teacher->save();

        $this->info("Password reset successfully: Login password: $newPassword");
    }
}
