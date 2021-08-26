<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameAutoRequest;
use App\Http\Requests\GameProbablePitcherRequest;
use App\Http\Requests\GameRequest;
use App\Models\Game;
use App\Models\Play;
use App\Models\Player;
use App\Models\Season;
use App\Models\Stamen;
use App\Models\Result;
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
    }

    ## ゲーム情報の取得
    public function getPlay(Game $game)
    {
        $stamenModel = new Stamen();
        $playModel = new Play();
        if (is_null($game->inning)) {
            $stamen = $stamenModel->getStamen($game);
            return [
                'member' => [
                    'home_team' => $stamen['home_team']['stamen'],
                    'visitor_team' => $stamen['visitor_team']['stamen'],
                ],
                'now_player_id' => null,
                'now_pitcher_id' => null,
            ];
        } else {
            // 試合中
            $member = $playModel->getMember($game);
            $nowPlayerId = $playModel->getNowPlayerId($member, $game);
            $nowPithcerId = $playModel->getNowPitcherId($member, $game);
            return [
                'member' => $member,
                'now_player_id' => $nowPlayerId,
                'now_pitcher_id' => $nowPithcerId,

            ];
        }
    }

    public function getResult()
    {
        return Result::orderBy('id', 'ASC')->get();
    }


   public function savePlay(Request $request, Game $game)
    {
        $requestData = $request->all();

        // requestは後で調整するかも
        if (is_null($game->inning)) {
            // 試合初期パターン
            $gameUpdateData = [
                'inning' => 11,
                'out' => 0,
                'home_point' => 0,
                'visitor_point' => 0,
            ];
            $game->update($gameUpdateData);

            // スタメンデータのコピー
            (new Play())->setStamen($game);
        } elseif (!is_null($requestData['selectedResult'])) {
            // 更新方法の後調整(集計をし直しにすることで全体を共通化したい)
            // 打撃情報の保存
            if ($game->inning % 10 == 1) {
                $pointType = 'home_point';
            } elseif ($game->inning % 10 == 2) {
                $pointType = 'visitor_point';
            } else {
                // エラー
            }
            $gameUpdateData = [
                'inning' => 11,
                'out' => $game->out + $requestData['out'],
                $pointType => $game->{$pointType} + $requestData['point'],
                'visitor_point' => 0,
            ];
            $game->update($gameUpdateData);

            // 打撃成績の保存
            (new Play())->saveDageki($requestData, $game);
            // dump($requestData);
        }
    }

   public function backPlay(GameRequest $request, Game $game)
    {
        
    }

}
