<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameAutoRequest;
use App\Http\Requests\GameProbablePitcherRequest;
use App\Http\Requests\GameRequest;
use App\Models\Game;
use App\Models\Player;
use App\Models\Season;
use App\Models\Stamen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
     public function index(Season $season)
     {
         return (new Game())->getIndexList($season->id);
     }

    public function view(Game $game)
    {
        return (new Game())->getViewInfo($game->id);
    }

   public function add(GameRequest $request, Season $season)
    {
        Game::create($request->all());
    }
   public function autoAdd(GameAutoRequest $request, Season $season)
    {
        (new Game())->autoAdd($request->all(), $season->id);
    }

    public function destroy(Game $game)
    {
        $game->delete();

        return $game;
    }

    ## 予告先発
    public function probablePitcherUpdate(GameProbablePitcherRequest $request, Game $game)
    {
        $game->update($request->all());
    }

    public function getProbablePitcherOptions(Game $game)
    {
        $playerModel = new Player();
        return [
            'home' => $playerModel->select('id as value', \DB::raw('number || \'. \' || name as "text"'))
                ->where(['team_id' => $game->home_team_id])
                ->get(),
            'visitor' => $playerModel->select('id as value', \DB::raw('number || \'. \' || name as "text"'))
                ->where(['team_id' => $game->visitor_team_id])
                ->get(),
        ];
    }

    ## スタメン
    public function getStamenInitialData(Game $game, string $stamenType)
    {
        return (new Stamen())->getStamenInitialData($game, $stamenType);
    }

    public function stamenEdit(Request $request, Game $game, string $stamenType)
    {
        return (new Stamen())->setStamen($request->all(), $game, $stamenType);
    }

    ## ゲームTOPのスタメン情報取得
    public function getStamen(Game $game)
    {
        return (new Stamen())->getStamen($game);
        if ($stamenType == 'visitor') {
            $teamId = $game->visitor_team_id;
            $probablePitcherId = $game->visitor_probable_pitcher_id;
        } else if ($stamenType == 'home') {
            $teamId = $game->home_team_id;
            $probablePitcherId = $game->home_probable_pitcher_id;
        } else {
            // error.
        }

        // 現在のスタメンの編集ということで現在情報を取得
        $stamens = $this::where('game_id', $game->id)
            ->where('team_id', $teamId)
            ->with('player')
            ->orderBy('dajun', 'ASC')
            ->get();

        return (new Stamen())->showStamenData($stamens, $teamId);
    }

}
