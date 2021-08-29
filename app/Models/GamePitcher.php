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


    /**
     * player
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
