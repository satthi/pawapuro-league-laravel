<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameAutoRequest;
use App\Http\Requests\GameRequest;
use App\Models\Game;
use App\Models\Season;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
     public function index(Season $season)
     {
         return (new Game())->getIndexList($season->id);
     }

   public function add(GameRequest $request, Season $season)
    {
        Game::create($request->all());
    }
   public function autoAdd(GameAutoRequest $request, Season $season)
    {
        (new Game())->autoAdd($request->all(), $season->id);
        // \Log::debug('HHH');
        // Game::create($request->all());
    }

    public function destroy(Game $game)
    {
        $game->delete();

        return $game;
    }
}
