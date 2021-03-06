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
        // baseの集計は不要
        $season->shukei(false);
    }
   public function autoAdd(GameAutoRequest $request, Season $season)
    {
        (new Game())->autoAdd($request->all(), $season->id);
        // baseの集計は不要
        $season->shukei(false);
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
        $gameModel = new Game();
        return [
            'home' => $playerModel->getProbablePitcherOptions($game, $game->home_team_id),
            'visitor' => $playerModel->getProbablePitcherOptions($game, $game->visitor_team_id),
            'home_hisory' => $playerModel->getPitcherHistory($game, $game->home_team_id),
            'visitor_hisory' => $playerModel->getPitcherHistory($game, $game->visitor_team_id),
            'home_game_schedule' => $gameModel->getGameSchedule($game, $game->home_team_id),
            'visitor_game_schedule' => $gameModel->getGameSchedule($game, $game->visitor_team_id),
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
                'now_player' => null,
                'now_pitcher' => null,
                'inning_info' => $playModel->getInningInfo($game),
                'pithcer_info' => [],
                'walk' => false,
                'manrui_walk' => false,
            ];
        } elseif ($game->board_status == GameBoardStatus::STATUS_GAME) {
            // 試合中
            $member = $playModel->getMember($game);
            $nowPlayer = $playModel->getNowPlayerInfo($member, $game);
            $nowPithcer = $playModel->getNowPitcherInfo($member, $game);

            // 四球判定
            $rand = rand(0, 10000);
            $pWalkRitsu = $nowPithcer['player']->p_walk_ritsu;
            $walkRitsu = $nowPlayer['player']->walk_ritsu;


            $walKCheck = $pWalkRitsu * $walkRitsu * 100 > $rand;
            $manruiWalKCheck = $pWalkRitsu * $walkRitsu * 50 > $rand;

            return [
                'member' => $member,
                'now_player' => $nowPlayer,
                'now_pitcher' => $nowPithcer,
                'inning_info' => $playModel->getInningInfo($game),
                'pithcer_info' => [],
                'walk' => $walKCheck,
                'manrui_walk' => $manruiWalKCheck,
            ];
        } elseif ($game->board_status == GameBoardStatus::STATUS_INNING_END) {
            // 交代
            $member = $playModel->getMember($game);
            return [
                'member' => $member,
                'now_player' => null,
                'now_pitcher' => null,
                'inning_info' => $playModel->getInningInfo($game),
                'pithcer_info' => [],
                'walk' => false,
                'manrui_walk' => false,
            ];
        } elseif ($game->board_status == GameBoardStatus::STATUS_GAMEEND) {
            // 試合終了
            $member = $playModel->getMember($game);
            return [
                'member' => $member,
                'now_player' => null,
                'now_pitcher' => null,
                'inning_info' => $playModel->getInningInfo($game),
                'pithcer_info' => $playModel->getPitcherInfo($game),
                'walk' => false,
                'manrui_walk' => false,
            ];
        } elseif ($game->board_status == GameBoardStatus::STATUS_GAMEENDED) {
            // 試合終了
            $member = $playModel->getMember($game);
            return [
                'member' => $member,
                'now_player' => null,
                'now_pitcher_id' => null,
                'inning_info' => $playModel->getInningInfo($game),
                'pithcer_info' => $playModel->getPitcherInfo($game),
                'walk' => false,
                'manrui_walk' => false,
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
        $nowPitcher = $playModel->getNowPitcherInfo($member, $game);

        Play::create([
            'game_id' => $game->id,
            'team_id' => $teamId,
            'inning' => $game->inning,
            'type' => PlayType::TYPE_POINT_ONLY,
            'result_id' => null,
            'out_count' => $requestData['out'],
            'point_count' => $requestData['point'],
            'player_id' => null,
            'pitcher_id' => $nowPitcher['player']->id,
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
        $nowPlayer = $playModel->getNowPlayerInfo($member, $game);
        $pinchHitterInfo = Player::find($requestData['pinch_hitter_id']);
        $inningInfo = $playModel->getInningInfo($game);

        $memberLists = $teamType == 'home' ? $member['home_team'] : $member['visitor_team'];
        foreach ($memberLists as $memberList) {
            if ($memberList['player']['id'] == $nowPlayer['player']->id) {
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
        $nowPitcher = $playModel->getNowPitcherInfo($member, $game);

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
            'pitcher_id' => $nowPitcher['player']->id,
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
        $nowPitcher = $playModel->getNowPitcherInfo($member, $game);

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
            'pitcher_id' => $nowPitcher['player']->id,
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
                if ($memberList['dajun'] == 'P') {
                    $memberList['dajun'] = 10;
                }
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

   public function backGame(Request $request, Game $game)
    {
        $game->backGame();
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
        $pitcherInfo = (new GamePitcher())->topPitcherSeisekiInfo($game);

        $hrPlayers = (new Play())->getTopSummaryHr($game);

        return [
            'winPitcher' => $pitcherInfo['winPitcher'],
            'losePitcher' => $pitcherInfo['losePitcher'],
            'savePitcher' => $pitcherInfo['savePitcher'],
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
        if ($type == 'home') {
            $teamId = $game->home_team_id;
        } else {
            $teamId = $game->visitor_team_id;
        }

        $gamePitcherModel = new GamePitcher();

        return $gamePitcherModel->getPitcherInfo($game->id, $teamId);
    }



}
