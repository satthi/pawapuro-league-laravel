<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamePitcher extends Model
{
    use HasFactory;
    protected $fillable = [
        'game_id',
        'team_id',
        'player_id',
        'win_flag',
        'lose_flag',
        'hold_flag',
        'save_flag',
        'jiseki',
        'inning',
        'daseki',
        'dasu',
        'hit',
        'hr',
        'sansin',
        'walk',
        'dead',
    ];
    protected $appends = [
        'string_inning',
    ];


    /**
     * player
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    /**
     * player
     */
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }
    /**
     * 利き(投げ) テキスト表示
     *
     * @param  string  $value
     * @return string
     */
    public function getStringInningAttribute($value)
    {
        $string = (int)(floor($this->inning / 3));
        if ($this->inning % 3 != 0) {
            $string .= ' ' . ($this->inning % 3) . '/3';
        }

        return $string;
    }

    public function getPitcherInfo($gameId, $teamId)
    {
        $gameInfo = Game::find($gameId);

        $pitcherInfo = $this::where('game_id', $gameId)
            ->where('team_id', $teamId)
            ->with('player')
            ->orderBy('id', 'ASC')
            ->get()
            ->toArray();
        // 成績の設定
        foreach ($pitcherInfo as $pitcherInfoKey => $pitcherInfoVal) {
            $pitcherInfo[$pitcherInfoKey]['seiseki'] = $this->getSeiseki($pitcherInfoVal['player_id'], $gameInfo->date);
        }

        return $pitcherInfo;
    }

    public function getSeiseki($playerId, $gameDate)
    {
        $seiseki = $this::where('player_id', $playerId)
            ->join('games', 'games.id', '=', 'game_pitchers.game_id')
            ->where('games.date' , '<=', $gameDate)
            ->select([
                \DB::raw('count(game_pitchers.id) as game_sum'),
                \DB::raw('coalesce(sum(game_pitchers.inning), 0) as inning_sum'),
                \DB::raw('coalesce(sum(game_pitchers.jiseki), 0) as jiseki_sum'),
                \DB::raw('coalesce(sum(game_pitchers.daseki), 0) as daseki_sum'),
                \DB::raw('coalesce(sum(game_pitchers.dasu), 0) as dasu_sum'),
                \DB::raw('coalesce(sum(game_pitchers.hit), 0) as hit_sum'),
                \DB::raw('coalesce(sum(game_pitchers.hr), 0) as hr_sum'),
                \DB::raw('coalesce(sum(game_pitchers.sansin), 0) as sansin_sum'),
                \DB::raw('coalesce(sum(game_pitchers.walk), 0) as walk_sum'),
                \DB::raw('coalesce(sum(game_pitchers.dead), 0) as dead_sum'),
                \DB::raw('coalesce(sum(game_pitchers.win_flag::integer), 0) as win_count'),
                \DB::raw('coalesce(sum(game_pitchers.lose_flag::integer), 0) as lose_count'),
                \DB::raw('coalesce(sum(game_pitchers.hold_flag::integer), 0) as hold_count'),
                \DB::raw('coalesce(sum(game_pitchers.save_flag::integer), 0) as save_count'),
            ])
            ->first()
            ->toArray();
        if (empty($seiseki['inning_sum'])) {
            $seiseki['era'] = '-';
        } else {
            $seiseki['era'] = sprintf('%.2f', round($seiseki['jiseki_sum'] / $seiseki['inning_sum'] * 27, 2));
        }

        return $seiseki;
    }

    public function getSeisekiAll($playerIds, $gameDate)
    {
        $seisekis = $this::whereIn('player_id', $playerIds)
            ->join('games', 'games.id', '=', 'game_pitchers.game_id')
            ->where('games.date' , '<=', $gameDate)
            ->select([
                'player_id',
                \DB::raw('count(game_pitchers.id) as game_sum'),
                \DB::raw('coalesce(sum(game_pitchers.inning), 0) as inning_sum'),
                \DB::raw('coalesce(sum(game_pitchers.jiseki), 0) as jiseki_sum'),
                \DB::raw('coalesce(sum(game_pitchers.daseki), 0) as daseki_sum'),
                \DB::raw('coalesce(sum(game_pitchers.dasu), 0) as dasu_sum'),
                \DB::raw('coalesce(sum(game_pitchers.hit), 0) as hit_sum'),
                \DB::raw('coalesce(sum(game_pitchers.hr), 0) as hr_sum'),
                \DB::raw('coalesce(sum(game_pitchers.sansin), 0) as sansin_sum'),
                \DB::raw('coalesce(sum(game_pitchers.walk), 0) as walk_sum'),
                \DB::raw('coalesce(sum(game_pitchers.dead), 0) as dead_sum'),
                \DB::raw('coalesce(sum(game_pitchers.win_flag::integer), 0) as win_count'),
                \DB::raw('coalesce(sum(game_pitchers.lose_flag::integer), 0) as lose_count'),
                \DB::raw('coalesce(sum(game_pitchers.hold_flag::integer), 0) as hold_count'),
                \DB::raw('coalesce(sum(game_pitchers.save_flag::integer), 0) as save_count'),
            ])
            ->groupBy('player_id')
            ->get()
            ->keyBy('player_id')
            ->toArray();

        foreach ($playerIds as $playerId) {
            if (array_key_exists($playerId, $seisekis)) {
                if (empty($seisekis[$playerId]['inning_sum'])) {
                    $seisekis[$playerId]['era'] = '-';
                } else {
                    $seisekis[$playerId]['era'] = sprintf('%.2f', round($seisekis[$playerId]['jiseki_sum'] / $seisekis[$playerId]['inning_sum'] * 27, 2));
                }
            } else {
                $seisekis[$playerId] = [
                    'game_sum' => 0,
                    'inning_sum' => 0,
                    'jiseki_sum' => 0,
                    'daseki_sum' => 0,
                    'dasu_sum' => 0,
                    'hit_sum' => 0,
                    'hr_sum' => 0,
                    'sansin_sum' => 0,
                    'walk_sum' => 0,
                    'dead_sum' => 0,
                    'win_count' => 0,
                    'lose_count' => 0,
                    'hold_count' => 0,
                    'save_count' => 0,
                    'era' => '-',
                ];
            }
        }

        return $seisekis;
    }

    public function topPitcherSeisekiInfo($game)
    {
        // ピッチャー情報を取得して勝ち投手/負け投手/セーブ投手を取得

        return [
            'winPitcher' => $this->topPitcherSeisekiInfoCheck($game, 'win_flag'),
            'losePitcher' => $this->topPitcherSeisekiInfoCheck($game, 'lose_flag'),
            'savePitcher' => $this->topPitcherSeisekiInfoCheck($game, 'save_flag'),
        ];
        
    }

    private function topPitcherSeisekiInfoCheck($game, $field)
    {
        $pitcher = GamePitcher::where('game_id', $game->id)
            ->where($field, true)
            ->with('player')
            ->first();

        if (is_null($pitcher)) {
            return null;
        }

        $pitcher = $pitcher->toArray();

        // 成績情報(試合/勝ち/負け/ホールド/セーブ)
        $seiseki = $this::where('player_id', $pitcher['player_id'])
            ->join('games', 'games.id', '=', 'game_pitchers.game_id')
            ->where('games.date', '<=', $game->date)
            ->select([
                \DB::raw('count(game_pitchers.id) as game_sum'),
                \DB::raw('sum(game_pitchers.win_flag::integer) as win_count'),
                \DB::raw('sum(game_pitchers.lose_flag::integer) as lose_count'),
                \DB::raw('sum(game_pitchers.hold_flag::integer) as hold_count'),
                \DB::raw('sum(game_pitchers.save_flag::integer) as save_count'),
            ])
            ->first();
        // これnullはない
        $seisekiText = $seiseki->game_sum . '試合';
        if ($seiseki->win_count > 0) {
            $seisekiText .= ' ' . $seiseki->win_count . '勝';
        }
        if ($seiseki->lose_count > 0) {
            $seisekiText .= ' ' . $seiseki->lose_count . '敗';
        }
        if ($seiseki->hold_count > 0) {
            $seisekiText .= ' ' . $seiseki->hold_count . 'H';
        }
        if ($seiseki->save_count > 0) {
            $seisekiText .= ' ' . $seiseki->save_count . 'S';
        }

        $pitcher['seiseki_text'] = $seisekiText;

        return $pitcher;
    }

    public function getPitcherHistory(Player $player)
    {
        $gamePitchers =  $this::where('player_id', $player->id)
            ->select('game_pitchers.*')
            ->with([
                'game.home_team',
                'game.visitor_team',
            ])
            ->join('games', 'games.id', '=', 'game_pitchers.game_id')
            ->orderBy('games.date', 'ASC')
            ->orderBy('games.id', 'ASC')
            ->get();
        // dump($gamePitchers->toArray());
        // exit;

        $gamePitcherHistories = [];
        $totalInning = 0;
        $totalJiseki = 0;
        foreach ($gamePitchers as $gamePitcher) {
            $gamePitcherHistory = [];
            $gamePitcherHistory['game_id'] = $gamePitcher->game_id;
            $gamePitcherHistory['date'] = $gamePitcher->game->date;
            if ($gamePitcher->game->home_team_id != $player->team_id) {
                $gamePitcherHistory['vs'] = $gamePitcher->game->home_team->ryaku_name;
            } else {
                $gamePitcherHistory['vs'] = $gamePitcher->game->visitor_team->ryaku_name;
            }
            $gamePitcherHistory['type'] = '';
            if ($gamePitcher->win_flag) {
                $gamePitcherHistory['type'] = '○';
            }
            if ($gamePitcher->lose_flag) {
                $gamePitcherHistory['type'] = '●';
            }
            if ($gamePitcher->hold_flag) {
                $gamePitcherHistory['type'] = 'H';
            }
            if ($gamePitcher->save_flag) {
                $gamePitcherHistory['type'] = 'S';
            }
            $gamePitcherHistory['inning'] = $gamePitcher->string_inning;
            $gamePitcherHistory['hit'] = $gamePitcher->hit;
            $gamePitcherHistory['hr'] = $gamePitcher->hr;
            $gamePitcherHistory['jiseki'] = $gamePitcher->jiseki;
            $gamePitcherHistory['walk'] = $gamePitcher->walk;
            $gamePitcherHistory['dead'] = $gamePitcher->dead;
            $gamePitcherHistory['sansin'] = $gamePitcher->sansin;

            $totalInning += $gamePitcher->inning;
            $totalJiseki += $gamePitcher->jiseki;
            if ($totalInning == 0) {
                $gamePitcherHistory['era'] = '-';
            } else {
                $gamePitcherHistory['era'] = sprintf('%.2f', round($totalJiseki / $totalInning * 27, 2));
            }

            $gamePitcherHistories[] = $gamePitcherHistory;
        }

        // dump($gamePitcherHistories);
        // exit;
        return $gamePitcherHistories;

    }

    public function getMothryPitcher(Player $player)
    {
        $monthlyInfos = $this::where('player_id', $player->id)
            ->join('games', 'games.id', '=', 'game_pitchers.game_id')
            ->select([
                \DB::raw('to_char(date,\'YYYY-MM\') as month'),
                \DB::raw('count(game_pitchers.id) as p_game'),
                \DB::raw('coalesce(sum(game_pitchers.inning), 0) as inning_sum'),
                \DB::raw('coalesce(sum(game_pitchers.jiseki), 0) as p_jiseki'),
                \DB::raw('coalesce(sum(game_pitchers.daseki), 0) as daseki_sum'),
                \DB::raw('coalesce(sum(game_pitchers.dasu), 0) as dasu_sum'),
                \DB::raw('coalesce(sum(game_pitchers.hit), 0) as p_hit'),
                \DB::raw('coalesce(sum(game_pitchers.hr), 0) as p_hr'),
                \DB::raw('coalesce(sum(game_pitchers.sansin), 0) as p_sansin'),
                \DB::raw('coalesce(sum(game_pitchers.walk), 0) as p_walk'),
                \DB::raw('coalesce(sum(game_pitchers.dead), 0) as p_dead'),
                \DB::raw('coalesce(sum(game_pitchers.win_flag::integer), 0) as p_win'),
                \DB::raw('coalesce(sum(game_pitchers.lose_flag::integer), 0) as p_lose'),
                \DB::raw('coalesce(sum(game_pitchers.hold_flag::integer), 0) as p_hold'),
                \DB::raw('coalesce(sum(game_pitchers.save_flag::integer), 0) as p_save'),
            ])
            ->groupBy(\DB::raw('to_char(date,\'YYYY-MM\')'))
            ->orderBy(\DB::raw('to_char(date,\'YYYY-MM\')'), 'ASC')
            ->get();

        foreach ($monthlyInfos as $monthlyInfo) {
            $monthlyInfo->append('display_p_era');
            $monthlyInfo->append('display_p_win_ratio');
            $monthlyInfo->append('display_p_sansin_ratio');
            $monthlyInfo->append('display_p_avg');
            $monthlyInfo->append('display_p_inning');
        }

        // dump($monthlyInfos);
        // exit;

        return $monthlyInfos;
    }

    public function getDisplayPEraAttribute($value)
    {
        $bunbo = $this->inning_sum / 27;
        $bunshi = $this->p_jiseki;

        if ($bunbo) {
            return sprintf("%.2f", round($bunshi / $bunbo, 2));
        } else {
            return '-';
        }
    }

    public function getDisplayPWinRatioAttribute($value)
    {
        $bunbo = $this->p_win + $this->p_lose;
        $bunshi = $this->p_win;

        if ($bunbo) {
            return sprintf("%.3f", round($bunshi / $bunbo, 3));
        } else {
            return '-';
        }
    }

    public function getDisplayPSansinRatioAttribute($value)
    {
        $bunbo = $this->inning_sum / 27;
        $bunshi = $this->p_sansin;

        if ($bunbo) {
            return sprintf("%.2f", round($bunshi / $bunbo, 2));
        } else {
            return '-';
        }
    }

    public function getDisplayPAvgAttribute($value)
    {
        $bunbo = $this->dasu_sum;
        $bunshi = $this->p_hit;

        if ($bunbo) {
            return sprintf("%.3f", round($bunshi / $bunbo, 3));
        } else {
            return '-';
        }
    }

    public function getDisplayPInningAttribute($value)
    {
        $text = floor($this->inning_sum / 3);
        if ($this->inning_sum % 3 != 0) {
            $text .= ' ' . ($this->inning_sum % 3) . '/3';
        }

        return $text;
    }


}
