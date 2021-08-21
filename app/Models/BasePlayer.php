<?php

namespace App\Models;

use App\Enums\Kiki;
use App\Enums\PlayerPosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasePlayer extends Model
{
    use HasFactory;
    protected $fillable = [
        'base_team_id',
        'number',
        'name',
        'name_short',
        'hand_p',
        'hand_b',
        'position_main',
        'position_sub1',
        'position_sub2',
        'position_sub3',
    ];

    protected $appends = [
        'hand_p_text',
        'hand_b_text',
        'hand_full_text',
        'position_main_text',
    ];

    /**
     * 利き(投げ) テキスト表示
     *
     * @param  string  $value
     * @return string
     */
    public function getHandPTextAttribute($value)
    {
        return Kiki::getDescription($this->hand_p);
    }

    /**
     * 利き(打ち) テキスト表示
     *
     * @param  string  $value
     * @return string
     */
    public function getHandBTextAttribute($value)
    {
        return Kiki::getDescription($this->hand_b);
    }

    /**
     * 利き(投げ打ち) テキスト表示
     *
     * @param  string  $value
     * @return string
     */
    public function getHandFullTextAttribute($value)
    {
        return $this->hand_p_text . '投' . $this->hand_b_text . '打';
    }

    /**
     * メインポジション テキスト表示
     *
     * @param  string  $value
     * @return string
     */
    public function getPositionMainTextAttribute($value)
    {
        return PlayerPosition::getDescription($this->position_main);
    }
}
