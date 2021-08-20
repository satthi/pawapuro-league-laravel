<?php

namespace App\Http\Controllers;

use App\Models\BaseTeam;
use App\Http\Requests\BaseTeamRequest;
use Illuminate\Support\Facades\Validator;

class BaseTeamController extends Controller
{
     public function index()
     {
         return BaseTeam::all();
     }

    public function show(BaseTeam $baseTeam)
    {
        return $baseTeam;
    }
    public function add(BaseTeamRequest $request)
    {
        BaseTeam::create($request->all());
    }

    public function update(BaseTeamRequest $request, BaseTeam $baseTeam)
    {
        $baseTeam->update($request->all());
    }

    public function destroy(BaseTeam $baseTeam)
    {
        $baseTeam->delete();

        return $baseTeam;
    }
}
