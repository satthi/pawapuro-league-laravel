<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeasonRequest;
use App\Models\Season;
use App\Models\Team;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class SeasonController extends Controller
{
     public function index()
     {
         return Season::all();
     }

     public function regularList()
     {
         return Season::where('regular_flag', true)
            ->orderBy('seasons.id', 'ASC')
            ->get();
     }

    public function show(Season $season)
    {
        return $season;
    }

    public function nextGame(Season $season)
    {
        return $season->getNextGame();
    }

    public function add(SeasonRequest $request)
    {
        (new Season())->add($request->all());

        $season = Season::orderBy('id', 'DESC')
            ->firstOrFail();

        // 集計
        // baseの集計は不要
        $season->shukei(false);
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
        $gameModel = new Game();

        $monthList = $gameModel
            ->select(\DB::raw('to_char(date,\'YYYY-MM\') as month'))
            ->where('season_id', $season->id)
            ->where('inning', 999)
            ->groupBy(\DB::raw('to_char(date,\'YYYY-MM\')'))
            ->orderBy(\DB::raw('to_char(date,\'YYYY-MM\')'), 'ASC')
            ->get();


        return [
            'season' => $season,
            'teams' => $teamModel->getSeasonTeam($season),
            'ranking' => $season->getRanking(),
            'vs' => $gameModel->getVs($season),
            'monthList' => $monthList,
        ];
    }

    public function title(Season $season)
    {
        return $season->getTitle();
    }
    public function monthData(Season $season, string $month)
    {
        $startDate = new Carbon($month . '-01');
        $endDate = (clone $startDate)->endOfMonth();

        $gameModel = new Game();
        $monthInfo = $gameModel->where('season_id', $season->id)
            ->with('home_team')
            ->with('visitor_team')
            ->where('inning', 999)
            ->where('date', '>=' , $startDate)
            ->where('date', '<=' , $endDate)
            ->get();

        $initialData = [
            'rank' => 0,
            'name' => '',
            'game' => 0,
            'win' => 0,
            'lose' => 0,
            'draw' => 0,
            'win_ratio' => 0,
        ];
        $monthData = [];
        foreach ($monthInfo as $game) {
            if (empty($monthData[$game->home_team_id])) {
                $monthData[$game->home_team_id] = $initialData;
                $monthData[$game->home_team_id]['name'] = $game->home_team->name;
            }
            if (empty($monthData[$game->visitor_team_id])) {
                $monthData[$game->visitor_team_id] = $initialData;
                $monthData[$game->visitor_team_id]['name'] = $game->visitor_team->name;
            }

            $monthData[$game->home_team_id]['game']++;
            $monthData[$game->visitor_team_id]['game']++;

            if ($game->home_point > $game->visitor_point) {
                $monthData[$game->home_team_id]['win']++;
                $monthData[$game->visitor_team_id]['lose']++;
            } elseif ($game->home_point < $game->visitor_point) {
                $monthData[$game->home_team_id]['lose']++;
                $monthData[$game->visitor_team_id]['win']++;
            } else {
                $monthData[$game->home_team_id]['draw']++;
                $monthData[$game->visitor_team_id]['draw']++;
            }
        }

        foreach ($monthData as $teamId => $monthDataParts) {
            $monthData[$teamId]['win_ratio'] = ($monthDataParts['win'] + $monthDataParts['lose'] > 0) ? ($monthDataParts['win'] / ($monthDataParts['win'] + $monthDataParts['lose'])) : 0;
        }

        $sortMonthData = array_merge(collect($monthData)->sortBy('win_ratio', SORT_NUMERIC , true)->toArray());

        $nowRank = 0;
        $beforeRatio = -1;
        foreach ($sortMonthData as $sortMonthDataKey => $sortMonthDataVal) {
            if ($sortMonthDataVal['win_ratio'] != $beforeRatio) {
                $sortMonthData[$sortMonthDataKey]['rank'] = $sortMonthDataKey + 1;
            } else {
                $sortMonthData[$sortMonthDataKey]['rank'] = $nowRank;
            }
            $nowRank = $sortMonthData[$sortMonthDataKey]['rank'];
            $beforeRatio = $sortMonthData[$sortMonthDataKey]['win_ratio'];
        }

        return $sortMonthData;
    }

    public function graph(Season $season)
    {
        $teams = Team::where('season_id', $season->id)
            ->get();

        $games = Game::where('season_id', $season->id)
            ->where('inning', 999)
            ->orderBy('date', 'ASC')
            ->get();

        $graphInfo = [];
        $chokinInfo = [];
        foreach ($teams as $team) {
            $graphInfo[$team->id] = [];
            $chokinInfo[$team->id] = 0;
        }

        $minDate = null;
        $maxDate = null;
        foreach ($games as $game) {
            if (is_null($minDate)) {
                $minDate = $game->date;
            }
            if ($game->home_point > $game->visitor_point) {
                $chokinInfo[$game->home_team_id]++;
                $chokinInfo[$game->visitor_team_id]--;
            } else if ($game->home_point < $game->visitor_point) {
                $chokinInfo[$game->visitor_team_id]++;
                $chokinInfo[$game->home_team_id]--;
            }

            $graphInfo[$game->home_team_id][] = [
                'date' => $game->date,
                'chokin' => $chokinInfo[$game->home_team_id],
            ];
            $graphInfo[$game->visitor_team_id][] = [
                'date' => $game->date,
                'chokin' => $chokinInfo[$game->visitor_team_id],
            ];
        }
        $maxDate = $game->date;

        $returnArray = [];
        $returnArray['labels'] = [];
        $returnArray['datasets'] = [];
        foreach ($teams as $team) {
            $color = '';
            if ($team->ryaku_name == 'LB') {
                $color = '#FF0000';
            } elseif ($team->ryaku_name == 'TU') {
                $color = '#000000';
            } elseif ($team->ryaku_name == 'MF') {
                $color = '#008000';
            } elseif ($team->ryaku_name == 'TF') {
                $color = '#FFA500';
            } elseif ($team->ryaku_name == 'SN') {
                $color = '#0000FF';
            } elseif ($team->ryaku_name == 'FF') {
                $color = '#FFFF00';
            }
            $returnArray['datasets'][$team->id] = [
                'label' => $team->ryaku_name,
                'data' => [],
                'borderColor' => $color, // 後
                // 以下は固定
                'lineTension' =>  0,
                'fill' =>  false,
                'spanGaps' => true,
            ];
        }

        $targetDateChronos = new Carbon($minDate);
        while (true) {
            // label作り
            $returnArray['labels'][$targetDateChronos->format('Y-m-d')] = $targetDateChronos->format('Y-m-d');
            // とりあえず殻をセットする
            foreach ($teams as $team) {
                $returnArray['datasets'][$team->id]['data'][$targetDateChronos->format('Y-m-d')] = null;
            }
            if ($targetDateChronos->format('Y-m-d') == $maxDate) {
                break;
            }

            $targetDateChronos->addDay();

        }

        foreach ($graphInfo as $teamId => $teamInfo) {
            foreach ($teamInfo as $targetDateInfo) {
                $returnArray['datasets'][$teamId]['data'][$targetDateInfo['date']] = $targetDateInfo['chokin'];
            }
        }

        // ここで配列のkeyを降りなおしておく(jsonで変な順にならないように)
        $returnArray['labels'] = array_values($returnArray['labels']);
        $returnArray['datasets'] = array_values($returnArray['datasets']);
        foreach (array_keys($returnArray['datasets']) as $teamKey) {
            $returnArray['datasets'][$teamKey]['data'] = array_values($returnArray['datasets'][$teamKey]['data']);
        }
        // dump($graphInfo);
        // dump($returnArray);
        // exit;



        return $returnArray;
    }


    public function reShukei(Request $request, Season $season)
    {
        // 集計処理
        $season->shukei();
    }

    public function fielderRank(Season $season, string $sortType)
    {
        return (new Player())->getRank($season, $sortType);
    }
    public function pitcherRank(Season $season, string $sortType)
    {
        return (new Player())->getRank($season, $sortType);
    }


}
