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
        $gamePitcherModel = new GamePitcher();

        $player->team->season;

         return [
            'player' => $player,
            'fielder_histories' => $playModel->getFielderHistory($player),
            'pitcher_histories' => $gamePitcherModel->getPitcherHistory($player),
         ];
     }

}
