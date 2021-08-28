<?php

namespace App\Http\Controllers;

use App\Enums\GameBoardStatus;
use App\Enums\PlayType;
use App\Enums\Position;
use App\Http\Requests\GameAutoRequest;
use App\Http\Requests\GameProbablePitcherRequest;
use App\Http\Requests\GameRequest;
use App\Models\Game;
use App\Models\Play;
use App\Models\Player;
use App\Models\Result;
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
    }

    ## ゲーム情報の取得
    public function getPlay(Game $game)
    {
        $stamenModel = new Stamen();
        $playModel = new Play();
        if ($game->board_status == GameBoardStatus::STATUS_START) {
            $stamen = $stamenModel->getStamen($game);
            return [
                'member' => [
                    'home_team' => $stamen['home_team']['stamen'],
                    'visitor_team' => $stamen['visitor_team']['stamen'],
                    'home_team_hikae' => $stamen['home_team']['hikae'],
                    'visitor_team_hikae' => $stamen['visitor_team']['hikae'],
                ],
                'now_player_id' => null,
                'now_pitcher_id' => null,
                'inning_info' => $playModel->getInningInfo($game),
                'pithcer_info' => [],
            ];
        } elseif ($game->board_status == GameBoardStatus::STATUS_GAME) {
            // 試合中
            $member = $playModel->getMember($game);
            $nowPlayerId = $playModel->getNowPlayerId($member, $game);
            $nowPithcerId = $playModel->getNowPitcherId($member, $game);
            return [
                'member' => $member,
                'now_player_id' => $nowPlayerId,
                'now_pitcher_id' => $nowPithcerId,
                'inning_info' => $playModel->getInningInfo($game),
                'pithcer_info' => [],
            ];
        } elseif ($game->board_status == GameBoardStatus::STATUS_INNING_END) {
            // 交代
            $member = $playModel->getMember($game);
            return [
                'member' => $member,
                'now_player_id' => null,
                'now_pitcher_id' => null,
                'inning_info' => $playModel->getInningInfo($game),
                'pithcer_info' => [],
            ];
        } elseif ($game->board_status == GameBoardStatus::STATUS_GAMEEND) {
            // 試合終了
            $member = $playModel->getMember($game);
            return [
                'member' => $member,
                'now_player_id' => null,
                'now_pitcher_id' => null,
                'inning_info' => $playModel->getInningInfo($game),
                'pithcer_info' => $playModel->getPitcherInfo($game),
            ];
        }
        // error.
    }

    public function getResult()
    {
        return Result::orderBy('id', 'ASC')
            ->get()
            ->keyBy('id');
    }


   public function savePlay(Request $request, Game $game)
    {
        $requestData = $request->all();

        // requestは後で調整するかも
        if (is_null($game->inning)) {
            // スタメンデータのコピー
            (new Play())->setStamen($game);
        } elseif (!is_null($requestData['selectedResult'])) {
            // 打撃成績の保存
            (new Play())->saveDageki($requestData, $game);
        }
        $game->gameUpdate($game);
    }

    public function savePinchHitter(Request $request, Game $game, string $teamType)
    {
        $requestData = $request->all();
        $playModel = new Play();
        $member = $playModel->getMember($game);
        $nowPlayerId = $playModel->getNowPlayerId($member, $game);
        $pinchHitterInfo = Player::find($requestData['pinch_hitter_id']);
        $inningInfo = $playModel->getInningInfo($game);

        $memberLists = $teamType == 'home' ? $member['home_team'] : $member['visitor_team'];
        foreach ($memberLists as $memberList) {
            if ($memberList['player']['id'] == $nowPlayerId) {
                // 代打処理
                Play::create([
                    'game_id' => $game->id,
                    'team_id' => $pinchHitterInfo->team_id,
                    'inning' => $game->inning,
                    'type' => PlayType::TYPE_MEMBER_CHANGE,
                    'result_id' => null,
                    'out_count' => null,
                    'point_count' => null,
                    'player_id' => $pinchHitterInfo->id,
                    'pitcher_id' => null,
                    'dajun' => $memberList['dajun'],
                    'position' => Position::POSITION_PH,
                ]);

                return;
            }
        }

        // error
    }

   public function backPlay(Request $request, Game $game)
    {
        (new Play())->backPlay($game);
        $game->gameUpdate($game);
    }

   public function nextInningPlay(Request $request, Game $game)
    {
        $game->nextInningUpdate($game);
    }

    public function gameEndPlay(Request $request, Game $game)
    {
        $requestData = $request->all();
        $game->gameEndPlay($requestData, $game);
    }
}
