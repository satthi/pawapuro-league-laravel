<?php

namespace App\Models;

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
    ];
}
