<?php

namespace App\Http\Controllers;

use App\Enums\PlayerPosition;
use App\Models\Player;
use App\Models\Season;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

        // dump($fielder->toArray());
        // exit;
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
            ->get();
    }
}
