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
        'mvp_player_id',
        'b9_1_player_id',
        'b9_2_player_id',
        'b9_3_player_id',
        'b9_4_player_id',
        'b9_5_player_id',
        'b9_6_player_id',
        'b9_7_player_id',
        'b9_8_player_id',
        'b9_9_player_id',
    ];
    protected $appends = [
        'is_deletable',
    ];

    public function mvp_player()
    {
        return $this->belongsTo(Player::class, 'mvp_player_id');
    }
    public function b9_1_player()
    {
        return $this->belongsTo(Player::class, 'b9_1_player_id');
    }
    public function b9_2_player()
    {
        return $this->belongsTo(Player::class, 'b9_2_player_id');
    }
    public function b9_3_player()
    {
        return $this->belongsTo(Player::class, 'b9_3_player_id');
    }
    public function b9_4_player()
    {
        return $this->belongsTo(Player::class, 'b9_4_player_id');
    }
    public function b9_5_player()
    {
        return $this->belongsTo(Player::class, 'b9_5_player_id');
    }
    public function b9_6_player()
    {
        return $this->belongsTo(Player::class, 'b9_6_player_id');
    }
    public function b9_7_player()
    {
        return $this->belongsTo(Player::class, 'b9_7_player_id');
    }
    public function b9_8_player()
    {
        return $this->belongsTo(Player::class, 'b9_8_player_id');
    }
    public function b9_9_player()
    {
        return $this->belongsTo(Player::class, 'b9_9_player_id');
    }


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
            ->with('home_team', 'visitor_team')
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
            ->get();

        $hrPlayer = $playerModel->where('teams.season_id', $this->id)
            ->where('players.hr', '>' , 0)
            ->select('players.name as name', 'players.hr', 'players.team_id')
            ->with('team')
            ->join('teams', 'teams.id', '=', 'players.team_id')
            ->orderBy('players.hr', 'DESC')
            ->orderBy('players.id', 'ASC')
            ->limit(10)
            ->get();

        $datenPlayer = $playerModel->where('teams.season_id', $this->id)
            ->where('players.daten', '>' , 0)
            ->select('players.name as name', 'players.daten', 'players.team_id')
            ->with('team')
            ->join('teams', 'teams.id', '=', 'players.team_id')
            ->orderBy('players.daten', 'DESC')
            ->orderBy('players.id', 'ASC')
            ->limit(10)
            ->get();

        $stealPlayer = $playerModel->where('teams.season_id', $this->id)
            ->where('players.steal_success', '>' , 0)
            ->select('players.name as name', 'players.steal_success', 'players.team_id')
            ->with('team')
            ->join('teams', 'teams.id', '=', 'players.team_id')
            ->orderBy('players.steal_success', 'DESC')
            ->orderBy('players.id', 'ASC')
            ->limit(10)
            ->get();

        $eraPlayer = $playerModel->where('teams.season_id', $this->id)
            ->where(\DB::raw('(players.p_inning >= teams.game * 3)'), true)
            ->select('players.name as name', 'players.p_inning', 'players.p_era', 'players.team_id')
            ->with('team')
            ->join('teams', 'teams.id', '=', 'players.team_id')
            ->orderBy('players.p_era', 'ASC')
            ->orderBy('players.id', 'ASC')
            ->limit(10)
            ->get();


        $winPlayer = $playerModel->where('teams.season_id', $this->id)
            ->where('players.p_win', '>' , 0)
            ->select('players.name as name', 'players.p_win', 'players.team_id')
            ->with('team')
            ->join('teams', 'teams.id', '=', 'players.team_id')
            ->orderBy('players.p_win', 'DESC')
            ->orderBy('players.id', 'ASC')
            ->limit(10)
            ->get();

        $holdPlayer = $playerModel->where('teams.season_id', $this->id)
            ->where('players.p_hold', '>' , 0)
            ->select('players.name as name', 'players.p_hold', 'players.team_id')
            ->with('team')
            ->join('teams', 'teams.id', '=', 'players.team_id')
            ->orderBy('players.p_hold', 'DESC')
            ->orderBy('players.id', 'ASC')
            ->limit(10)
            ->get();


        $savePlayer = $playerModel->where('teams.season_id', $this->id)
            ->where('players.p_save', '>' , 0)
            ->select('players.name as name', 'players.p_save', 'players.team_id')
            ->with('team')
            ->join('teams', 'teams.id', '=', 'players.team_id')
            ->orderBy('players.p_save', 'DESC')
            ->orderBy('players.id', 'ASC')
            ->limit(10)
            ->get();

        return [
            'avg' => $avgPlayer,
            'hr' => $hrPlayer,
            'daten' => $datenPlayer,
            'steal' => $stealPlayer,
            'era' => $eraPlayer,
            'win' => $winPlayer,
            'hold' => $holdPlayer,
            'save' => $savePlayer,
        ];

    }

    public function getTitle(): array
    {
        $playerModel = new Player();
        if ($this->mvp_player) {
            $this->mvp_player->team;
        }
        if ($this->b9_1_player) {
            $this->b9_1_player->team;
        }
        if ($this->b9_2_player) {
            $this->b9_2_player->team;
        }
        if ($this->b9_3_player) {
            $this->b9_3_player->team;
        }
        if ($this->b9_4_player) {
            $this->b9_4_player->team;
        }
        if ($this->b9_5_player) {
            $this->b9_5_player->team;
        }
        if ($this->b9_6_player) {
            $this->b9_6_player->team;
        }
        if ($this->b9_7_player) {
            $this->b9_7_player->team;
        }
        if ($this->b9_8_player) {
            $this->b9_8_player->team;
        }
        if ($this->b9_9_player) {
            $this->b9_9_player->team;
        }
        return [
            'avg' => $playerModel->getSimpleTopPlayer($this->id, 'avg', 3, \DB::raw('(players.daseki::numeric >= teams.game::numeric * 3.1)'), 'max'),
            'hr' => $playerModel->getSimpleTopPlayer($this->id, 'hr'),
            'daten' => $playerModel->getSimpleTopPlayer($this->id, 'daten'),
            'steal' => $playerModel->getSimpleTopPlayer($this->id, 'steal_success'),
            'hit' => $playerModel->getSimpleTopPlayer($this->id, 'hit'),
            'p_era' => $playerModel->getSimpleTopPlayer($this->id, 'p_era', 2, \DB::raw('(players.p_inning >= teams.game * 3)'), 'min'),
            'p_win' => $playerModel->getSimpleTopPlayer($this->id, 'p_win'),
            'p_win_ratio' => $playerModel->getSimpleTopPlayer($this->id, 'p_win_ratio', 3, \DB::raw('(players.p_win >= 13::numeric)')),
            'p_sansin' => $playerModel->getSimpleTopPlayer($this->id, 'p_sansin'),
            'p_hold' => $playerModel->getSimpleTopPlayer($this->id, 'p_hold'),
            'p_save' => $playerModel->getSimpleTopPlayer($this->id, 'p_save'),
            'mvp' => $this->mvp_player,
            'bb_1' => $this->b9_1_player,
            'bb_2' => $this->b9_2_player,
            'bb_3' => $this->b9_3_player,
            'bb_4' => $this->b9_4_player,
            'bb_5' => $this->b9_5_player,
            'bb_6' => $this->b9_6_player,
            'bb_7' => $this->b9_7_player,
            'bb_8' => $this->b9_8_player,
            'bb_9' => $this->b9_9_player,
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
