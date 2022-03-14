<?php

namespace App\Http\Controllers;

use App\Enums\PlayerPosition;
use App\Models\Player;
use App\Models\Season;
use App\Models\Team;
use App\Models\Game;
use App\Models\GamePitcher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class TeamController extends Controller
{
     public function view(Team $team)
     {
        // seasonを紐づけておく
        $team->season;
        $playerModel = new Player();
        $fielders = $playerModel::where('team_id', $team->id)
            ->orderBy(\DB::raw('position_main != ' . PlayerPosition::PITHCER), 'DESC')
            ->orderBy(\DB::raw('number::numeric'), 'ASC')
            ->get();

        $pitchers = $playerModel::where('team_id', $team->id)
            ->where(function($q){
                $q->where('position_main', PlayerPosition::PITHCER)
                    ->orWhere('p_game', '>' , 0);
            })
            ->orderBy(\DB::raw('number::numeric'), 'ASC')
            ->get();

         return [
            'team' => $team,
            'fielders' => $fielders,
            'pitchers' => $pitchers,
         ];
     }

    public function getOptions(Season $season)
    {
        $baseTeamModel = new Team();
        return $baseTeamModel
            ->select('id as value', 'name as text')
            ->where('season_id', $season->id)
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function getMonthList(Team $team)
    {
        $gameModel = new Game();
        return $gameModel
            ->select(\DB::raw('to_char(date,\'YYYY-MM\') as month'))
            ->where(function($q) use ($team) {
                $q->where('home_team_id', $team->id)
                    ->orWhere('visitor_team_id' , $team->id);
            })
            ->groupBy(\DB::raw('to_char(date,\'YYYY-MM\')'))
            ->orderBy(\DB::raw('to_char(date,\'YYYY-MM\')'), 'ASC')
            ->get();
    }

    public function getMonthInfo(Team $team, string $month)
    {
        $gameModel = new Game();
        $gamePitcherModel = new GamePitcher();

        $startDate = new Carbon($month . '-01');
        $endDate = (clone $startDate)->endOfMonth();

        $monthInfo = [];
        $startWeek = $startDate->format('w');
        if ($startWeek == 0) {
            $startWeek = 7;
        }

        $raw = 0;
        for ($i = 1;$i < $startWeek;$i++) {
            if (empty($monthInfo)) {
                $raw++;
                $monthInfo[$raw] = [];
            }

            $monthInfo[$raw][$i] = [];
        }

        $targetDate = clone $startDate;
        while (true) {
            if ($targetDate->format('w') == 1) {
                $raw++;
                $monthInfo[$raw] = [];
            }

            $targetGame = $gameModel->where(function($q) use ($team) {
                    $q->where('home_team_id', $team->id)
                        ->orWhere('visitor_team_id' , $team->id);
                })
                ->where('date', $targetDate)
                ->with('home_team', 'visitor_team')
                ->first();

            if (empty($targetGame)) {
                $monthInfo[$raw][$targetDate->format('w')] = [
                    'date' => $targetDate->format('j'),
                    'game' => [],
                    'win' => null,
                    'lose' => null,
                    'save' => null,
                ];
            } else {
                $winPlayer = $gamePitcherModel->where('game_id', $targetGame->id)
                    ->where('win_flag', true)
                    ->with('player')
                    ->first();
                $losePlayer = $gamePitcherModel->where('game_id', $targetGame->id)
                    ->where('lose_flag', true)
                    ->with('player')
                    ->first();
                $savePlayer = $gamePitcherModel->where('game_id', $targetGame->id)
                    ->where('save_flag', true)
                    ->with('player')
                    ->first();

                $monthInfo[$raw][$targetDate->format('w')] = [
                    'date' => $targetDate->format('j'),
                    'game' => $targetGame,
                    'win' => $winPlayer ? $winPlayer->player : null,
                    'lose' => $losePlayer ? $losePlayer->player : null,
                    'save' => $savePlayer ? $savePlayer->player : null,
                ];

            }
            $targetDate = $targetDate->addDay();
 
            if ($endDate < $targetDate) {
                break;
            }
        }

        \Log::debug($monthInfo);

        return $monthInfo;
    }
}
