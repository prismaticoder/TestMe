<?php

use Illuminate\Database\Seeder;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Seeder to input every subject into the db
        $subjects = array(['alias'=>'english','subject_name'=>'English Language'],['alias'=>'mathematics','subject_name'=>'Mathematics'],['alias'=>'rnv','subject_name'=>'Religion and National Values'],['alias'=>'french','subject_name'=>'French'],['alias'=>'ict','subject_name'=>'Information and Communication Technology'],['alias'=>'physics','subject_name'=>'Physics'],['alias'=>'chemistry','subject_name'=>'Chemistry'],['alias'=>'biology','subject_name'=>'Biology'],['alias'=>'basic_science','subject_name'=>'Basic Science'],['alias'=>'basic_tech','subject_name'=>'Basic Technology'],['alias'=>'cca','subject_name'=>'Cultural and Creative Arts'],['alias'=>'music','subject_name'=>'Music'],['alias'=>'phonics','subject_name'=>'Phonics'],['alias'=>'yoruba','subject_name'=>'Yoruba']);

        foreach ($subjects as $subject) {
            DB::table('subjects')->insert([
                'alias' => $subject['alias'],
                'subject_name' => $subject['subject_name'],
            ]);
        }
    }
}
