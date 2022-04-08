<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseTeam extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'ryaku_name',
    ];
    protected $appends = [
        'is_deletable',
    ];

    /**
     * ブログポストのコメントを取得
     */
    public function base_players()
    {
        return $this->hasMany(BasePlayer::class);
    }

    /**
     * 利き(投げ) テキスト表示
     *
     * @param  string  $value
     * @return string
     */
    public function getIsDeletableAttribute($value)
    {
        return !BasePlayer::where('base_team_id', $this->id)->exists();
    }

    public function seiseki()
    {
        return $this::join('teams', 'teams.base_team_id', '=', 'base_teams.id')
            ->join('seasons', 'teams.season_id', '=', 'seasons.id')
            ->where('seasons.regular_flag', true)
            ->select([
                'base_teams.id',
                'base_teams.name',
                'base_teams.ryaku_name',
                \DB::raw('sum(teams.game) as game'),
                \DB::raw('sum(teams.win) as win'),
                \DB::raw('sum(teams.lose) as lose'),
                \DB::raw('sum(teams.draw) as draw'),
                \DB::raw('sum(teams.hr) as hr'),
                \DB::raw('
                    (
                    select
                        sum(players.hit)::numeric / sum(players.dasu)::numeric
                    FROM
                        players
                    LEFT JOIN
                        teams
                    ON
                        players.team_id = teams.id
                    WHERE
                        teams.base_team_id = base_teams.id
                    )
                     as avg'),
                \DB::raw('
                    (
                    select
                        sum(players.p_jiseki)::numeric / sum(players.p_inning)::numeric * 27::numeric
                    FROM
                        players
                    LEFT JOIN
                        teams
                    ON
                        players.team_id = teams.id
                    WHERE
                        teams.base_team_id = base_teams.id
                    )
                     as era'),
            ])
            ->groupBy('base_teams.id')
            ->orderBy(\DB::raw('sum(teams.win)::numeric / (sum(teams.win)::numeric + sum(teams.lose)::numeric)'), 'DESC')
            ->get();
    }

    public function rankHensen()
    {
        $seasons = Season::where('regular_flag', true)
            ->orderBy('id', 'ASC')
            ->get();

        $rankData = [];
        foreach ($seasons as $season) {
            $rankTeams = (new Team())->getSeasonTeam($season);
            foreach ($rankTeams as $rankTeam) {
                $rankData[$season->id][$rankTeam['base_team_id']] = $rankTeam['rank'];
            }
        }

        return $rankData;
    }

}
