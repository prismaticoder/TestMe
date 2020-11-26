<?php

use Illuminate\Database\Seeder;
use App\Option;

class OptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Option::class,3000)->create();
    }
}
