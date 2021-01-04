<?php

namespace App\Actions;

use App\Exam;

class StartExamAction
{
    public function execute(Exam $exam): bool
    {
        return $exam->update([
            'has_started' => 1,
            'started_at' => now()
        ]);
        //later additions would include firing an event for an exam being created or notifying admin
    }
}
