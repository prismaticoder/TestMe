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
        $subjects = array(['alias'=>'english','name'=>'English Language'],['alias'=>'mathematics','name'=>'Mathematics'],['alias'=>'rnv','name'=>'Religion and National Values'],['alias'=>'french','name'=>'French'],['alias'=>'ict','name'=>'Information and Communication Technology'],['alias'=>'physics','name'=>'Physics'],['alias'=>'chemistry','name'=>'Chemistry'],['alias'=>'biology','name'=>'Biology'],['alias'=>'basic_science','name'=>'Basic Science'],['alias'=>'basic_tech','name'=>'Basic Technology'],['alias'=>'cca','name'=>'Cultural and Creative Arts'],['alias'=>'music','name'=>'Music'],['alias'=>'phonics','name'=>'Phonics'],['alias'=>'yoruba','name'=>'Yoruba']);

        foreach ($subjects as $subject) {
            DB::table('subjects')->insert([
                'alias' => $subject['alias'],
                'name' => $subject['name'],
            ]);
        }
    }
}
