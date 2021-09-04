<?php

namespace App\Http\Controllers;

use App\Enums\PlayerPosition;
use App\Models\Player;
use App\Models\Season;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
     public function view(Player $player)
     {
        $player->team->season;

         return [
            'player' => $player,
         ];
     }

}
