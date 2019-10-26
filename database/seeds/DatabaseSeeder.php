<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ClassesTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        $this->call(QuestionsTableSeeder::class);
        $this->call(OptionTableSeeder::class);
        $this->call(SubjectsTableSeeder::class);

    }
}
