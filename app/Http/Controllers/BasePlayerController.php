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
         return BasePlayer::where('base_team_id', $baseTeam->id)->get();
     }

    public function show(BasePlayer $basePlayer)
    {
        return $basePlayer;
    }
    public function add(BasePlayerRequest $request, BaseTeam $baseTeam)
    {
        $requestData = $request->all();
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
    public function fielderRank(string $sortType)
    {
        return (new BasePlayer())->getRank($sortType);
    }
    public function pitcherRank(string $sortType)
    {
        return (new BasePlayer())->getRank($sortType);
    }

    public function titleFielderRank(string $sortType)
    {
        return (new BasePlayer())->getRank($sortType, true);
    }
    public function titlePitcherRank(string $sortType)
    {
        return (new BasePlayer())->getRank($sortType, true);
    }

}
