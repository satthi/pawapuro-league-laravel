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
     * 利き(投げ) テキスト表示
     *
     * @param  string  $value
     * @return string
     */
    public function getStringInningAttribute($value)
    {
        $string = floor($this->inning / 3);
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
            $seiseki = $this::where('player_id', $pitcherInfoVal['player_id'])
                ->join('games', 'games.id', '=', 'game_pitchers.game_id')
                ->where('games.date' , '<=', $gameInfo->date)
                ->select([
                    \DB::raw('sum(game_pitchers.inning) as inning_sum'),
                    \DB::raw('sum(game_pitchers.jiseki) as jiseki_sum'),
                    \DB::raw('sum(game_pitchers.daseki) as daseki_sum'),
                    \DB::raw('sum(game_pitchers.dasu) as dasu_sum'),
                    \DB::raw('sum(game_pitchers.hit) as hit_sum'),
                    \DB::raw('sum(game_pitchers.hr) as hr_sum'),
                    \DB::raw('sum(game_pitchers.sansin) as sansin_sum'),
                    \DB::raw('sum(game_pitchers.walk) as walk_sum'),
                    \DB::raw('sum(game_pitchers.dead) as dead_sum'),
                    \DB::raw('sum(game_pitchers.win_flag::integer) as win_count'),
                    \DB::raw('sum(game_pitchers.lose_flag::integer) as lose_count'),
                    \DB::raw('sum(game_pitchers.hold_flag::integer) as hold_count'),
                    \DB::raw('sum(game_pitchers.save_flag::integer) as save_count'),
                ])
                ->first()
                ->toArray();
            if (empty($seiseki['inning_sum'])) {
                $seiseki['era'] = '-';
            } else {
                $seiseki['era'] = sprintf('%.2f', round($seiseki['jiseki_sum'] / $seiseki['inning_sum'] * 27, 2));
            }

            $pitcherInfo[$pitcherInfoKey]['seiseki'] = $seiseki;
        }

        return $pitcherInfo;
    }


}
