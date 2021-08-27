<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    protected $fillable = [
         'name',
         'hit_flag',
         'hit_2_flag',
         'hit_3_flag',
         'hr_flag',
         'sansin_flag',
         'heisatsu_flag',
         'walk_flag',
         'dead_flag',
         'bant_flag',
         'sac_fly_flag',
         'out_count',
         'point_require_flag',
         'dasu_count_flag',
         'button_position',
         'button_type',
    ];
}
