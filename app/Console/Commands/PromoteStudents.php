<?php

namespace App\Console\Commands;

use App\Classes;
use App\Student;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PromoteStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promote:students';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command runs the action of promoting students to the next class.';

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
        $graduandClass = Classes::withoutGlobalScopes()->where('name', 'Graduated')->first();

        try {
            Student::where('class_id', '<>', $graduandClass->id)
                ->update(['class_id' => DB::raw('class_id + 1')]);

            $this->info('Students promoted successfully');
        } catch (\Throwable $th) {
            $this->error('An error occurred while promoting students: '.$th->getMessage());
        }
    }
}
