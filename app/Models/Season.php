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
        'regular_flag',
    ];
    protected $appends = [
        'is_deletable',
    ];

    #### season make
    public function add($requestData)
    {
        DB::transaction(function () use ($requestData) {
            $selectedTeams = $requestData['selected_teams'];
            unset($requestData['selected_teams']);
            $season = $this::create($requestData);
            $seasonId = $season->id;

            $baseTeams = BaseTeam::whereIn('id', $selectedTeams)->get();

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
                    unset($playerCopy['hand_p_text']);
                    unset($playerCopy['hand_b_text']);
                    unset($playerCopy['hand_full_text']);
                    unset($playerCopy['position_main_text']);

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

    public function shukei($baseShukei = true)
    {
        $teamModel = new Team();
        $playerModel = new Player();
        $basePlayerModel = new BasePlayer();
        // チームの集計
        $teamModel->shukei($this->id);
        // // 個人の集計
        $playerModel->shukei($this->id);
        // シーズンをまたいだ集計
        if ($baseShukei) {
            $basePlayerModel->shukei();
        }
    }

    public function getNextGame()
    {
        $gameModel = new Game();

        return $gameModel
            ->where('season_id', $this->id)
            ->whereNull('inning')
            ->orderBy('date', 'ASC')
            ->orderBy('id', 'ASC')
            ->first();
    }

    public function getRanking()
    {
        $playerModel = new Player();

        $avgPlayer = $playerModel->where('teams.season_id', $this->id)
            ->where(\DB::raw('(players.dasu::numeric >= teams.game::numeric * 3.1)'), true)
            ->select('players.name as name', 'players.dasu', 'players.avg', 'players.team_id')
            ->with('team')
            ->join('teams', 'teams.id', '=', 'players.team_id')
            ->orderBy('players.avg', 'DESC')
            ->orderBy('players.id', 'ASC')
            ->limit(10)
            ->get()
            ;

        \Log::debug($avgPlayer);
        // \Log::debug($avgPlayer->toSql());

        return [
            'avg' => $avgPlayer,
        ];

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
