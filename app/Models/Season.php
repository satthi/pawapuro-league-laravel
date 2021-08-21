<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'start_date',
        'regular_flag',
        'game_count',
    ];
    protected $appends = [
        'is_deletable',
    ];

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
