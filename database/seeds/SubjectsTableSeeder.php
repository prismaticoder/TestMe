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
        $subjects = array(['slug'=>'english','name'=>'English Language'],['slug'=>'mathematics','name'=>'Mathematics'],['slug'=>'rnv','name'=>'Religion and National Values'],['slug'=>'french','name'=>'French'],['slug'=>'ict','name'=>'Information and Communication Technology'],['slug'=>'physics','name'=>'Physics'],['slug'=>'chemistry','name'=>'Chemistry'],['slug'=>'biology','name'=>'Biology'],['slug'=>'basic_science','name'=>'Basic Science'],['slug'=>'basic_tech','name'=>'Basic Technology'],['slug'=>'cca','name'=>'Cultural and Creative Arts'],['slug'=>'music','name'=>'Music'],['slug'=>'phonics','name'=>'Phonics'],['slug'=>'yoruba','name'=>'Yoruba']);

        foreach ($subjects as $subject) {
            DB::table('subjects')->insert([
                'slug' => $subject['slug'],
                'name' => $subject['name'],
            ]);
        }
    }
}
