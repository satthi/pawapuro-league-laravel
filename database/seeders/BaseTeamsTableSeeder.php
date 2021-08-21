<?php

namespace Database\Seeders;

use App\Models\BaseTeam;
use Illuminate\Database\Seeder;

class BaseTeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         BaseTeam::create([
             'name' => 'リトルバスターズ',
             'ryaku_name' => 'LB',
         ]);
         BaseTeam::create([
             'name' => '幻想郷タートルズ',
             'ryaku_name' => 'TU',
         ]);
         BaseTeam::create([
             'name' => '守矢フロッグス',
             'ryaku_name' => 'MF',
         ]);
         BaseTeam::create([
             'name' => 'タイプムーンフェイツ',
             'ryaku_name' => 'TF',
         ]);
         BaseTeam::create([
             'name' => 'サモンナイツ',
             'ryaku_name' => 'SN',
         ]);
         BaseTeam::create([
             'name' => 'ファイナルファンタジーズ',
             'ryaku_name' => 'FF',
         ]);
    }
}
