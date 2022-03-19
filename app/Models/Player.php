<?php

namespace App\Models;

use App\Enums\Kiki;
use App\Enums\PlayerPosition;
use App\Enums\PlayType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Player extends Model
{
    use HasFactory;
    protected $fillable = [
        'base_player_id',
        'team_id',
        'number',
        'name',
        'name_short',
        'hand_p',
        'hand_b',
        'position_main',
        'position_sub1',
        'position_sub2',
        'position_sub3',
        'game',
        'daseki',
        'dasu',
        'hit',
        'hit_2',
        'hit_3',
        'hr',
        'sansin',
        'heisatsu',
        'walk',
        'dead',
        'bant',
        'sac_fly',
        'daten',
        'steal_success',
        'steal_miss',
        'avg',
        'obp',
        'ops',
        'slg',
        'p_game',
        'p_win',
        'p_lose',
        'p_hold',
        'p_save',
        'p_daseki',
        'p_dasu',
        'p_win_ratio',
        'p_sansin',
        'p_sansin_ratio',
        'p_hit',
        'p_hr',
        'p_walk',
        'p_dead',
        'p_avg',
        'p_inning',
        'p_jiseki',
        'p_era',
        'accident_type',
        'walk_ritsu',
        'p_walk_ritsu',
        'trade_flag',
    ];

    protected $appends = [
        'hand_p_text',
        'hand_b_text',
        'hand_full_text',
        'position_main_text',
        'display_avg',
        'display_obp',
        'display_ops',
        'display_slg',
        'display_p_era',
        'display_p_win_ratio',
        'display_p_sansin_ratio',
        'display_p_avg',
        'display_p_inning',
        'img_path',
    ];
    /**
     * home team
     */
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
    /**
     * home team
     */
    public function base_player()
    {
        return $this->belongsTo(BasePlayer::class, 'base_player_id');
    }

    /**
     * 利き(投げ) テキスト表示
     *
     * @param  string  $value
     * @return string
     */
    public function getHandPTextAttribute($value)
    {
        return Kiki::getDescription($this->hand_p);
    }

    /**
     * 利き(打ち) テキスト表示
     *
     * @param  string  $value
     * @return string
     */
    public function getHandBTextAttribute($value)
    {
        return Kiki::getDescription($this->hand_b);
    }

    /**
     * 利き(投げ打ち) テキスト表示
     *
     * @param  string  $value
     * @return string
     */
    public function getHandFullTextAttribute($value)
    {
        return $this->hand_p_text . '投' . $this->hand_b_text . '打';
    }

    /**
     * メインポジション テキスト表示
     *
     * @param  string  $value
     * @return string
     */
    public function getPositionMainTextAttribute($value)
    {
        return PlayerPosition::getDescription($this->position_main);
    }

    public function getDisplayAvgAttribute($value)
    {
        if ($this->dasu == 0) {
            return '-';
        }

        return preg_replace('/^0/', '', sprintf('%.3f', round($this->avg, 3)));
    }
    public function getDisplayObpAttribute($value)
    {
        if ($this->dasu == 0 && $this->walk == 0 && $this->dead == 0 && $this->sac_fly == 0) {
            return '-';
        }

        return preg_replace('/^0/', '', sprintf('%.3f', round($this->obp, 3)));
    }
    public function getDisplayOpsAttribute($value)
    {
        if ($this->display_obp == '-') {
            return '-';
        }

        return preg_replace('/^0/', '', sprintf('%.3f', round($this->ops, 3)));
    }
    public function getDisplaySlgAttribute($value)
    {
        if ($this->dasu == 0) {
            return '-';
        }

        return preg_replace('/^0/', '', sprintf('%.3f', round($this->slg, 3)));
    }
    public function getDisplayPEraAttribute($value)
    {
        if (!$this->p_inning) {
            return '-';
        }

        return sprintf('%.2f', round($this->p_era, 2));
    }

    public function getDisplayPWinRatioAttribute($value)
    {
        if ($this->p_win == 0 && $this->p_lose == 0) {
            return '-';
        }

        return preg_replace('/^0/', '', sprintf('%.3f', round($this->p_win_ratio, 3)));
    }
    public function getDisplayPSansinRatioAttribute($value)
    {
        if (!$this->p_inning) {
            return '-';
        }

        return sprintf('%.2f', round($this->p_sansin_ratio, 2));
    }
    public function getDisplayPAvgAttribute($value)
    {
        if ($this->p_dasu == 0) {
            return '-';
        }

        return preg_replace('/^0/', '', sprintf('%.3f', round($this->p_avg, 3)));
    }
    public function getDisplayPInningAttribute($value)
    {
        $text = floor($this->p_inning / 3);
        if ($this->p_inning % 3 != 0) {
            $text .= ' ' . ($this->p_inning % 3) . '/3';
        }

        return $text;
    }

    public function getImgPathAttribute($value)
    {
        if (file_exists(public_path('img/base_player/' . $this->base_player_id . '/file'))) {
            return '/img/base_player/' . $this->base_player_id . '/file';
        }

        return '/img/noimage.jpg';
    }

    ## 対象日時点の個人の成績
    public function getTargetDateSeisekiInfo($date)
    {
        
        $player = Play::where('player_id', $this->id)
            ->join('games', 'games.id', '=', 'plays.game_id')
            ->join('results', 'results.id', '=', 'plays.result_id')
            ->where('date', '<=', $date)
            ->whereIn('type', [PlayType::TYPE_DAGEKI_KEKKA, PlayType::TYPE_STEAL])
            ->groupBy('player_id');

        $player = $this->fielderSeisekiSelect($player)
            ->first();
        if (is_null($player)) {
            return [
                'daseki' => 0,
                'dasu' => 0,
                'hit' => 0,
                'hit_2' => 0,
                'hit_3' => 0,
                'hr' => 0,
                'sansin' => 0,
                'heisatsu' => 0,
                'walk' => 0,
                'dead' => 0,
                'bant' => 0,
                'sac_fly' => 0,
                'daten' => 0,
                'steal_success' => 0,
                'steal_miss' => 0,
                'target_avg' => '-',
            ];
        }

        return $player->append('target_avg');
    }

    private function fielderSeisekiSelect($query)
    {
        return $query->select([
            \DB::raw('count(*) AS daseki'),
            $this->fielderSeisekiSelectParts('dasu_count_flag', 'dasu'),
            $this->fielderSeisekiSelectParts('hit_flag', 'hit'),
            $this->fielderSeisekiSelectParts('hit_2_flag', 'hit_2'),
            $this->fielderSeisekiSelectParts('hit_3_flag', 'hit_3'),
            $this->fielderSeisekiSelectParts('hr_flag', 'hr'),
            $this->fielderSeisekiSelectParts('sansin_flag', 'sansin'),
            $this->fielderSeisekiSelectParts('heisatsu_flag', 'heisatsu'),
            $this->fielderSeisekiSelectParts('walk_flag', 'walk'),
            $this->fielderSeisekiSelectParts('dead_flag', 'dead'),
            $this->fielderSeisekiSelectParts('bant_flag', 'bant'),
            $this->fielderSeisekiSelectParts('sac_fly_flag', 'sac_fly'),
            \DB::raw('sum(plays.point_count) AS daten'),
            \DB::raw('sum(CASE WHEN plays.type = ' . PlayType::TYPE_STEAL . ' AND plays.out_count = 0 THEN 1 ELSE 0 END) AS steal_success'),
            \DB::raw('sum(CASE WHEN plays.type = ' . PlayType::TYPE_STEAL . ' AND plays.out_count = 1 THEN 1 ELSE 0 END) AS steal_miss'),
        ]);
    }

    public function getRecentSeisekiInfo($game)
    {
        $lastPlayId = Play::where('game_id', $game->id)
            ->orderBy('id', 'DESC')
            ->firstOrFail();

        return $this->getTargetSeisekiInfo($game, $lastPlayId->id);
    }

    public function getTargetSeisekiInfo($game, $lastPlayId)
    {
        $gamePitcherModel = new GamePitcher();
        $gameSubDay = (new Carbon($game->date))->subDay()->format('Y/m/d');

        // 試合前情報
        $dagekiSeiseki = $this->getTargetDateSeisekiInfo($gameSubDay);
        $pitcherSeiseki = $gamePitcherModel->getSeiseki($this->id, $gameSubDay);

        // リアルタイム情報
        $checkPlayInfo = Play::where('game_id', $game->id)
            ->where('plays.id', '<=', $lastPlayId)
            ->where('player_id', $this->id)
            ->join('results', 'results.id', '=', 'plays.result_id')
            ->whereIn('type', [PlayType::TYPE_DAGEKI_KEKKA, PlayType::TYPE_STEAL])
            ->groupBy('player_id');
        
        $checkPlayInfo = $this->fielderSeisekiSelect($checkPlayInfo)
            ->first();

        if (!is_null($checkPlayInfo)) {
            foreach ($checkPlayInfo->toArray() as $fieldKey => $playVal) {
                $dagekiSeiseki[$fieldKey] = $dagekiSeiseki[$fieldKey] + $playVal;
            }

            if (!$dagekiSeiseki['dasu']) {
                $dagekiSeiseki['target_avg'] = '-';
            } else {
                $avg = sprintf("%.3f", round($dagekiSeiseki['hit'] / $dagekiSeiseki['dasu'], 3));

                $avg = preg_replace('/^0/', '' , $avg);

                $dagekiSeiseki['target_avg'] = $avg;
            }
        }

        return [
            'dageki' => $dagekiSeiseki['target_avg'] . ' ' . $dagekiSeiseki['hr'] . '本 ' . $dagekiSeiseki['daten'] . '点 ',
            'pitcher' => $pitcherSeiseki['game_sum'] . '試' . $pitcherSeiseki['win_count'] . '勝' . $pitcherSeiseki['lose_count'] . '敗 ' . $pitcherSeiseki['era'],
        ];
    }

    public function fielderSeisekiSelectParts($checkField, $asField)
    {
        return \DB::raw('sum(CASE WHEN results.' . $checkField . ' THEN 1 ELSE 0 END) AS ' . $asField);
    }

    public function getProbablePitcherOptions(Game $game, $teamId)
    {
        $gamePitcherModel = new GamePitcher();
        $gameSubDay = (new Carbon($game->date))->subDay()->format('Y/m/d');

        $pitchers = $this->where('team_id', $teamId)
            ->where('trade_flag', false)
            // 並び順の調整
            ->orderBy(\DB::raw('position_main = 1'), 'DESC')
            ->orderBy(\DB::raw('number::integer'), 'ASC')
            ->orderBy('id', 'ASC')
            ->get()
            ;
        $pitcherOptions = [];
        foreach ($pitchers as $pitcher) {
            $seiseki = $gamePitcherModel->getSeiseki($pitcher->id, $gameSubDay);
            $pitcherOptions[] = [
                'value' => $pitcher->id,
                'text' => $pitcher->number . '. ' . $pitcher->name . ' ' .$seiseki['game_sum'] . '試' . $seiseki['win_count'] . '勝' . $seiseki['lose_count'] . '敗 ' . $seiseki['era'],
            ];
        }

        return $pitcherOptions;
    }

    public function getPitcherHistory(Game $game, $teamId)
    {
        // 過去10試合の投手履歴の取得
        // 対象ゲーム
        $gameModel = new Game();
        $gamePitcherModel = new GamePitcher();
        $targetGames = $gameModel
            ->where(function($q) use ($teamId) {
                $q->where('games.home_team_id', $teamId)
                    ->orWhere('games.visitor_team_id', $teamId);
            })
            ->where('games.inning' , 999)
            ->where('games.date', '<' , $game->date)
            ->orderBy('games.date', 'DESC')
            ->limit(10)
            ->get();

        $returnArray = [];

        foreach ($targetGames as $targetGame) {
        \Log::debug('AAAA');
            $returnArray[$targetGame->date] = [
                'date' => (new Carbon($targetGame->date))->format('m/d(D)'),
                'info' => [],
            ];
            $gamePitchers = $gamePitcherModel->where('game_pitchers.game_id', $targetGame->id)
                ->where('game_pitchers.team_id', $teamId)
                ->with('player')
                ->orderBy('game_pitchers.id', 'ASC')
                ->get();

            foreach ($gamePitchers as $gamePitcher) {
                $returnArray[$targetGame->date]['info'][] = [
                    'player' => $gamePitcher->player->name_short,
                    'inning' => $gamePitcher->string_inning,
                ];
            }
        }

        \Log::debug($returnArray);

        return $returnArray;


    }

    public function shukei($seasonId)
    {
        $shukeis =  $this->leftjoin('plays', 'players.id', '=', 'plays.player_id')
            ->leftjoin('games', 'games.id', '=', 'plays.game_id')
            ->leftjoin('results', 'results.id', '=', 'plays.result_id')
            ->join('teams', 'teams.id', '=', 'players.team_id')
            ->where('teams.season_id', $seasonId)
            ->where(function($q){
                $q->where('plays.type', PlayType::TYPE_DAGEKI_KEKKA)
                    ->orWhere('plays.type', PlayType::TYPE_STEAL)
                    ->orWhere('plays.type', PlayType::TYPE_STAMEN)
                    ->orWhere('plays.type', PlayType::TYPE_MEMBER_CHANGE)
                    ->orWhere('plays.type');
            })
            ->groupBy('players.id')
            ->select([
                'players.id as player_id',
                \DB::raw('count(DISTINCT(games.id)) AS game'),
                \DB::raw('count(results.id) AS daseki'),
                $this->fielderSeisekiSelectParts('dasu_count_flag', 'dasu'),
                $this->fielderSeisekiSelectParts('hit_flag', 'hit'),
                $this->fielderSeisekiSelectParts('hit_2_flag', 'hit_2'),
                $this->fielderSeisekiSelectParts('hit_3_flag', 'hit_3'),
                $this->fielderSeisekiSelectParts('hr_flag', 'hr'),
                $this->fielderSeisekiSelectParts('sansin_flag', 'sansin'),
                $this->fielderSeisekiSelectParts('heisatsu_flag', 'heisatsu'),
                $this->fielderSeisekiSelectParts('walk_flag', 'walk'),
                $this->fielderSeisekiSelectParts('dead_flag', 'dead'),
                $this->fielderSeisekiSelectParts('bant_flag', 'bant'),
                $this->fielderSeisekiSelectParts('sac_fly_flag', 'sac_fly'),
                \DB::raw('coalesce(sum(plays.point_count), 0) AS daten'),
                \DB::raw('sum(CASE WHEN plays.type = ' . PlayType::TYPE_STEAL . ' AND plays.out_count = 0 THEN 1 ELSE 0 END) AS steal_success'),
                \DB::raw('sum(CASE WHEN plays.type = ' . PlayType::TYPE_STEAL . ' AND plays.out_count = 1 THEN 1 ELSE 0 END) AS steal_miss'),
            ])
            ->get()
            ->keyBy('player_id')
            ->toArray()
            ;

        $pitcherShukeis = $this::join('teams', 'teams.id', '=', 'players.team_id')
            ->leftjoin('game_pitchers', 'game_pitchers.player_id', '=', 'players.id')
            ->where('teams.season_id', $seasonId)
            ->select([
                'players.id as player_id',
                \DB::raw('count(game_pitchers.id) as game_sum'),
                \DB::raw('coalesce(sum(game_pitchers.inning), 0) as inning_sum'),
                \DB::raw('coalesce(sum(game_pitchers.jiseki), 0) as jiseki_sum'),
                \DB::raw('coalesce(sum(game_pitchers.daseki), 0) as daseki_sum'),
                \DB::raw('coalesce(sum(game_pitchers.dasu), 0) as dasu_sum'),
                \DB::raw('coalesce(sum(game_pitchers.hit), 0) as hit_sum'),
                \DB::raw('coalesce(sum(game_pitchers.hr), 0) as hr_sum'),
                \DB::raw('coalesce(sum(game_pitchers.sansin), 0) as sansin_sum'),
                \DB::raw('coalesce(sum(game_pitchers.walk), 0) as walk_sum'),
                \DB::raw('coalesce(sum(game_pitchers.dead), 0) as dead_sum'),
                \DB::raw('coalesce(sum(game_pitchers.win_flag::integer), 0) as win_count'),
                \DB::raw('coalesce(sum(game_pitchers.lose_flag::integer), 0) as lose_count'),
                \DB::raw('coalesce(sum(game_pitchers.hold_flag::integer), 0) as hold_count'),
                \DB::raw('coalesce(sum(game_pitchers.save_flag::integer), 0) as save_count'),
            ])
            ->groupBy('players.id')
            ->get()
            ;

        foreach ($pitcherShukeis as $pitcherShukei) {
            $shukeis[$pitcherShukei->player_id]['p_game'] = $pitcherShukei->game_sum;
            $shukeis[$pitcherShukei->player_id]['p_inning'] = $pitcherShukei->inning_sum;
            $shukeis[$pitcherShukei->player_id]['p_jiseki'] = $pitcherShukei->jiseki_sum;
            $shukeis[$pitcherShukei->player_id]['p_daseki'] = $pitcherShukei->daseki_sum;
            $shukeis[$pitcherShukei->player_id]['p_dasu'] = $pitcherShukei->dasu_sum;
            $shukeis[$pitcherShukei->player_id]['p_hit'] = $pitcherShukei->hit_sum;
            $shukeis[$pitcherShukei->player_id]['p_hr'] = $pitcherShukei->hr_sum;
            $shukeis[$pitcherShukei->player_id]['p_sansin'] = $pitcherShukei->sansin_sum;
            $shukeis[$pitcherShukei->player_id]['p_walk'] = $pitcherShukei->walk_sum;
            $shukeis[$pitcherShukei->player_id]['p_dead'] = $pitcherShukei->dead_sum;
            $shukeis[$pitcherShukei->player_id]['p_win'] = $pitcherShukei->win_count;
            $shukeis[$pitcherShukei->player_id]['p_lose'] = $pitcherShukei->lose_count;
            $shukeis[$pitcherShukei->player_id]['p_hold'] = $pitcherShukei->hold_count;
            $shukeis[$pitcherShukei->player_id]['p_save'] = $pitcherShukei->save_count;
            $shukeis[$pitcherShukei->player_id]['p_avg'] = 0;
            if ($pitcherShukei->dasu_sum) {
                $shukeis[$pitcherShukei->player_id]['p_avg'] = $pitcherShukei->hit_sum / $pitcherShukei->dasu_sum;
            }
            $shukeis[$pitcherShukei->player_id]['p_era'] = 0;
            if ($pitcherShukei->inning_sum) {
                $shukeis[$pitcherShukei->player_id]['p_era'] = $pitcherShukei->jiseki_sum / $pitcherShukei->inning_sum * 27;
            }
            $shukeis[$pitcherShukei->player_id]['p_win_ratio'] = 0;
            if ($pitcherShukei->win_count + $pitcherShukei->lose_count) {
                $shukeis[$pitcherShukei->player_id]['p_win_ratio'] = $pitcherShukei->win_count / ($pitcherShukei->win_count + $pitcherShukei->lose_count);
            }
            $shukeis[$pitcherShukei->player_id]['p_sansin_ratio'] = 0;
            if ($pitcherShukei->inning_sum) {
                $shukeis[$pitcherShukei->player_id]['p_sansin_ratio'] = $pitcherShukei->sansin_sum / $pitcherShukei->inning_sum * 27;
            }

        }

        foreach ($shukeis as $shukei) {
            $player = Player::find($shukei['player_id']);

            $shukei['avg'] = 0;
            $shukei['obp'] = 0;
            $shukei['slg'] = 0;
            // avg
            if ($shukei['dasu']) {
                $shukei['avg'] = $shukei['hit'] / $shukei['dasu'];
                $shukei['slg'] = ($shukei['hit'] +  $shukei['hit_2'] + $shukei['hit_3'] * 2 + $shukei['hr'] * 3) / $shukei['dasu'];
            }

            //obp
            $obpBunbo = $shukei['dasu'] + $shukei['walk'] + $shukei['dead'] + $shukei['sac_fly'];
            $obpBunshi = $shukei['hit'] + $shukei['walk'] + $shukei['dead'];
            if ($obpBunbo) {
                $shukei['obp'] = $obpBunshi / $obpBunbo;
            }

            // 出塁率+長打率
            $shukei['ops'] = $shukei['obp'] + $shukei['slg'];

            unset($shukei['player_id']);
            $player->update($shukei);
        }
    }

    public function getRank(Season $season, string $sortType)
    {
        $players = $this::join('teams', 'teams.id', '=', 'players.team_id')
            ->select([
                'players.*',
                'teams.ryaku_name as team_ryaku_name'
            ])
            ->where('teams.season_id', $season->id)
            // 一応30件まで
            ->limit(30);

        switch ($sortType) {
            case 'avg':
            case 'obp':
            case 'ops':
            case 'slg':
                // 打率/出塁率/OPSのランキングは降順/規定打席到達のみ
                $players->orderBy($sortType, 'DESC')
                    ->whereRaw(\DB::raw('players.daseki::numeric >= teams.game::numeric * 3.1'));
                break;
            case 'p_sansin_ratio':
                // 打率/出塁率/OPSのランキングは降順/規定打席到達のみ
                $players->orderBy($sortType, 'DESC')
                    ->whereRaw(\DB::raw('players.p_inning >= teams.game * 3'));
                break;
            case 'p_era':
            case 'p_avg':
                // 防御率は昇順/規定投球回数到達の実
                $players->orderBy($sortType, 'ASC')
                    ->whereRaw(\DB::raw('players.p_inning >= teams.game * 3'));

                break;

            case 'p_win_ratio':
                // 勝率は試合が残っている場合は規定投球回数・試合が残っていない場合は13章
                $players->orderBy($sortType, 'DESC')
                    ->whereRaw(\DB::raw('((teams.remain > 0 AND players.p_inning >= teams.game * 3) OR players.p_win >= 13)'));

                break;
            default:
                $players->orderBy($sortType, 'DESC');
        }

        // dump($players->toSql());
        // exit;

        return $players->get();

    }

    public function getSeasonFielderHistory(Player $player)
    {
        return $this::where('base_player_id', $player->base_player_id)
            ->select([
                'players.*',
                'teams.ryaku_name as team_ryaku_name',
                'seasons.name as season_name',
            ])
            ->leftjoin('teams', 'teams.id', '=', 'players.team_id')
            ->leftjoin('seasons', 'seasons.id', '=', 'teams.season_id')
            ->where('seasons.regular_flag', true)
            ->where('players.daseki', '>', 0)
            ->orderBy('seasons.id', 'ASC')
            ->get();
    }
    public function getSeasonPitcherHistory(Player $player)
    {
        return $this::where('base_player_id', $player->base_player_id)
            ->select([
                'players.*',
                'teams.ryaku_name as team_ryaku_name',
                'seasons.name as season_name',
            ])
            ->leftjoin('teams', 'teams.id', '=', 'players.team_id')
            ->leftjoin('seasons', 'seasons.id', '=', 'teams.season_id')
            ->where('seasons.regular_flag', true)
            ->where('players.p_game', '>', 0)
            ->orderBy('seasons.id', 'ASC')
            ->get();
    }

    public function trade($requestData)
    {
        \Log::debug($requestData);

        $isekimotoPlayer = $this::find($requestData['player_id']);
        // 移籍元のtrade_flagをonに
        $isekimotoPlayer->update(['trade_flag' => true]);

        // 移籍先のデータの作成
        $isekisakiPlayerData = [
            'base_player_id' => $isekimotoPlayer->base_player_id,
            'team_id' => $requestData['team_id'],
            'number' => $requestData['number'],
            'name' => $isekimotoPlayer->name,
            'name_short' => $isekimotoPlayer->name_short,
            'hand_p' => $isekimotoPlayer->hand_p,
            'hand_b' => $isekimotoPlayer->hand_b,
            'position_main' => $isekimotoPlayer->position_main,
            'position_sub1' => $isekimotoPlayer->position_sub1,
            'position_sub2' => $isekimotoPlayer->position_sub2,
            'position_sub3' => $isekimotoPlayer->position_sub3,
        ];
        $this::create($isekisakiPlayerData);

        // baseplayerの情報更新
        $team = Team::find($requestData['team_id']);
        $basePlayer = BasePlayer::find($isekimotoPlayer->base_player_id);
        $basePlayer->update([
            'base_player_id' => $team->base_player_id,
            'number' => $requestData['number'],
        ]);

        // 最後にplayerの集計はかけておく
        $this->shukei($requestData['season_id']);
    }


}
