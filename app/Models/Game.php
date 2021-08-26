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
        'home_probable_pitcher_id',
        'visitor_probable_pitcher_id',
        'dh_flag',
        'inning',
        'out',
        'home_point',
        'visitor_point',
    ];

    ### relation

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
    /**
     * home team
     */
    public function home_probable_pitcher()
    {
        return $this->belongsTo(Player::class, 'home_probable_pitcher_id');
    }
    /**
     * home team
     */
    public function visitor_probable_pitcher()
    {
        return $this->belongsTo(Player::class, 'visitor_probable_pitcher_id');
    }

    ### index

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

    ### auto add

    public function autoAdd($requestData, $seasonId)
    {
        // 回数は調整
        $autoWaku = null;
        $checkPoint = null;
        for($i = 1;$i <= 20000;$i++) {
            $tempAutoWaku = $this->autoWakuMake();
            if (empty($tempAutoWaku)) {
                continue;
            }
            // @todo: 後で評価点を取得する
            // 連続で同一カードはない
            // 1カード空けて同一カードがある(同一ホーム)があるとき 8
            // 1カード空けて同一カードがある(別ホーム)があるとき 4
            // 2カード空けて同一カードがある(同一ホーム)があるとき 3
            // 2カード空けて同一カードがある(別ホーム)があるとき 2
            // でかけるか足すかは別途調整してみる
            $targetCheckPoint = $this->checkPoint($tempAutoWaku);
            if (is_null($checkPoint) || $targetCheckPoint < $checkPoint) {
                $checkPoint = $targetCheckPoint;
                $autoWaku = $tempAutoWaku;
            }
        }

        // 枠が取得できたのであとは枠に合わせてデータを作成する
        // チーム割り振りも後調整
        $teamList = [
            1 => $requestData['team_id_1'],
            2 => $requestData['team_id_2'],
            3 => $requestData['team_id_3'],
            4 => $requestData['team_id_4'],
            5 => $requestData['team_id_5'],
            6 => $requestData['team_id_6'],
        ];
        $startDate = new Carbon($requestData['start_date']);
        $date = new Carbon($requestData['start_date']);

        $skipFlag = false;
        foreach ($autoWaku as $autoWakuParts) {
            // 月曜日のときは火曜日にスライドする
            if ($date->format('w') == 1) {
                $date = $date->addDay();
            }
            foreach ($autoWakuParts as $autoWakuPartsGame) {
                $targetDate = clone $date;
                // 2試合のときは水木/土日とする
                if ($autoWakuPartsGame['game'] == 2) {
                    $targetDate = $targetDate->addDay();
                }
                for ($i = 1;$i <= $autoWakuPartsGame['game'];$i++) {
                    $this::create([
                        'season_id' => $seasonId,
                        'date' => $targetDate->format('Y-m-d'),
                        'home_team_id' => $teamList[$autoWakuPartsGame['home_team']],
                        'visitor_team_id' => $teamList[$autoWakuPartsGame['visitor_team']],
                        'dh_flag' => $autoWakuPartsGame['dh']
                    ]);
                    $targetDate = $targetDate->addDay();
                }
            }
            // 処理が終わったときは3日進める
            $date = $date->addDay(3);

            // 100日ほどたったところで1週間の休みを一度加える(AS休み的な何か)
            if (!$skipFlag && $date->format('w') == 1 && $date->diffInDays($startDate) > 110) {
                $skipFlag = true;
                $date = $date->addDay(7);
            }
        }
    }

    public function autoWakuMake()
    {
        $initialGameList = $this->initialGameList();

        // 開幕カードは 3試合DHなしで 1-6/2-5/3-4固定なので探し出して抽出
        $gameBlock = [];

        $firstGameBlock = [];
        $latestGameBlock = [];
        foreach ($initialGameList as $initialGameKey => $initialGame) {
            if ($initialGame['home_team'] == 1 && $initialGame['visitor_team'] == 6 && $initialGame['game'] == 3 && $initialGame['dh'] == false) {
                $firstGameBlock[] = $initialGame;
                unset($initialGameList[$initialGameKey]);
                break;
            }
        }
        foreach ($initialGameList as $initialGameKey => $initialGame) {
            if ($initialGame['home_team'] == 2 && $initialGame['visitor_team'] == 5 && $initialGame['game'] == 3 && $initialGame['dh'] == false) {
                $firstGameBlock[] = $initialGame;
                unset($initialGameList[$initialGameKey]);
                break;
            }
        }
        foreach ($initialGameList as $initialGameKey => $initialGame) {
            if ($initialGame['home_team'] == 3 && $initialGame['visitor_team'] == 4 && $initialGame['game'] == 3 && $initialGame['dh'] == false) {
                $firstGameBlock[] = $initialGame;
                unset($initialGameList[$initialGameKey]);
                break;
            }
        }
        $gameBlock[] = $firstGameBlock;
        $latestGameBlock = $firstGameBlock;
        shuffle($initialGameList);

        // 以降のブロック
        $count = 0;
        while(true) {
            $count++;
            $targetGameBlock = [];
            foreach ($initialGameList as $initialGameKey => $initialGame) {
                foreach ($targetGameBlock as $targetGame) {
                    if (
                        $initialGame['home_team'] == $targetGame['home_team'] || 
                        $initialGame['home_team'] == $targetGame['visitor_team'] || 
                        $initialGame['visitor_team'] == $targetGame['home_team'] || 
                        $initialGame['visitor_team'] == $targetGame['visitor_team']
                    ) {
                        continue 2;
                    }
                }

                // 後でunset する用に$initialGameKeyをセット
                $targetGameBlock[$initialGameKey] = $initialGame;
                if (count($targetGameBlock) == 3) {
                    break;
                }
            }

            if (count($targetGameBlock) < 3) {
                // きれいなパターンで取得できなかった
                return [];
            }

            // 前のブロックと同一カードがなければOK
            $check = true;
            foreach ($targetGameBlock as $targetGame) {
                foreach ($latestGameBlock as $latestGame) {
                    if (
                        (
                            $targetGame['home_team'] == $latestGame['home_team'] &&
                            $targetGame['visitor_team'] == $latestGame['visitor_team']
                        ) ||
                        (
                            $targetGame['home_team'] == $latestGame['visitor_team'] &&
                            $targetGame['visitor_team'] == $latestGame['home_team']
                        )
                    ) {
                        $check = false;
                    }
                }
            }

            if ($check) {
                $gameBlock[] = $targetGameBlock;
                $latestGameBlock = $targetGameBlock;
                foreach (array_keys($targetGameBlock) as $deleteInitialGameKey) {
                    unset($initialGameList[$deleteInitialGameKey]);
                }
            } else {
                shuffle($initialGameList);
            }

            if (empty($initialGameList)) {
                break;
            }

            if ($count > 1000) {
                // 作成失敗(ループに入ってしまっている)
                return [];
            }
        }

        return $gameBlock;
    }

    private function initialGameList()
    {
        // 大前提 チームが6チームであること
        $gameList = [];
        for ($i = 1;$i <= 6;$i++) {
            for ($j = 1;$j <= 6;$j++) {
                // 同じチームの対戦は当然ないので無視
                if ($i === $j) {
                    continue;
                }
                // 3試合DHなしが3カード/3試合DHありが1カード/2試合DHなしが1カード
                for ($k = 1;$k <= 3;$k++) {
                    $gameList[] = [
                        'home_team' => $i,
                        'visitor_team' => $j,
                        'game' => 3,
                        'dh' => false
                    ];
                }
                $gameList[] = [
                    'home_team' => $i,
                    'visitor_team' => $j,
                    'game' => 3,
                    'dh' => true
                ];
                $gameList[] = [
                    'home_team' => $i,
                    'visitor_team' => $j,
                    'game' => 2,
                    'dh' => false
                ];
            }
        }

        return $gameList;
    }

    private function checkPoint($waku)
    {
        // 連続で同一カードはない前提
        // 1カード空けて同一カードがある(同一ホーム)があるとき 8
        // 1カード空けて同一カードがある(別ホーム)があるとき 4
        // 2カード空けて同一カードがある(同一ホーム)があるとき 3
        // 2カード空けて同一カードがある(別ホーム)があるとき 2
        // でかけるか足すかは別途調整してみる

        $point = 1;
        $beforeWaku = [];
        foreach ($waku as $wakuParts) {
            if (!empty($beforeWaku[2])) {
                // 2カード空けて同一カードがある(同一ホーム)があるとき 3
                // 2カード空けて同一カードがある(別ホーム)があるとき 2
                foreach ($wakuParts as $nowWakuPartsGame) {
                    foreach ($beforeWaku[2] as $beforeWakuPartsGame) {
                        if (
                            $nowWakuPartsGame['home_team'] == $beforeWakuPartsGame['home_team'] &&
                            $nowWakuPartsGame['visitor_team'] == $beforeWakuPartsGame['visitor_team']
                        ) {
                            $point *= 3;
                        }
                        if (
                            $nowWakuPartsGame['home_team'] == $beforeWakuPartsGame['visitor_team'] &&
                            $nowWakuPartsGame['visitor_team'] == $beforeWakuPartsGame['home_team']
                        ) {
                            $point *= 2;
                        }
                    }
                }
            }
            if (!empty($beforeWaku[1])) {
                // 1カード空けて同一カードがある(同一ホーム)があるとき 8
                // 1カード空けて同一カードがある(別ホーム)があるとき 4
                foreach ($wakuParts as $nowWakuPartsGame) {
                    foreach ($beforeWaku[1] as $beforeWakuPartsGame) {
                        if (
                            $nowWakuPartsGame['home_team'] == $beforeWakuPartsGame['home_team'] &&
                            $nowWakuPartsGame['visitor_team'] == $beforeWakuPartsGame['visitor_team']
                        ) {
                            $point *= 8;
                        }
                        if (
                            $nowWakuPartsGame['home_team'] == $beforeWakuPartsGame['visitor_team'] &&
                            $nowWakuPartsGame['visitor_team'] == $beforeWakuPartsGame['home_team']
                        ) {
                            $point *= 4;
                        }
                    }
                }

                $beforeWaku[2] = $beforeWaku[1];
            }
            if (!empty($beforeWaku[0])) {
                $beforeWaku[1] = $beforeWaku[0];
            }
            $beforeWaku[0] = $wakuParts;
        }

        return $point;
    }


    ### game view
    public function getViewInfo($gameId)
    {
        return $this->where('id', $gameId)
            ->with('home_team')
            ->with('visitor_team')
            ->with('home_probable_pitcher')
            ->with('visitor_probable_pitcher')
            ->first()
            ;
    }
}
