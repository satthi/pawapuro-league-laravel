<?php

namespace Database\Seeders;

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
        $this->call(BaseTeamsTableSeeder::class);
        $this->call(BasePlayerTableSeeder::class);
        $this->call(SeasonsTableSeeder::class);
        $this->call(GamesTableSeeder::class);
    }
}
