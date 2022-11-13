<?php

use App\Player;
use App\TaskForce;
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
        TaskForce::firstOrCreate(['country_name' => '日本']);
        TaskForce::firstOrCreate(['country_name' => 'アメリカ']);
    }
}
