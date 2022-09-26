<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 4; $i++) {
            DB::table('classes')->insert([
                'id' => $i,
                'name' => "JSS {$i}"
            ]);
        }

    }
}
