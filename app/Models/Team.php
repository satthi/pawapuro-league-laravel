<?php

namespace App\Models;

use App\Enums\PlayType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $fillable = [
        'season_id',
        'base_team_id',
        'name',
        'ryaku_name',
        'game',
        'remain',
        'win',
        'lose',
        'draw',
        'win_ratio',
        'hr',
        'avg',
        'era',
        'point',
        'p_point',
    ];

    protected $appends = [
        'display_win_ratio',
        'display_avg',
        'display_era',
    ];
    /**
     * home team
     */
    public function base_team()
    {
        return $this->belongsTo(BaseTeam::class, 'base_team_id');
    }

    /**
     * home team
     */
    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id');
    }

    ## attribute
    public function getDisplayWinRatioAttribute($value)
    {
        if ($this->win + $this->lose == 0) {
            return '-';
        }

        return preg_replace('/^0/', '', sprintf('%.3f', round($this->win_ratio, 3)));
    }
    public function getDisplayAvgAttribute($value)
    {
        if ($this->game == 0) {
            return '-';
        }

        return preg_replace('/^0/', '', sprintf('%.3f', round($this->avg, 3)));
    }
    public function getDisplayEraAttribute($value)
    {
        if ($this->game == 0) {
            return '-';
        }

        return sprintf('%.2f', round($this->era, 2));
    }

    public function shukei($seasonId)
    {
        $playerModel = new Player();
        $teams = Team::where('season_id', $seasonId)
            ->get();

        $teamShukei = [];
        foreach ($teams as $team) {
            $teamShukei[$team->id] = [
                'game' => 0,
                'remain' => 0,
                'win' => 0,
                'lose' => 0,
                'draw' => 0,
                'point' => 0,
                'p_point' => 0,
                'team' => $team
            ];
        }

        $games = Game::where('season_id', $seasonId)
            ->get();

        foreach ($games as $game) {

            // 試合終了処理
            if ($game->inning == 999) {
                $teamShukei[$game->home_team_id]['game']++;
                $teamShukei[$game->visitor_team_id]['game']++;

                $teamShukei[$game->home_team_id]['point'] += $game->home_point;
                $teamShukei[$game->visitor_team_id]['point'] += $game->visitor_point;

                $teamShukei[$game->home_team_id]['p_point'] += $game->visitor_point;
                $teamShukei[$game->visitor_team_id]['p_point'] += $game->home_point;

                if ($game->home_point > $game->visitor_point) {
                    $teamShukei[$game->home_team_id]['win']++;
                    $teamShukei[$game->visitor_team_id]['lose']++;
                } else if ($game->home_point < $game->visitor_point) {
                    $teamShukei[$game->home_team_id]['lose']++;
                    $teamShukei[$game->visitor_team_id]['win']++;
                } else {
                    $teamShukei[$game->home_team_id]['draw']++;
                    $teamShukei[$game->visitor_team_id]['draw']++;
                }
            } else {
                $teamShukei[$game->home_team_id]['remain']++;
                $teamShukei[$game->visitor_team_id]['remain']++;
            }
        }

        // 打撃成績の集計
        $dagekiShukei = Play::join('games', 'games.id', '=', 'plays.game_id')
            ->leftjoin('results', 'results.id', '=', 'plays.result_id')
            ->where('type', PlayType::TYPE_DAGEKI_KEKKA)
            ->where('games.season_id', $seasonId)
            ->select([
                'plays.team_id as team_id',
                $playerModel->fielderSeisekiSelectParts('dasu_count_flag', 'dasu'),
                $playerModel->fielderSeisekiSelectParts('hit_flag', 'hit'),
                $playerModel->fielderSeisekiSelectParts('hr_flag', 'hr'),
            ])
            ->groupBy('plays.team_id')
            ->get();

        foreach ($dagekiShukei as $dageki) {
            $teamShukei[$dageki->team_id]['hr'] = $dageki->hr;
            $teamShukei[$dageki->team_id]['avg'] = 0;
            if ($dageki->dasu) {
                $teamShukei[$dageki->team_id]['avg'] = $dageki->hit / $dageki->dasu;
            }
        }

        // 投手成績の集計
        $pitcherShukei = GamePitcher::join('games', 'games.id', '=', 'game_pitchers.game_id')
            ->where('games.season_id', $seasonId)
            ->select([
                'game_pitchers.team_id as team_id',
                \DB::raw('sum(game_pitchers.inning) as inning'),
                \DB::raw('sum(game_pitchers.jiseki) as jiseki'),
            ])
            ->groupBy('game_pitchers.team_id')
            ->get();

        foreach ($pitcherShukei as $pitcher) {
            $teamShukei[$pitcher->team_id]['era'] = 0;
            if ($pitcher->inning) {
                $teamShukei[$pitcher->team_id]['era'] = $pitcher->jiseki / $pitcher->inning * 27;
            }
        }

        foreach ($teamShukei as $k => $teamInfo) {
            $team = $teamInfo['team'];
            unset($teamInfo['team']);
            $teamInfo['win_ratio'] = 0;

            if ($teamInfo['win'] + $teamInfo['lose'] > 0) {
                $teamInfo['win_ratio'] = $teamInfo['win'] / ($teamInfo['win'] + $teamInfo['lose']);
            }

            $team->update($teamInfo);
        }
    }

    public function getSeasonTeam(Season $season)
    {
        $teams = $this::where('season_id', $season->id)
            ->orderBy('win_ratio', 'DESC')
            ->orderBy('id', 'ASC')
            ->get()
            ->toArray();

        $rank = 0;
        $beforeRank = null;
        $beforeWinRatio = null;
        $beforeChokin = null;
        foreach ($teams as $teamKey => $team) {
            $rank++;
            if ($beforeWinRatio !== $team['win_ratio']) {
                $teams[$teamKey]['rank'] = $rank;
                $beforeRank = $rank;
            } else {
                $teams[$teamKey]['rank'] = $beforeRank;
            }
            $beforeWinRatio = $team['win_ratio'];

            if (is_null($beforeChokin)) {
                $teams[$teamKey]['diff'] = '-';
                $beforeChokin = $team['win'] - $team['lose'];
            } else {
                $teams[$teamKey]['diff'] = sprintf('%.1f', ($beforeChokin - ($team['win'] - $team['lose'])) / 2);
                $beforeChokin = $team['win'] - $team['lose'];
            }
        }

        // 終了してないゲームを抽出
        $remainGames = Game::where('season_id', $season->id)
            ->where(function($q) {
                $q->where('inning', '<>', 999)
                    ->orWhere('inning', null);
            })
            ->get();

        $remainGameVs = [];
        foreach ($teams as $team) {
            foreach ($teams as $team2) {
                $remainGameVs[$team['id']][$team2['id']] = 0;
            }
        }
        foreach ($remainGames as $remainGame) {
            $remainGameVs[$remainGame->home_team_id][$remainGame->visitor_team_id]++;
            $remainGameVs[$remainGame->visitor_team_id][$remainGame->home_team_id]++;
        }

        // 優勝可能確認
        foreach ($teams as $teamKey => $team) {
            $yushoKano = true;
            $yushoKakutei = true;
            $jirikiYushoKano = true;
            $magicNumber = 0;

            $allWinRatio = ($team['win'] + $team['remain']) / ($team['win'] + $team['lose'] + $team['remain']);
            $allLoseRatio = ($team['win']) / ($team['win'] + $team['lose'] + $team['remain']);
            $remainRatio = [];
            // 0勝～残りの勝率(140まで入れてるのは先の情報まで見るようにするため)
            for ($i = 0; $i <= 140; $i++) {
                $remainRatio[$i] = ($team['win'] + $i) / ($team['win'] + $team['lose'] + $team['remain']);
            }
            // dump($remainRatio);
            // exit;
            foreach ($teams as $checkTeamKey => $checkTeam) {
                if ($team['id'] == $checkTeam['id']) {
                    continue;
                }
                $checkAllLoseRatio =  ($checkTeam['win']) / ($checkTeam['win'] + $checkTeam['lose'] + $checkTeam['remain']);
                $checkAllWinRatio =  ($checkTeam['win'] + $checkTeam['remain']) / ($checkTeam['win'] + $checkTeam['lose'] + $checkTeam['remain']);
                
                // 自力優勝可能かどうかは直接対決以外全部負け
                $checkAllWinRatioChokusetsuLose = ($checkTeam['win'] + $checkTeam['remain'] - $remainGameVs[$team['id']][$checkTeam['id']]) / ($checkTeam['win'] + $checkTeam['lose'] + $checkTeam['remain']);
                if ($allWinRatio < $checkAllLoseRatio) {
                    $yushoKano = false;
                }
                if ($allLoseRatio <= $checkAllWinRatio) {
                    $yushoKakutei = false;
                }
                if ($allWinRatio < $checkAllWinRatioChokusetsuLose) {
                    $jirikiYushoKano = false;
                }
                // マジックのチェック
                foreach ($remainRatio as $magicNumberCheckWin => $checkRemainRatio) {
                    if ($checkRemainRatio > $checkAllWinRatio) {
                        if ($magicNumber < $magicNumberCheckWin) {
                            $magicNumber = $magicNumberCheckWin;
                        }
                        break;
                    }
                }
            }

            $teams[$teamKey]['yusho_kano'] = $yushoKano;
            $teams[$teamKey]['yusho_kakutei'] = $yushoKakutei;
            $teams[$teamKey]['jiriki_yusho_kano'] = $jirikiYushoKano;
            $teams[$teamKey]['magic_number'] = $magicNumber;
        }

        return $teams;
    }
}
