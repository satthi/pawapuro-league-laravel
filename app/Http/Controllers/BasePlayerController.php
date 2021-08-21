<?php

namespace App\Http\Controllers;

use App\Http\Requests\BasePlayerRequest;
use App\Models\BasePlayer;
use App\Models\BaseTeam;
use Illuminate\Support\Facades\Validator;

class BasePlayerController extends Controller
{
     public function index(BaseTeam $baseTeam)
     {
         return BasePlayer::all();
     }

    public function show(BasePlayer $basePlayer)
    {
        return $basePlayer;
    }
    public function add(BasePlayerRequest $request, BaseTeam $baseTeam)
    {
        $requestData = $request->all();
        \Log::debug($requestData);
        BasePlayer::create($request->all());
    }

    public function update(BasePlayerRequest $request, BasePlayer $basePlayer)
    {
        $basePlayer->update($request->all());
    }

    public function destroy(BasePlayer $basePlayer)
    {
        $basePlayer->delete();

        return $basePlayer;
    }
}
