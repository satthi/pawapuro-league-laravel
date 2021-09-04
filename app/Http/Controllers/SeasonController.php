<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeasonRequest;
use App\Models\Season;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SeasonController extends Controller
{
     public function index()
     {
         return Season::all();
     }

    public function show(Season $season)
    {
        return $season;
    }
    public function add(SeasonRequest $request)
    {
        (new Season())->add($request->all());
    }

    public function update(SeasonRequest $request, Season $season)
    {
        $season->update($request->all());
    }

    public function destroy(Season $season)
    {
        $season->delete();

        return $season;
    }

    public function detail(Season $season)
    {
        $teamModel = new Team();
        return [
            'season' => $season,
            'teams' => $teamModel->getSeasonTeam($season),
        ];
    }
    public function reShukei(Request $request, Season $season)
    {
        // 集計処理
        $season->shukei();
    }
}
