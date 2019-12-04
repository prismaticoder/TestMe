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
        $arr = [1,2,3,4,5,6];
        foreach ($arr as $key => $value) {
            DB::table('admins')->insert([
                'username' => 'ora/tid/00'.$value,
                'password' => bcrypt('oasis'.$value)
            ]);
        }
    }
}
