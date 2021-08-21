<?php

namespace Database\Seeders;

use App\Models\BasePlayer;
use App\Models\BaseTeam;
use Illuminate\Database\Seeder;
use PhpSpreadsheetWrapper\PhpSpreadsheetWrapper;

class BasePlayerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $handReverseOptions = [
            '右' => 1,
            '左' => 2,
            '両' => 3,
        ];

        // Excelからデータ移行
        $phpSpreadsheetWrapper = new PhpSpreadsheetWrapper(base_path('database/seeders/initial_player.xlsx'));

        $teamList = [];
        $row = 1;
        while(true) {
            $row++;
            $id = $phpSpreadsheetWrapper->getVal(1, $row, 0);
            if (is_null($id)) {
                break;
            }
            $baseTeamName = $phpSpreadsheetWrapper->getVal(2, $row, 0);
            if (!array_key_exists($baseTeamName, $teamList)) {
                $teamList[$baseTeamName] = BaseTeam::where('ryaku_name', $baseTeamName)->first()->id;
            }
            $number = $phpSpreadsheetWrapper->getVal(3, $row, 0);
            $name = $phpSpreadsheetWrapper->getVal(4, $row, 0);
            $nameShort = $phpSpreadsheetWrapper->getVal(5, $row, 0);

            $handP = $handReverseOptions[$phpSpreadsheetWrapper->getVal(9, $row, 0)];
            $handB = $handReverseOptions[$phpSpreadsheetWrapper->getVal(10, $row, 0)];

            $positionP = $phpSpreadsheetWrapper->getVal(11, $row, 0);
            $positionC = $phpSpreadsheetWrapper->getVal(12, $row, 0);
            $positionI = $phpSpreadsheetWrapper->getVal(13, $row, 0);
            $positionO = $phpSpreadsheetWrapper->getVal(14, $row, 0);
            $positionMain = null;
            if ($positionP === '◎') {
                $positionMain = 1;
            } elseif ($positionC === '◎') {
                $positionMain = 2;
            } elseif ($positionI === '◎') {
                $positionMain = 3;
            } elseif ($positionO === '◎') {
                $positionMain = 4;
            }

            $positionSub = [];
            if ($positionP === '○') {
                $positionSub[] = 1;
            } elseif ($positionC === '○') {
                $positionSub[] = 2;
            } elseif ($positionI === '○') {
                $positionSub[] = 3;
            } elseif ($positionO === '○') {
                $positionSub[] = 4;
            }

             BasePlayer::create([
                'base_team_id' => $teamList[$baseTeamName],
                'number' => $number,
                'name' => $name,
                'name_short' => $nameShort,
                'hand_p' => $handP,
                'hand_b' => $handB,
                'position_main' => $positionMain,
                'position_sub1' => $positionSub[0] ?? null,
                'position_sub2' => $positionSub[1] ?? null,
                'position_sub3' => $positionSub[2] ?? null,
             ]);
        }
    }
}
