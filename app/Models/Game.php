<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $fillable = [
        'season_id',
        'date',
        'home_team_id',
        'visitor_team_id',
        'dh_flag',
    ];
}
