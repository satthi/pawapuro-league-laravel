<?php

namespace App\Http\Controllers;

use App\Models\Season;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    public function getOptions(Season $season)
    {
        $baseTeamModel = new Team();
        return $baseTeamModel
            ->select('id as value', 'name as text')
            ->where('season_id', $season->id)
            ->get();
    }
}
