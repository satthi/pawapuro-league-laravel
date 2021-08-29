<?php

namespace App\Http\Controllers;

use App\Enums\GameBoardStatus;
use App\Enums\PlayType;
use App\Enums\Position;
use App\Http\Requests\GameAutoRequest;
use App\Http\Requests\GameEndRequest;
use App\Http\Requests\GamePinchHitterRequest;
use App\Http\Requests\GamePinchRunnerRequest;
use App\Http\Requests\GamePlayRequest;
use App\Http\Requests\GameProbablePitcherRequest;
use App\Http\Requests\GameRequest;
use App\Http\Requests\GameStealRequest;
use App\Models\Game;
use App\Models\GamePitcher;
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
        } elseif ($game->board_status == GameBoardStatus::STATUS_GAMEENDED) {
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

   public function saveGameStart(Request $request, Game $game)
   {
        // スタメンデータのコピー
        (new Play())->setStamen($game);
        $game->gameUpdate($game);
   }

   public function savePlay(GamePlayRequest $request, Game $game)
    {
        $requestData = $request->all();

        // 打撃成績の保存
        (new Play())->saveDageki($requestData, $game);
        $game->gameUpdate($game);
    }

    public function savePointOnly(Request $request, Game $game)
    {
        $omoteura = $game->inning % 10;
        if ($omoteura == 1) {
            // 表
            $teamType = 'visitor_team';
            $teamId = $game->visitor_team_id;
        } elseif ($omoteura == 2) {
            $teamType = 'home_team';
            $teamId = $game->home_team_id;
        } else {
            // エラー
        }

        $requestData = $request->all();
        $playModel = new Play();
        $member = $playModel->getMember($game);
        $nowPitcherId = $playModel->getNowPitcherId($member, $game);

        Play::create([
            'game_id' => $game->id,
            'team_id' => $teamId,
            'inning' => $game->inning,
            'type' => PlayType::TYPE_POINT_ONLY,
            'result_id' => null,
            'out_count' => $requestData['out'],
            'point_count' => $requestData['point'],
            'player_id' => null,
            'pitcher_id' => $nowPitcherId,
            'dajun' => null,
            'position' => null,
        ]);

        $game->gameUpdate($game);
    }

    public function savePinchHitter(GamePinchHitterRequest $request, Game $game, string $teamType)
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

    public function savePinchRunner(GamePinchRunnerRequest $request, Game $game, string $teamType)
    {
        $requestData = $request->all();
        $playModel = new Play();
        $member = $playModel->getMember($game);
        $baseRunnerId = $requestData['base_runner_id'];
        $pinchRunnerInfo = Player::find($requestData['pinch_runner_id']);
        $inningInfo = $playModel->getInningInfo($game);

        $memberLists = $teamType == 'home' ? $member['home_team'] : $member['visitor_team'];
        foreach ($memberLists as $memberList) {
            if ($memberList['player']['id'] == $baseRunnerId) {
                // 代走処理
                Play::create([
                    'game_id' => $game->id,
                    'team_id' => $pinchRunnerInfo->team_id,
                    'inning' => $game->inning,
                    'type' => PlayType::TYPE_MEMBER_CHANGE,
                    'result_id' => null,
                    'out_count' => null,
                    'point_count' => null,
                    'player_id' => $pinchRunnerInfo->id,
                    'pitcher_id' => null,
                    'dajun' => $memberList['dajun'],
                    'position' => Position::POSITION_PR,
                ]);

                return;
            }
        }

        // error
    }

    public function saveStealSuccess(GameStealRequest $request, Game $game)
    {
        $playModel = new Play();
        $requestData = $request->all();
        $stealPlayerId = $requestData['steal_player_id'];
        $stealPlayer = Player::find($stealPlayerId);
        $member = $playModel->getMember($game);
        $nowPitcherId = $playModel->getNowPitcherId($member, $game);

        // 盗塁成功
        Play::create([
            'game_id' => $game->id,
            'team_id' => $stealPlayer->team_id,
            'inning' => $game->inning,
            'type' => PlayType::TYPE_STEAL,
            'result_id' => null,
            'out_count' => 0,
            'point_count' => null,
            'player_id' => $stealPlayer->id,
            'pitcher_id' => $nowPitcherId,
            'dajun' => null,
            'position' => null,
        ]);
        $game->gameUpdate($game);
    }

    public function saveStealFail(GameStealRequest $request, Game $game)
    {
        $playModel = new Play();
        $requestData = $request->all();
        $stealPlayerId = $requestData['steal_player_id'];
        $stealPlayer = Player::find($stealPlayerId);
        $member = $playModel->getMember($game);
        $nowPitcherId = $playModel->getNowPitcherId($member, $game);

        // 盗塁失敗
        Play::create([
            'game_id' => $game->id,
            'team_id' => $stealPlayer->team_id,
            'inning' => $game->inning,
            'type' => PlayType::TYPE_STEAL,
            'result_id' => null,
            'out_count' => 1,
            'point_count' => null,
            'player_id' => $stealPlayer->id,
            'pitcher_id' => $nowPitcherId,
            'dajun' => null,
            'position' => null,
        ]);
        $game->gameUpdate($game);
    }


    public function savePositionChange(Request $request, Game $game, string $teamType)
    {
        $playModel = new Play();
        // 現在のメンバー情報との差分を見る
        $requestData = $request->all();
        $member = $playModel->getMember($game);
        $memberLists = $teamType == 'home' ? $member['home_team'] : $member['visitor_team'];
        $teamId = $teamType == 'home' ? $game->home_team_id : $game->visitor_team_id;

        foreach ($memberLists as $dajun => $memberList) {
            $changeMemberData = $requestData[$dajun];
            // requestDataはbase_positionで比較する
            if (
                $memberList['position']['value'] != $requestData[$dajun]['base_position']['value'] || 
                $memberList['player']['id'] != $requestData[$dajun]['player']['id']
            ) {
                // 守備変更
                Play::create([
                    'game_id' => $game->id,
                    'team_id' => $teamId,
                    'inning' => $game->inning,
                    'type' => PlayType::TYPE_MEMBER_CHANGE,
                    'result_id' => null,
                    'out_count' => null,
                    'point_count' => null,
                    'player_id' => $requestData[$dajun]['player']['id'],
                    'pitcher_id' => null,
                    'dajun' => $memberList['dajun'],
                    'position' => $requestData[$dajun]['base_position']['value'],
                ]);
            }
        }
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

    public function gameEndPlay(GameEndRequest $request, Game $game)
    {
        $requestData = $request->all();
        $game->gameEndPlay($requestData, $game);
    }

    ### ゲーム結果表示
    public function summary(Game $game)
    {
        // ピッチャー情報を取得して勝ち投手/負け投手/セーブ投手を取得
        $winPitcher = GamePitcher::where('game_id', $game->id)
            ->where('win_flag', true)
            ->with('player')
            ->first();
        $losePitcher = GamePitcher::where('game_id', $game->id)
            ->where('lose_flag', true)
            ->with('player')
            ->first();
        $savePitcher = GamePitcher::where('game_id', $game->id)
            ->where('save_flag', true)
            ->with('player')
            ->first();

        $hrPlayers = Play::where('game_id', $game->id)
            ->with('player')
            ->with('pitcher')
            ->join('results', 'results.id', '=', 'plays.result_id')
            ->where('results.hr_flag', true)
            ->get();

        return [
            'winPitcher' => $winPitcher,
            'losePitcher' => $losePitcher,
            'savePitcher' => $savePitcher,
            'hrPlayers' => $hrPlayers,
        ];
    }

    public function fielderSummary(Game $game, string $type)
    {
        if ($type == 'home') {
            $teamId = $game->home_team_id;
        } else {
            $teamId = $game->visitor_team_id;
        }

        $playModel = new Play();
        return $playModel->getFielderSummary($game, $teamId);
    }

    public function pitcherSummary(Game $game, string $type)
    {
    }



}
