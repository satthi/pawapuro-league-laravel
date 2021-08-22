<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;

class GamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Game::create([
             'season_id' => 1,
             'date' => '2021-04-01',
             'home_team_id' => 1,
             'visitor_team_id' => 2,
             'dh_flag' => false
         ]);
         Game::create([
             'season_id' => 1,
             'date' => '2021-04-01',
             'home_team_id' => 3,
             'visitor_team_id' => 4,
             'dh_flag' => false
         ]);
         Game::create([
             'season_id' => 1,
             'date' => '2021-04-01',
             'home_team_id' => 5,
             'visitor_team_id' => 6,
             'dh_flag' => true
         ]);
         Game::create([
             'season_id' => 1,
             'date' => '2021-04-02',
             'home_team_id' => 1,
             'visitor_team_id' => 2,
             'dh_flag' => false
         ]);
         Game::create([
             'season_id' => 1,
             'date' => '2021-04-02',
             'home_team_id' => 3,
             'visitor_team_id' => 4,
             'dh_flag' => false
         ]);
         Game::create([
             'season_id' => 1,
             'date' => '2021-04-02',
             'home_team_id' => 5,
             'visitor_team_id' => 6,
             'dh_flag' => true
         ]);
    }
}
