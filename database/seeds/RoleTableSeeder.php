<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $roleArray = ['superadmin', 'admin'];

        foreach ($roleArray as $role) {
            DB::table('roles')->insert([
                'role' => $role
            ]);
        }
    }
}
