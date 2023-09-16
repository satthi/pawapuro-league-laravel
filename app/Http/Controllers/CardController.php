<?php

namespace App\Http\Controllers;

use App\Http\Requests\BasePlayerRequest;
use App\Models\Player;
use App\Models\BaseTeam;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function update(Request $request, Player $player)
    {
        $player->update($request->get('player'));
    }
}
