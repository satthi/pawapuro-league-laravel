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

}
