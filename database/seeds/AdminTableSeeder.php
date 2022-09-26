<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('teachers')->insert([
            'title' => 'Mr',
            'firstname' => 'Teacher',
            'lastname' => 'Admin',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role_id' => 1
        ]);
    }
}
