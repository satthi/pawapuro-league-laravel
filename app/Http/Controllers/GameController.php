<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameRequest;
use App\Models\Game;
use App\Models\Season;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
   public function add(GameRequest $request, Season $season)
    {
        Game::create($request->all());
    }

    public function destroy(Game $game)
    {
        $game->delete();

        return $game;
    }
}
