<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Season extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'start_date',
        'regular_flag',
        'game_count',
    ];
    protected $appends = [
        'is_deletable',
    ];

    #### season make
    public function add($requestData)
    {
        DB::transaction(function () use ($requestData) {
            $season = $this::create($requestData);
            $seasonId = $season->id;

            $baseTeams = BaseTeam::whereIn('id', $requestData['selected_teams'])->get();

            foreach ($baseTeams as $baseTeam) {
                $teamCopy = $baseTeam->toArray();
                unset($teamCopy['id']);
                unset($teamCopy['created_at']);
                unset($teamCopy['updated_at']);
                unset($teamCopy['is_deletable']);

                $teamData = array_merge($teamCopy, [
                    'base_team_id' => $baseTeam->id,
                    'season_id' => $seasonId,
                ]);
                // team作成
                $team = Team::create($teamData);

                foreach ($baseTeam->base_players as $basePlayer) {
                    $playerCopy = $basePlayer->toArray();
                    unset($playerCopy['id']);
                    unset($playerCopy['base_team_id']);
                    unset($playerCopy['created_at']);
                    unset($playerCopy['updated_at']);

                    $playerData = array_merge($playerCopy, [
                        'base_player_id' => $basePlayer->id,
                        'team_id' => $team->id,
                    ]);
                    \Log::debug($playerData);
                    // player 作成
                    Player::create($playerData);
                }
            }
        });

    }

    ####  attirbute
    /**
     * 削除可能か
     *
     * @param  string  $value
     * @return string
     */
    public function getIsDeletableAttribute($value)
    {
        // 後調整
        return true;
    }

}
