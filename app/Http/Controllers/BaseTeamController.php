<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseTeamRequest;
use App\Models\BaseTeam;
use Illuminate\Support\Facades\DB;
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

    public function getOptions()
    {
        $baseTeamModel = new BaseTeam();
        return $baseTeamModel->select('id as value', 'name as text')->get();
    }

     public function seiseki()
     {
         return (new BaseTeam())->seiseki();
     }
     public function rankHensen()
     {
         return (new BaseTeam())->rankHensen();
     }

}
