<?php

namespace Database\Seeders;

use App\Models\Stamen;
use Illuminate\Database\Seeder;

class StamensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 開発用
         Stamen::create([
             'game_id' => 1,
             'team_id' => 1,
             'dajun' => 1,
             'position' => 8,
             'player_id' => 23,
         ]);
         Stamen::create([
             'game_id' => 1,
             'team_id' => 1,
             'dajun' => 2,
             'position' => 4,
             'player_id' => 39,
         ]);
         Stamen::create([
             'game_id' => 1,
             'team_id' => 1,
             'dajun' => 3,
             'position' => 5,
             'player_id' => 24,
         ]);
         Stamen::create([
             'game_id' => 1,
             'team_id' => 1,
             'dajun' => 4,
             'position' => 3,
             'player_id' => 26,
         ]);
         Stamen::create([
             'game_id' => 1,
             'team_id' => 1,
             'dajun' => 5,
             'position' => 7,
             'player_id' => 31,
         ]);
         Stamen::create([
             'game_id' => 1,
             'team_id' => 1,
             'dajun' => 6,
             'position' => 9,
             'player_id' => 27,
         ]);
         Stamen::create([
             'game_id' => 1,
             'team_id' => 1,
             'dajun' => 7,
             'position' => 6,
             'player_id' => 25,
         ]);
         Stamen::create([
             'game_id' => 1,
             'team_id' => 1,
             'dajun' => 8,
             'position' => 2,
             'player_id' => 28,
         ]);
         Stamen::create([
             'game_id' => 1,
             'team_id' => 1,
             'dajun' => 9,
             'position' => 1,
             'player_id' => 5,
         ]);

         Stamen::create([
             'game_id' => 1,
             'team_id' => 2,
             'dajun' => 1,
             'position' => 4,
             'player_id' => 87,
         ]);
         Stamen::create([
             'game_id' => 1,
             'team_id' => 2,
             'dajun' => 2,
             'position' => 3,
             'player_id' => 62,
         ]);
         Stamen::create([
             'game_id' => 1,
             'team_id' => 2,
             'dajun' => 3,
             'position' => 5,
             'player_id' => 77,
         ]);
         Stamen::create([
             'game_id' => 1,
             'team_id' => 2,
             'dajun' => 4,
             'position' => 7,
             'player_id' => 100,
         ]);
         Stamen::create([
             'game_id' => 1,
             'team_id' => 2,
             'dajun' => 5,
             'position' => 2,
             'player_id' => 95,
         ]);
         Stamen::create([
             'game_id' => 1,
             'team_id' => 2,
             'dajun' => 6,
             'position' => 9,
             'player_id' => 89,
         ]);
         Stamen::create([
             'game_id' => 1,
             'team_id' => 2,
             'dajun' => 7,
             'position' => 8,
             'player_id' => 85,
         ]);
         Stamen::create([
             'game_id' => 1,
             'team_id' => 2,
             'dajun' => 8,
             'position' => 6,
             'player_id' => 88,
         ]);
         Stamen::create([
             'game_id' => 1,
             'team_id' => 2,
             'dajun' => 9,
             'position' => 1,
             'player_id' => 58,
         ]);
    }
}
