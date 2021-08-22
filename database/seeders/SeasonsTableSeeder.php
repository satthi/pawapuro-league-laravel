<?php

namespace Database\Seeders;

use App\Models\Season;
use Illuminate\Database\Seeder;

class SeasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         (new Season())->add([
             'name' => '開発用シーズン',
             'regular_flag' => true,
             'selected_teams' => [
                '1',
                '2',
                '3',
                '4',
                '5',
                '6',
             ]
         ]);
    }
}
