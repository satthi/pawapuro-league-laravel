<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
     public function index()
     {
         return Team::all();
     }

    public function show(Team $team)
    {
        return $team;
    }
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'ryaku_name' => 'required',
        ]);
        \Log::debug($validator->messages());

        if ($validator->fails()) {
            return response()->json(['save' => false, 'errorMessages' => $validator->messages()]);
        } else {
            Team::create($request->all());
            return response()->json(['save' => true]);
        }

        return Team::create($request->all());
    }

    public function update(Request $request, Team $team)
    {
        $team->update($request->all());
        return $team;
    }

    public function destroy(Team $team)
    {
        $team->delete();

        return $team;
    }
}
