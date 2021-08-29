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

}
