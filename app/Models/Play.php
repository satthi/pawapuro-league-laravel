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

    public function setStamen(Game $game)
    {
        $stamen = (new Stamen())->getStamen($game);
        foreach ($stamen as $teamType => $teamStamen) {
            $teamId = $teamType == 'home_team' ? $game->home_team_id : $game->visitor_team_id;
            foreach ($teamStamen['stamen'] as $dajun => $stamenInfo) {
                $this::create([
                    'game_id' => $game->id,
                    'team_id' => $teamId,
                    'inning' => $game->inning,
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

        $member = [];
        foreach ($playForMembers as $playForMember) {
            $teamType = $playForMember->team_id == $game->home_team_id ? 'home_team' : 'visitor_team';
            // 上書きしていくことで最新のメンバーを設定
            $member[$teamType][$playForMember->dajun] = [
                'dajun' => $playForMember->dajun == 10 ? 'P' : (string)$playForMember->dajun,
                'position' => $positionOptions[$playForMember->position],
                'player' => $playForMember->player->toArray(),
            ];
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
            $teamType = 'visitor_team';
            $teamId = $game->visitor_team_id;
        } elseif ($omoteura == 2) {
            $teamType = 'home_team';
            $teamId = $game->home_team_id;
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


}
