<?php

namespace App\Models;

use App\Enums\PlayType;
use App\Enums\Position;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Play extends Model
{
    use HasFactory;
    protected $fillable = [
        'game_id',
        'team_id',
        'inning',
        'type',
        'result_id',
        'out_count',
        'point_count',
        'player_id',
        'pitcher_id',
        'dajun',
        'position',
    ];

    /**
     * home team
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
    /**
     * home team
     */
    public function pitcher()
    {
        return $this->belongsTo(Player::class, 'pitcher_id');
    }
    /**
     * home team
     */
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    /**
     * home team
     */
    public function result()
    {
        return $this->belongsTo(Result::class, 'result_id');
    }

    public function setStamen(Game $game)
    {
        $stamen = (new Stamen())->getStamen($game);
        foreach ($stamen as $teamType => $teamStamen) {
            $teamId = $teamType == 'home_team' ? $game->home_team_id : $game->visitor_team_id;
            foreach ($teamStamen['stamen'] as $dajun => $stamenInfo) {
                $this::create([
                    'game_id' => $game->id,
                    'team_id' => $teamId,
                    'inning' => 11, // 1回表としてセット
                    'type' => PlayType::TYPE_STAMEN,
                    'result_id' => null,
                    'out_count' => null,
                    'point_count' => null,
                    'player_id' => $stamenInfo['player']['id'],
                    'pitcher_id' => null,
                    'dajun' => $dajun,
                    'position' => $stamenInfo['position']['value'],
                ]);
            }

        }
    }

    public function getMember(Game $game, $hikaeInclude = true)
    {
        $gameSubDay = (new Carbon($game->date))->subDay()->format('Y/m/d');

        // @todo: リアルタイムに成績を設定

        // memberにカラム処理のみ
        $playForMembers = $this::whereIn('type', [PlayType::TYPE_STAMEN, PlayType::TYPE_MEMBER_CHANGE])
            ->where('game_id', $game->id)
            ->with('player')
            ->orderBy('plays.id', 'ASC')
            ->get();

        $values = Position::getValues();
        $positionOptions = [];
        foreach ($values as $value) {
            $positionOptions[$value] = [
                'value' => $value,
                'text' => Position::getDescription($value)
            ];
        }

        $memberIds = [];
        $playerIds = [];
        $member = [];
        foreach ($playForMembers as $playForMember) {
            $memberIds[] = $playForMember->player->id;// 一度でも登場したことがある人をセットする
            $playerIds[] = $playForMember->player->id;

            $teamType = $playForMember->team_id == $game->home_team_id ? 'home_team' : 'visitor_team';
            $beforeBasePosition = $member[$teamType][$playForMember->dajun]['base_position'] ?? null;

            $playInfo = Play::where('game_id', $game->id)
                ->with('result')
                ->with('pitcher')
                ->where('type', PlayType::TYPE_DAGEKI_KEKKA)
                ->where('player_id', $playForMember->player->id)
                ->orderBy('id', 'ASC')
                ->get();


            // 上書きしていくことで最新のメンバーを設定
            $member[$teamType][$playForMember->dajun] = [
                'dajun' => $playForMember->dajun == 10 ? 'P' : (string)$playForMember->dajun,
                'position' => $positionOptions[$playForMember->position],
                'player' => $playForMember->player->toArray(),
                // 'seiseki' => $playForMember->player->getRecentSeisekiInfo($game),
                'base_position' => $beforeBasePosition,
                'play_info' => $playInfo,
            ];

            // 代打/代走を除いてセットする
            if ($playForMember->position <= 10) {
                $member[$teamType][$playForMember->dajun]['base_position'] = $positionOptions[$playForMember->position];
            }
        }

        if ($hikaeInclude) {
            $hikaePlayers = Player::where('team_id', $game->home_team_id)
                ->where('trade_flag', false)
                ->whereNotIn('id', $memberIds)
                ->orderBy(\DB::raw('number::integer'), 'ASC')
                ->get();
            $key = 10;
            $member['home_team_hikae'] = [];
            foreach ($hikaePlayers as $hikaePlayer) {
                $key++;
                $playerIds[] = $hikaePlayer->id;
                $member['home_team_hikae'][$key] = $hikaePlayer->toArray();
                // $member['home_team_hikae'][$key]['seiseki'] = $hikaePlayer->getRecentSeisekiInfo($game, true);
            }

            $hikaePlayers = Player::where('team_id', $game->visitor_team_id)
                ->where('trade_flag', false)
                ->whereNotIn('id', $memberIds)
                ->orderBy(\DB::raw('number::integer'), 'ASC')
                ->get();
            $key = 10;
            $member['visitor_team_hikae'] = [];
            foreach ($hikaePlayers as $hikaePlayer) {
                $key++;
                $playerIds[] = $hikaePlayer->id;
                $member['visitor_team_hikae'][$key] = $hikaePlayer;
                // $member['visitor_team_hikae'][$key]['seiseki'] = $hikaePlayer->getRecentSeisekiInfo($game, true);
            }
        }

        // dump($member);
        // dump($playerIds);
        // exit;

        $getRecentSeisekiInfoAll = (new Player())->getRecentSeisekiInfoAll($game, $playerIds);
        // dump($getRecentSeisekiInfoAll);
        // exit;
        // dump($member);
        // exit;
        foreach ($member as $type => $member1) {
            foreach ($member1 as $dajun => $member2) {
                if (!empty($member2['player'])) {
                    $member[$type][$dajun]['seiseki'] = $getRecentSeisekiInfoAll[$member2['player']['id']];
                } else {
                    $member[$type][$dajun]['seiseki'] = $getRecentSeisekiInfoAll[$member2['id']];
                }
            }
        }

        // dump($member);
        // exit;

        return $member;
    }

    public function getNowPlayerInfo($member, Game $game)
    {
        // イニングから表か裏かを取得
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
        $lastPlay = $this::where('game_id', $game->id)
            ->where('team_id' , $teamId)
            ->where('type', PlayType::TYPE_DAGEKI_KEKKA)
            ->orderBy('id', 'DESC')
            ->first();

        if (is_null($lastPlay)) {
            $targetDajun = 1;
        } else {
            $targetDajun = $lastPlay->dajun + 1;
            if ($targetDajun == 10) {
                $targetDajun = 1;
            }
        }

        $nowPlayerId = null;
        foreach ($member[$teamType] as $dajun => $teamMember) {
            if ($dajun == $targetDajun) {
                $nowPlayerId = $teamMember['player']['id'];
                break;
            }
        }
        // 抜けることはないはず。あったらエラー

        $nowPlayer = Player::find($nowPlayerId);
        $seiseki = $nowPlayer->getRecentSeisekiInfo($game);
        $playInfo = Play::where('game_id', $game->id)
            ->with('result')
            ->with('pitcher')
            ->where('type', PlayType::TYPE_DAGEKI_KEKKA)
            ->where('player_id', $nowPlayer->id)
            ->orderBy('id', 'ASC')
            ->get();

        return [
            'player' => $nowPlayer,
            'seiseki' => $seiseki,
            'playInfo' => $playInfo,
        ];

    }

    public function getNowPitcherInfo($member, Game $game)
    {

        $omoteura = $game->inning % 10;
        if ($omoteura == 1) {
            // 表
            $teamType = 'home_team';
        } elseif ($omoteura == 2) {
            $teamType = 'visitor_team';
        } else {
            // エラー
        }

        $pitcherId = null;
        foreach ($member[$teamType] as $teamMember) {
            if ($teamMember['position']['value'] == Position::POSITION_P) {
                $pitcherId = $teamMember['player']['id'];
            }
        }

        $newPlayersModel = new Player();

        $nowPitcher = $newPlayersModel::find($pitcherId);
        $seiseki = $nowPitcher->getRecentSeisekiInfo($game);
        $playInfo = Play::where('game_id', $game->id)
            ->leftjoin('results', 'results.id', '=', 'plays.result_id')
            ->where('pitcher_id', $nowPitcher->id)
            ->select([
                \DB::raw('sum(plays.out_count) as inning'),
                $newPlayersModel->fielderSeisekiSelectParts('hit_flag', 'hit'),
                $newPlayersModel->fielderSeisekiSelectParts('hr_flag', 'hr'),
                $newPlayersModel->fielderSeisekiSelectParts('sansin_flag', 'sansin'),
                $newPlayersModel->fielderSeisekiSelectParts('walk_flag', 'walk'),
                $newPlayersModel->fielderSeisekiSelectParts('dead_flag', 'dead'),
                \DB::raw('sum(plays.point_count) as point'),
            ])
            ->first();
        if (is_null($playInfo)) {
            $playInfo = [
                'inning' => 0,
                'hit' => 0,
                'hr' => 0,
                'sansin' => 0,
                'walk' => 0,
                'dead' => 0,
                'point' => 0,
            ];
        } else {
            $playInfo = $playInfo->toArray();
        }

        $playInfo['inning_text'] = floor($playInfo['inning'] / 3) . (($playInfo['inning'] % 3) != 0 ? ' ' .  $playInfo['inning'] % 3 . '/3': '');

        return [
            'player' => $nowPitcher,
            'seiseki' => $seiseki,
            'playInfo' => $playInfo,
        ];

        // 抜けることはないはず。あったらエラー
    }

    public function backPlay($game)
    {
        $playInfos = $this::where('game_id', $game->id)
            ->orderBy('id', 'DESC')
            ->get();

        $beforeInfo = [];
        foreach ($playInfos as $playInfo) {
            // beforeInfoが設定済みで、前と状態が違うものが来たら終了
            if (
                !empty($beforeInfo) &&
                (
                    $playInfo->type != $beforeInfo['type'] ||
                    (
                        $playInfo->team_id != $beforeInfo['team_id'] &&
                        $playInfo->type != PlayType::TYPE_STAMEN
                    )
                )
            ) {
                return;
            }

            $playInfo->delete();
            // 打撃結果/盗塁/ポイントのみは1件だけ処理して終わり
            if (
                $playInfo->type == PlayType::TYPE_DAGEKI_KEKKA || 
                $playInfo->type == PlayType::TYPE_STEAL ||
                $playInfo->type == PlayType::TYPE_POINT_ONLY
            ) {
                return;
            }

            $beforeInfo = [
                'type' => $playInfo->type,
                'team_id' => $playInfo->team_id,
            ];
        }
    }

    public function saveDageki($requestData, $game)
    {
        $member = $this->getMember($game);
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

        $targetDajun = null;
        foreach ($member[$teamType] as $dajun => $teamMember) {
            if ($teamMember['player']['id'] == $requestData['now_player_id']) {
                $targetDajun = $dajun;
            }
        }

        $this::create([
            'game_id' => $game->id,
            'team_id' => $teamId,
            'inning' => $game->inning,
            'type' => PlayType::TYPE_DAGEKI_KEKKA,
            'result_id' => $requestData['selectedResult'],
            'out_count' => $requestData['out'],
            'point_count' => $requestData['point'],
            'player_id' => $requestData['now_player_id'],
            'pitcher_id' => $requestData['now_pitcher_id'],
            'dajun' => $targetDajun,
            'position' => null,
        ]);
    }

    public function getInningInfo($game)
    {
        $playInfos = $this::where('game_id', $game->id)
            ->with('result')
            ->whereIn('type', [PlayType::TYPE_DAGEKI_KEKKA, PlayType::TYPE_STEAL, PlayType::TYPE_POINT_ONLY])
            ->orderBy('id', 'ASC')
            ->get();

        $inningInfo = [
            'inning' => [],
            'home_point' => null,
            'home_hit' => null,
            'visitor_point' => null,
            'visitor_hit' => null,
        ];
        $homeTeamId = $game->home_team_id;
        $visitorTeamId = $game->visitor_team_id;

        $outCount = 0;
        foreach ($playInfos as $playInfo) {
            if ($playInfo->team_id == $homeTeamId) {
                $inningInfo['home_point'] += $playInfo->point_count;
                if (!$inningInfo['home_hit']) {
                    $inningInfo['home_hit'] = 0;
                }
                if (!is_null($playInfo->result) && $playInfo->result->hit_flag) {
                    $inningInfo['home_hit']++;
                }
            } elseif ($playInfo->team_id == $visitorTeamId) {
                $inningInfo['visitor_point'] += $playInfo->point_count;
                if (!$inningInfo['visitor_hit']) {
                    $inningInfo['visitor_hit'] = 0;
                }
                if (!is_null($playInfo->result) && $playInfo->result->hit_flag) {
                    $inningInfo['visitor_hit']++;
                }
            } else {
                // error
            }

            if ($playInfo->point_count > 0) {
                if (!array_key_exists($playInfo->inning, $inningInfo['inning'])) {
                    $inningInfo['inning'][$playInfo->inning] = 0;
                }
                $inningInfo['inning'][$playInfo->inning] += $playInfo->point_count;
            }

            // 3アウトの0を埋める
            $outCount += $playInfo->out_count;
            if ($outCount == 3) {
                if (!array_key_exists($playInfo->inning, $inningInfo['inning'])) {
                    $inningInfo['inning'][$playInfo->inning] = 0;
                }
                $outCount = 0;
            }
        }

        return $inningInfo;
    }

    public function getPitcherInfo(Game $game)
    {
        $homeTeamId = $game->home_team_id;
        $visitorTeamId = $game->visitor_team_id;

        // memberにカラム処理のみ
        $playForPitchers = $this::whereIn('type', [PlayType::TYPE_STAMEN, PlayType::TYPE_MEMBER_CHANGE])
            ->where('game_id', $game->id)
            ->where('position', Position::POSITION_P)
            ->with('player')
            ->orderBy('id', 'ASC')
            ->get();

        $pitcherInfo = [
            'home_team' => [],
            'visitor_team' => [],
        ];

        foreach ($playForPitchers as $playForPitcher) {
            if ($playForPitcher->team_id == $game->home_team_id) {
                $pitcherInfo['home_team'][] = $playForPitcher;
            } elseif ($playForPitcher->team_id == $game->visitor_team_id) {
                $pitcherInfo['visitor_team'][] = $playForPitcher;
            } else {
                // error
            }
        }

        return $pitcherInfo;
    }

    ## summary
    public function getFielderSummary(Game $game, int $teamId)
    {
        $plays = $this->where('game_id', $game->id)
            ->where('team_id', $teamId)
            ->with('player')
            ->with('result')
            ->whereNotNull('player_id')
            ->orderBy('dajun', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        // 試合のmaxの打席数で枠を確保しておく
        $initialWaku = [];
        $wakuCount = 0;
        foreach ($plays as $play) {
            if ($play->dajun != 1) {
                break;
            }
            if ($play->type == PlayType::TYPE_DAGEKI_KEKKA) {
                $wakuCount++;
                $initialWaku[$wakuCount] = null;
            }
        }

        $summary = [];
        $nowDajun = 0;
        $dasekiCount = 0;
        foreach ($plays as $play) {

            // if (!array_key_exists($play->dajun, $summary)) {
            //     $summary[$play->dajun] = [];
            // }
            if (!array_key_exists($play->player_id, $summary)) {
                $summary[$play->player_id] = [
                    // positionをテキストで書く ⑤45みたいな感じで
                    'position' => '',
                    'player' => $play->player,
                    'dageki' => $initialWaku,
                    'seiseki' => $play->player->getTargetDateSeisekiInfo($game->date),
                ];
            }
            if ($play->type == PlayType::TYPE_STAMEN) {
                $summary[$play->player_id]['position'] .= '<span style="font-size:1.5em;">' . Position::getNumberStamen($play->position) .'</span>';
            } elseif ($play->type == PlayType::TYPE_MEMBER_CHANGE) {
                $summary[$play->player_id]['position'] .= Position::getNumberChange($play->position);
            } elseif ($play->type == PlayType::TYPE_DAGEKI_KEKKA) {
                // 打撃結果の表示
                if ($nowDajun != $play->dajun) {
                    $nowDajun = $play->dajun;
                    $dasekiCount = 0;
                }
                $dasekiCount++;
                $summary[$play->player_id]['dageki'][$dasekiCount] = [
                    'result' => $play->result,
                    'result_text' => $play->result->name . ($play->point_count > 0 ? '(' . $play->point_count . ')' : '')
                ];


            }
        }

        $summary = array_merge($summary);

        return $summary;

    }

    ## 画面表示用
    public function getTargetAvgAttribute($value)
    {
        if (!$this->dasu) {
            return '-';
        }

        $avg = sprintf("%.3f", round($this->hit / $this->dasu, 3));

        return preg_replace('/^0/', '' , $avg);
    }

    public function getTargetOpbAttribute($value)
    {
        $obpBunbo = $this->dasu + $this->walk + $this->dead + $this->sac_fly;
        $obpBunshi = $this->hit + $this->walk + $this->dead;
        if ($obpBunbo) {
            return sprintf("%.3f", round($obpBunshi / $obpBunbo, 3));

        } else {
            return '-';
        }
    }

    public function getTargetSlgAttribute($value)
    {
        $bunbo = $this->dasu;
        $bunshi = $this->hit +  $this->hit_2 + $this->hit_3 * 2 + $this->hr * 3;
        if ($bunbo) {
            return sprintf("%.3f", round($bunshi / $bunbo, 3));
        } else {
            return '-';
        }
    }

    public function getTargetOpsAttribute($value)
    {
        $obpBunbo = $this->dasu + $this->walk + $this->dead + $this->sac_fly;
        $obpBunshi = $this->hit + $this->walk + $this->dead;
        if ($obpBunbo) {
            $opb = $obpBunshi / $obpBunbo;

        } else {
            $opb = 0;
        }

        $bunbo = $this->dasu;
        $bunshi = $this->hit +  $this->hit_2 + $this->hit_3 * 2 + $this->hr * 3;
        if ($bunbo) {
            $slg = $bunshi / $bunbo;
        } else {
            $slg = 0;
        }

        return sprintf("%.3f", round($opb + $slg, 3));
    }


    public function getTopSummaryHr($game)
    {
        $hrPlayers = Play::where('game_id', $game->id)
            ->select([
                'plays.id as id',
                'plays.player_id as player_id',
                'plays.pitcher_id as pitcher_id',
            ])
            ->with('player')
            ->with('pitcher')
            ->leftjoin('results', 'results.id', '=', 'plays.result_id')
            ->where('results.hr_flag', true)
            ->get()
            ->toArray();
        foreach ($hrPlayers as $hrPlayerKey => $hrPlayer) {
            $hrPlayers[$hrPlayerKey]['hr_count'] = $this::where('player_id', $hrPlayer['player_id'])
                ->leftjoin('results', 'results.id', '=', 'plays.result_id')
                ->where('results.hr_flag', true)
                ->where('plays.id', '<=', $hrPlayer['id'])
                ->get()
                ->count();
        }

        return $hrPlayers;
    }

    public function getFielderHistory(Player $player)
    {
        $plays = $this::where('player_id', $player->id)
            ->with([
                'game.home_team',
                'game.visitor_team',
                'result',
            ])
            ->get();

        $playHistories = [];
        $initialWaku = [
            'date' => '',
            'vs' => '',
            'daseki' => 0,
            'dasu' => 0,
            'hit' => 0,
            'hr' => 0,
            'daten' => 0,
            'walk' => 0,
            'dead' => 0,
            'steal' => 0,
            'seiseki' => '',
            'position' => '',
            'now_seiseki' => '-',
        ];

        foreach ($plays as $play) {
            if (!array_key_exists($play->game_id, $playHistories)) {
                $playHistories[$play->game_id] = $initialWaku;
                $playHistories[$play->game_id]['game_id'] = $play->game_id;
                $playHistories[$play->game_id]['date'] = $play->game->date; // 表示用調整
                if ($play->game->home_team->id == $play->team_id) {
                    $playHistories[$play->game_id]['vs'] = $play->game->visitor_team->ryaku_name;
                } else {
                    $playHistories[$play->game_id]['vs'] = $play->game->home_team->ryaku_name;
                }
            }
            switch ($play->type) {
                case PlayType::TYPE_STAMEN:
                    if ( $play->dajun < 10) {
                        $playHistories[$play->game_id]['position'] = $play->dajun . '番' . Position::getTextFull($play->position);
                    } else {
                        $playHistories[$play->game_id]['position'] = '先発';
                    }
                    break;
                case PlayType::TYPE_MEMBER_CHANGE:
                    if (!$playHistories[$play->game_id]['position']) {
                        $playHistories[$play->game_id]['position'] = '途中交代';
                    }
                    break;
                case PlayType::TYPE_DAGEKI_KEKKA:
                    $playHistories[$play->game_id]['seiseki'] .= $play->result->name .' '; // 調整
                    $playHistories[$play->game_id]['daseki']++;
                    if ($play->result->dasu_count_flag) {
                        $playHistories[$play->game_id]['dasu']++;
                    }
                    if ($play->result->hit_flag) {
                        $playHistories[$play->game_id]['hit']++;
                    }
                    if ($play->result->hr_flag) {
                        $playHistories[$play->game_id]['hr']++;
                    }
                    $playHistories[$play->game_id]['daten'] += $play->point_count;
                    if ($play->result->walk_flag) {
                        $playHistories[$play->game_id]['walk']++;
                    }
                    if ($play->result->dead_flag) {
                        $playHistories[$play->game_id]['dead']++;
                    }
                    break;
                case PlayType::TYPE_DAGEKI_KEKKA:
                    if ($play->out_count == 0) {
                        $playHistories[$play->game_id]['steal']++;
                    }
                    break;
            }
        }

        $totalDasu = 0;
        $totalHit = 0;
        $totalHr = 0;
        foreach ($playHistories as $gameId => $playHistory) {
            $totalDasu += $playHistory['dasu'];
            $totalHit += $playHistory['hit'];
            $totalHr += $playHistory['hr'];

            if ($totalDasu > 0) {
                $playHistories[$gameId]['now_seiseki'] = preg_replace('/^0/', '', sprintf('%.3f', round($totalHit / $totalDasu, 3))) . '(' . $totalHr . ')';
            }
        }

        return $playHistories;

        // dump($playHistories);
        // exit;

    }

    public function getMonthlyFielder($player)
    {
        $playerModel = new Player();
        $monthlyInfos = $this::where('player_id', $player->id)
            ->join('games', 'games.id', '=', 'plays.game_id')
            ->leftjoin('results', 'results.id', '=', 'plays.result_id')
            ->select([
                \DB::raw('to_char(date,\'YYYY-MM\') as month'),
                \DB::raw('count(DISTINCT(games.id)) AS game'),
                \DB::raw('count(results.id) AS daseki'),
                $playerModel->fielderSeisekiSelectParts('dasu_count_flag', 'dasu'),
                $playerModel->fielderSeisekiSelectParts('hit_flag', 'hit'),
                $playerModel->fielderSeisekiSelectParts('hit_2_flag', 'hit_2'),
                $playerModel->fielderSeisekiSelectParts('hit_3_flag', 'hit_3'),
                $playerModel->fielderSeisekiSelectParts('hr_flag', 'hr'),
                $playerModel->fielderSeisekiSelectParts('sansin_flag', 'sansin'),
                $playerModel->fielderSeisekiSelectParts('heisatsu_flag', 'heisatsu'),
                $playerModel->fielderSeisekiSelectParts('walk_flag', 'walk'),
                $playerModel->fielderSeisekiSelectParts('dead_flag', 'dead'),
                $playerModel->fielderSeisekiSelectParts('bant_flag', 'bant'),
                $playerModel->fielderSeisekiSelectParts('sac_fly_flag', 'sac_fly'),
                \DB::raw('sum(plays.point_count) AS daten'),
                \DB::raw('sum(CASE WHEN plays.type = ' . PlayType::TYPE_STEAL . ' AND plays.out_count = 0 THEN 1 ELSE 0 END) AS steal_success'),
                \DB::raw('sum(CASE WHEN plays.type = ' . PlayType::TYPE_STEAL . ' AND plays.out_count = 1 THEN 1 ELSE 0 END) AS steal_miss'),
            ])
            ->whereIn('type', [PlayType::TYPE_DAGEKI_KEKKA, PlayType::TYPE_STEAL])
            ->groupBy(\DB::raw('to_char(date,\'YYYY-MM\')'))
            ->orderBy(\DB::raw('to_char(date,\'YYYY-MM\')'), 'ASC')
            ->get();

        foreach ($monthlyInfos as $monthlyInfo) {
            $monthlyInfo->append('target_avg');
            $monthlyInfo->append('target_opb');
            $monthlyInfo->append('target_slg');
            $monthlyInfo->append('target_ops');
        }

        return $monthlyInfos;
    }


}
