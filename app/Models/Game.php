<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Game extends Model
{
    use HasFactory;
    protected $fillable = [
        'season_id',
        'date',
        'home_team_id',
        'visitor_team_id',
        'dh_flag',
    ];


    /**
     * home team
     */
    public function home_team()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }
    /**
     * home team
     */
    public function visitor_team()
    {
        return $this->belongsTo(Team::class, 'visitor_team_id');
    }

    public function getIndexList(int $seasonId)
    {
        $games = $this::where('season_id', $seasonId)
            ->with('home_team')
            ->with('visitor_team')
            ->orderBy('date', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();
        // チーム数チェック
        $gameCount = ceil(Team::where('season_id', $seasonId)->count() / 2);

        // これベースに整理
        $nowDate = null;
        $gameLists = [];
        foreach ($games as $game) {
            $carbonDate = new Carbon($game->date);
            if (is_null($nowDate)) {
                $gameLists = $this->initialGame($gameLists, $carbonDate);
                $nowDate = $carbonDate;
            }

            if (!array_key_exists($carbonDate->format('Y-m-d'), $gameLists)) {
                // nowDateまで空枠を進める
                while(true) {
                    $nowDate = $nowDate->addDay();
                    $gameLists = $this->initialGame($gameLists, $nowDate);
                    if ($carbonDate == $nowDate) {
                        break;
                    }
                }
            }

            $gameLists[$carbonDate->format('Y-m-d')]['game'][] = $game->toArray();
        }

        foreach ($gameLists as $gameDate => $gameList) {
            for ($i = 0; $i < $gameCount - count($gameList['game']); $i++) {
                $gameLists[$gameDate]['game'][] = [];
            }
        }

        return $gameLists;
    }

    private function initialGame($gameLists, $carbonDate)
    {
        $gameLists[$carbonDate->format('Y-m-d')] = [
            'date' => $carbonDate->format('Y/m/d (D)'),
            'game' => []
        ];

        return $gameLists;
    }
}
