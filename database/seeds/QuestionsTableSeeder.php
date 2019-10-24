<?php

use Illuminate\Database\Seeder;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 4; $i++) {
            for ($j=1; $j<12; $j++) {
                DB::table('questions')->insert([
                    'class_id' => $i,
                    'subject_id' => $j,
                    'question' => "This is a question of order " . $i. "with newt " . $j
                ]);
            }

        }
    }
}
