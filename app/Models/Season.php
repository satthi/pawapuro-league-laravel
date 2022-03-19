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
