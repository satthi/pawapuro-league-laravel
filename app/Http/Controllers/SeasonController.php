<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeasonRequest;
use App\Models\Season;
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
        Season::create($request->all());
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
}
