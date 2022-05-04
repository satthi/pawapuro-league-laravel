<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerTradeRequest;
use App\Enums\PlayerPosition;
use App\Models\Play;
use App\Models\Player;
use App\Models\Season;
use App\Models\GamePitcher;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
     public function view(Player $player)
     {
        $playModel = new Play();
        $playerModel = new Player();
        $playerModel = new Player();
        $gamePitcherModel = new GamePitcher();

        $player->team->season;
        $player->base_player;

         return [
            'player' => $player,
            'monthly_fielder_infos' => $playModel->getMonthlyFielder($player),
            'monthly_pitcher_infos' => $gamePitcherModel->getMothryPitcher($player),
            'fielder_histories' => $playModel->getFielderHistory($player),
            'pitcher_histories' => $gamePitcherModel->getPitcherHistory($player),
            'season_fielder_histories' => $playerModel->getSeasonFielderHistory($player),
            'season_pitcher_histories' => $playerModel->getSeasonPitcherHistory($player),
         ];
     }

    public function getOptions(Season $season)
    {
        $playerModel = new Player();
        return $playerModel
            ->select('players.id as value', \DB::raw('\'[\' || teams.ryaku_name || \']\' || \'[\' || players.number || \']\' || players.name as text'))
            ->join('teams', 'teams.id', '=', 'players.team_id')
            ->where('teams.season_id', $season->id)
            ->orderBy('teams.id', 'ASC')
            ->orderBy(\DB::raw('players.number::integer'), 'ASC')
            ->get();
    }

    public function trade(PlayerTradeRequest $request)
    {
        (new Player())->trade($request->all());
    }

}
