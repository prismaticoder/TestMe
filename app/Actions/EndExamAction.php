<?php

namespace App\Actions;

use App\Exam;

class EndExamAction
{
    public function execute(Exam $exam): bool
    {
        return $exam->update([
            'has_started' => 0,
            'started_at' => null
        ]);

        //later additions would include firing an event for an exam being ended or notifying admin
    }
}
