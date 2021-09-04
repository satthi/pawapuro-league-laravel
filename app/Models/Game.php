<?php

namespace App\Models;

use App\Enums\GameBoardStatus;
use App\Enums\PlayType;
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

    protected $appends = [
        'display_inning'
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

    /**
     * win_game_pitcher
     */
    public function win_game_pitcher()
    {
        return $this->hasOne(GamePitcher::class, 'game_id')->where('win_flag', true);
    }

    /**
     * lose_game_pitcher
     */
    public function lose_game_pitcher()
    {
        return $this->hasOne(GamePitcher::class, 'game_id')->where('lose_flag', true);
    }

    /**
     * save_game_pitcher
     */
    public function save_game_pitcher()
    {
        return $this->hasOne(GamePitcher::class, 'game_id')->where('save_flag', true);
    }

    ## attribute
    /**
     * playのボードステータス
     *
     * @param  string  $value
     * @return string
     */
    public function getBoardStatusAttribute($value)
    {
        // スタメン未設定時
        $homeStamens = Stamen::where('game_id', $this->id)
            ->where('team_id', $this->home_team_id)
            ->get();
        $visitorStamens = Stamen::where('game_id', $this->id)
            ->where('team_id', $this->visitor_team_id)
            ->get();

        if ($homeStamens->isEmpty() || $visitorStamens->isEmpty()) {
            // スタメン未設定時
            return GameBoardStatus::STATUS_STAMEN_SETTING;
        } elseif (is_null($this->inning)) {
            // 試合開始
            return GameBoardStatus::STATUS_START;
        } elseif ($this->inning == 999) {
            return GameBoardStatus::STATUS_GAMEENDED;

        } elseif ($this->isGameEnd()) {
            return GameBoardStatus::STATUS_GAMEEND;
        } elseif ($this->out === 3) {
            // 3アウト/ イニング終了
            return GameBoardStatus::STATUS_INNING_END;
        } else {
            // 試合中
            return GameBoardStatus::STATUS_GAME;
        }
    }

    public function getIsNextInningAttribute($value)
    {
        // 次のイニング以外はfalse
        if ($this->getBoardStatusAttribute($value) !== GameBoardStatus::STATUS_INNING_END) {
            return false;
        }
        $member = (new Play())->getMember($this);
        // dump($member);
        // exit;
        $positionCheck = true;
        foreach ($member['home_team'] as $p) {
            if ($p['position']['value'] > 10) {
                $positionCheck = false;
            }
        }
        foreach ($member['visitor_team'] as $p) {
            if ($p['position']['value'] > 10) {
                $positionCheck = false;
            }
        }

        return $positionCheck;
    }
        // 'is_home_team_phpr',
        // 'is_home_team_position',
        // 'is_visitor_team_phpr',
        // 'is_visitor_team_position',

    public function getIsHomeTeamPhprAttribute($value)
    {
        // 試合中以外はない
        if ($this->getBoardStatusAttribute($value) !== GameBoardStatus::STATUS_GAME) {
            return false;
        }
        // 裏で3アウト以外
        return $this->inning % 10 == 2 && $this->out < 3;
    }
    public function getIsHomeTeamPositionAttribute($value)
    {
        // 試合中/イニング終了時以外はない
        if ($this->getBoardStatusAttribute($value) !== GameBoardStatus::STATUS_GAME && $this->getBoardStatusAttribute($value) !== GameBoardStatus::STATUS_INNING_END) {
            return false;
        }
        // 表で3アウト以外
        // 裏で3アウト
        return
            ($this->inning % 10 == 1 && $this->out < 3) ||
            ($this->inning % 10 == 2 && $this->out === 3);

    }
    public function getIsVisitorTeamPhprAttribute($value)
    {
        // 試合中以外はない
        if ($this->getBoardStatusAttribute($value) !== GameBoardStatus::STATUS_GAME) {
            return false;
        }
        // 表で3アウト以内
        return $this->inning % 10 == 1 && $this->out < 3;
    }
    public function getIsVisitorTeamPositionAttribute($value)
    {
        // 試合中/イニング終了時以外はない
        if ($this->getBoardStatusAttribute($value) !== GameBoardStatus::STATUS_GAME && $this->getBoardStatusAttribute($value) !== GameBoardStatus::STATUS_INNING_END) {
            return false;
        }
        return
            ($this->inning % 10 == 2 && $this->out < 3) ||
            ($this->inning % 10 == 1 && $this->out === 3);
    }

    public function getDisplayInningAttribute()
    {
        if (is_null($this->inning)) {
            return '開始前';
        } elseif ($this->inning == 999) {
            return '終了';
        } else {
            $text = floor($this->inning / 10) . '回';
            if ($this->inning % 10 == 1) {
                $text .= '表';
            } else {
                $text .= '裏';
            }

            return $text;
        }
    }

    private function isGameEnd()
    {
        // 9回までは終了はしない
        if ($this->inning < 91) {
            return false;
        }
        if ($this->inning == 91) {
            // 9表で終わるとき 3アウトでかつ後攻が勝っている
            if ($this->out === 3 && $this->home_point > $this->visitor_point) {
                return true;
            }
        }

        if ($this->inning % 10 == 2) {
            // 後攻で終わるときは後攻が勝っているとき
            if ($this->home_point > $this->visitor_point) {
                return true;
            }
            // 3アウト時に先行が買っている
            if ($this->out === 3 && $this->home_point < $this->visitor_point) {
                return true;
            }
        }

        // 12回裏で3アウト時試合終了
        if ($this->inning == 122 && $this->out === 3) {
            return true;
        }

        return false;
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
        $game = $this->where('id', $gameId)
            ->with('home_team')
            ->with('visitor_team')
            ->with('home_probable_pitcher')
            ->with('visitor_probable_pitcher')
            ->with('win_game_pitcher.player')
            ->with('lose_game_pitcher.player')
            ->with('save_game_pitcher.player')
            ->first();

        if (!is_null($game)) {
            $game = $game->append('board_status')
                ->append('is_home_team_phpr')
                ->append('is_home_team_position')
                ->append('is_visitor_team_phpr')
                ->append('is_visitor_team_position')
                ->append('is_next_inning');
        }

        return $game;
    }

    ### game update
    public function gameUpdate($game)
    {
        $playInfos = Play::where('game_id', $game->id)
            ->orderBy('id', 'ASC')
            ->get();
        $homeTeamId = $game->home_team_id;
        $visitorTeamId = $game->visitor_team_id;

        // playinfoない時はnullでセット
        if ($playInfos->isEmpty()) {
            $updateGameInfo = [
                'inning' => null,
                'out' => null,
                'home_point' => null,
                'visitor_point' => null,
            ];
            $game->update($updateGameInfo);

            return;
        }

        // 
        $updateGameInfo = [
            'inning' => 11,
            'out' => 0,
            'home_point' => 0,
            'visitor_point' => 0,
        ];
        foreach ($playInfos as $playInfo) {
            if ($updateGameInfo['inning'] != $playInfo->inning) {
                $updateGameInfo['inning'] = $playInfo->inning;
                $updateGameInfo['out'] = 0;
            }

            $updateGameInfo['out'] += $playInfo->out_count;
            if ($playInfo->team_id == $homeTeamId) {
                $updateGameInfo['home_point'] += $playInfo->point_count;
            } elseif ($playInfo->team_id == $visitorTeamId) {
                $updateGameInfo['visitor_point'] += $playInfo->point_count;
            }
        }

        $game->update($updateGameInfo);
    }

    public function nextInningUpdate($game)
    {

        $nextInning = null;
        // @todo: 試合終了の判定

        // 通常の次のイニング判定
        if ($game->out != 3) {
            // error
            dump('error');
            exit;
        }
        if ($game->inning % 10 == 1) {
            // 表から裏に
            $nextInning = $game->inning + 1;
        } elseif ($game->inning % 10 == 2) {
            // 裏から表に
            $nextInning = $game->inning + 9;
        } else {
            // error
            exit;
        }
        $game->update([
            'inning' => $nextInning,
            'out' => 0,
        ]);
    }

    public function gameEndPlay($requestData, $game)
    {
        $pitcherShukei = [];
        $initialDataSet = [
            'game_id' => $game->id,
            'win_flag' => false,
            'lose_flag' => false,
            'hold_flag' => false,
            'save_flag' => false,
            'jiseki' => 0,
            'inning' => 0,
            'daseki' => 0,
            'dasu' => 0,
            'hit' => 0,
            'hr' => 0,
            'sansin' => 0,
            'walk' => 0,
            'dead' => 0,
        ];
        $playForPitchers = Play::whereIn('type', [PlayType::TYPE_DAGEKI_KEKKA, PlayType::TYPE_STEAL, PlayType::TYPE_POINT_ONLY])
            ->with('result')
            ->with('pitcher')
            ->where('game_id', $game->id)
            ->orderBy('id', 'ASC')
            ->get();
        foreach ($playForPitchers as $playForPitcher) {
            if (!array_key_exists($playForPitcher->pitcher_id, $pitcherShukei)) {
                $pitcherShukei[$playForPitcher->pitcher_id] = $initialDataSet;
                if (!empty($requestData['pitcherResult']['win']) && $requestData['pitcherResult']['win'] == $playForPitcher->pitcher_id) {
                    $pitcherShukei[$playForPitcher->pitcher_id]['win_flag'] = true;
                }
                if (!empty($requestData['pitcherResult']['lose']) && $requestData['pitcherResult']['lose'] == $playForPitcher->pitcher_id) {
                    $pitcherShukei[$playForPitcher->pitcher_id]['lose_flag'] = true;
                }
                if (!empty($requestData['pitcherResult']['hold'][$playForPitcher->pitcher_id])) {
                    $pitcherShukei[$playForPitcher->pitcher_id]['hold_flag'] = true;
                }
                if (!empty($requestData['pitcherResult']['save']) && $requestData['pitcherResult']['save'] == $playForPitcher->pitcher_id) {
                    $pitcherShukei[$playForPitcher->pitcher_id]['save_flag'] = true;
                }
                if (!$playForPitcher->pitcher_id) {
                    dump($playForPitcher);
                    exit;
                }
                $pitcherShukei[$playForPitcher->pitcher_id]['jiseki'] = $requestData['pitcherResult']['jiseki'][$playForPitcher->pitcher_id];
                $pitcherShukei[$playForPitcher->pitcher_id]['player_id'] = $playForPitcher->pitcher_id;
                $pitcherShukei[$playForPitcher->pitcher_id]['team_id'] = $playForPitcher->pitcher->team_id;
            }

            $pitcherShukei[$playForPitcher->pitcher_id]['inning'] += $playForPitcher->out_count;
            if (!is_null($playForPitcher->result)) {
                $pitcherShukei[$playForPitcher->pitcher_id]['daseki']++;
                if ($playForPitcher->result->dasu_count_flag) {
                    $pitcherShukei[$playForPitcher->pitcher_id]['dasu']++;
                }
                if ($playForPitcher->result->hit_flag) {
                    $pitcherShukei[$playForPitcher->pitcher_id]['hit']++;
                }
                if ($playForPitcher->result->hr_flag) {
                    $pitcherShukei[$playForPitcher->pitcher_id]['hr']++;
                }
                if ($playForPitcher->result->sansin_flag) {
                    $pitcherShukei[$playForPitcher->pitcher_id]['sansin']++;
                }
                if ($playForPitcher->result->walk_flag) {
                    $pitcherShukei[$playForPitcher->pitcher_id]['walk']++;
                }
                if ($playForPitcher->result->dead_flag) {
                    $pitcherShukei[$playForPitcher->pitcher_id]['dead']++;
                }
            }
        }

        foreach ($pitcherShukei as $pitcherShukeiParts) {
            GamePitcher::create($pitcherShukeiParts);
        }

        // 試合終了登録
        $game->update([
            'inning' => 999, // 終了
        ]);
    }
}
