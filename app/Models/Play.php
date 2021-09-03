<?php

namespace App\Models;

use App\Enums\PlayType;
use App\Enums\Position;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function getMember(Game $game)
    {
        // memberにカラム処理のみ
        $playForMembers = $this::whereIn('type', [PlayType::TYPE_STAMEN, PlayType::TYPE_MEMBER_CHANGE])
            ->where('game_id', $game->id)
            ->with('player')
            ->orderBy('id', 'ASC')
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
        $member = [];
        foreach ($playForMembers as $playForMember) {
            $memberIds[] = $playForMember->player->id;// 一度でも登場したことがある人をセットする

            $teamType = $playForMember->team_id == $game->home_team_id ? 'home_team' : 'visitor_team';
            $beforeBasePosition = $member[$teamType][$playForMember->dajun]['base_position'] ?? null;
            // 上書きしていくことで最新のメンバーを設定
            $member[$teamType][$playForMember->dajun] = [
                'dajun' => $playForMember->dajun == 10 ? 'P' : (string)$playForMember->dajun,
                'position' => $positionOptions[$playForMember->position],
                'player' => $playForMember->player->toArray(),
                'base_position' => $beforeBasePosition,
            ];

            // 代打/代走を除いてセットする
            if ($playForMember->position <= 10) {
                $member[$teamType][$playForMember->dajun]['base_position'] = $positionOptions[$playForMember->position];
            }
        }

        $hikaePlayers = Player::where('team_id', $game->home_team_id)
            ->whereNotIn('id', $memberIds)
            ->get();
        $key = 10;
        $member['home_team_hikae'] = [];
        foreach ($hikaePlayers as $hikaePlayer) {
            $key++;
            $member['home_team_hikae'][$key] = $hikaePlayer;
        }


        $hikaePlayers = Player::where('team_id', $game->visitor_team_id)
            ->whereNotIn('id', $memberIds)
            ->get();
        $key = 10;
        $member['visitor_team_hikae'] = [];
        foreach ($hikaePlayers as $hikaePlayer) {
            $key++;
            $member['visitor_team_hikae'][$key] = $hikaePlayer;
        }

        return $member;
    }

    public function getNowPlayerId($member, Game $game)
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

        foreach ($member[$teamType] as $dajun => $teamMember) {
            if ($dajun == $targetDajun) {
                return $teamMember['player']['id'];
            }
        }

        // 抜けることはないはず。あったらエラー
    }

    public function getNowPitcherId($member, Game $game)
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

        foreach ($member[$teamType] as $teamMember) {
            if ($teamMember['position']['value'] == Position::POSITION_P) {
                return $teamMember['player']['id'];
            }
        }
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
            ->join('results', 'results.id', '=', 'plays.result_id')
            ->where('results.hr_flag', true)
            ->get()
            ->toArray();
        foreach ($hrPlayers as $hrPlayerKey => $hrPlayer) {
            $hrPlayers[$hrPlayerKey]['hr_count'] = $this::where('player_id', $hrPlayer['player_id'])
                ->join('results', 'results.id', '=', 'plays.result_id')
                ->where('results.hr_flag', true)
                ->where('plays.id', '<=', $hrPlayer['id'])
                ->get()
                ->count();
        }

        return $hrPlayers;

    }


}
