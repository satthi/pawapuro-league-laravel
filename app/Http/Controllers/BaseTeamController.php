<?php

namespace App\Http\Controllers;

use App\Models\BaseTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BaseTeamController extends Controller
{
     public function index()
     {
        \Log::debug(BaseTeam::all());
         return BaseTeam::all();
     }

    public function show(BaseTeam $baseTeam)
    {
        return $baseTeam;
    }
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'ryaku_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['save' => false, 'errorMessages' => $validator->messages()]);
        } else {
            BaseTeam::create($request->all());
            return response()->json(['save' => true]);
        }

        return BaseTeam::create($request->all());
    }

    public function update(Request $request, BaseTeam $baseTeam)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'ryaku_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['save' => false, 'errorMessages' => $validator->messages()]);
        } else {
            $baseTeam->update($request->all());
            return response()->json(['save' => true]);
        }
    }

    public function destroy(BaseTeam $baseTeam)
    {
        $baseTeam->delete();

        return $baseTeam;
    }
}
