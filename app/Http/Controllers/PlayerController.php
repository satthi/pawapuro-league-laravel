<?php

namespace App\Http\Controllers;

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
            'fielder_histories' => $playModel->getFielderHistory($player),
            'pitcher_histories' => $gamePitcherModel->getPitcherHistory($player),
            'season_fielder_histories' => $playerModel->getSeasonFielderHistory($player),
            'season_pitcher_histories' => $playerModel->getSeasonPitcherHistory($player),
         ];
     }

}
