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
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(SubjectsTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        $this->call(ExamTableSeeder::class);
        $this->call(QuestionsTableSeeder::class);
        $this->call(OptionTableSeeder::class);

    }
}
