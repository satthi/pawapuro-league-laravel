<?php

namespace App\Models;

use App\Enums\Kiki;
use App\Enums\PlayerPosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasePlayer extends Model
{
    use HasFactory;
    protected $fillable = [
        'base_team_id',
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
        'p_kanto',
        'p_kanpu',
        'accident_type',
        'walk_ritsu',
        'p_walk_ritsu',
        'mvp_count_for_fielder',
        'b9_count_for_fielder',
        'avg_king_count',
        'hr_king_count',
        'daten_king_count',
        'hit_king_count',
        'steal_king_count',
        'kitei_daseki_count',
        'avg_2wari_8bu_count',
        'avg_3wari_count',
        'avg_3wari_2bu_count',
        'avg_3wari_4bu_count',
        'hr_10_count',
        'hr_20_count',
        'hr_30_count',
        'hr_40_count',
        'daten_60_count',
        'daten_80_count',
        'daten_100_count',
        'steal_10_count',
        'steal_20_count',
        'steal_30_count',
        'steal_40_count',
        'mvp_count_for_pitcher',
        'b9_count_for_pitcher',
        'p_era_king_count',
        'p_win_king_count',
        'p_win_ratio_king_count',
        'p_sansin_king_count',
        'p_hold_king_count',
        'p_save_king_count',
        'kitei_tokyu_count',
        'p_game_50_count',
        'p_era_1ten_count',
        'p_era_2ten_count',
        'p_win_10_count',
        'p_win_13_count',
        'p_win_15_count',
        'p_hold_30_count',
        'p_save_30_count',

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
    ];

    private $titleRank = [];
    private $baseRank = [
            'mvp_count_for_fielder' => 0,
            'b9_count_for_fielder' => 0,
            'avg_king_count' => 0,
            'hr_king_count' => 0,
            'daten_king_count' => 0,
            'hit_king_count' => 0,
            'steal_king_count' => 0,
            'kitei_daseki_count' => 0,
            'avg_2wari_8bu_count' => 0,
            'avg_3wari_count' => 0,
            'avg_3wari_2bu_count' => 0,
            'avg_3wari_4bu_count' => 0,
            'hr_10_count' => 0,
            'hr_20_count' => 0,
            'hr_30_count' => 0,
            'hr_40_count' => 0,
            'daten_60_count' => 0,
            'daten_80_count' => 0,
            'daten_100_count' => 0,
            'steal_10_count' => 0,
            'steal_20_count' => 0,
            'steal_30_count' => 0,
            'steal_40_count' => 0,
            'mvp_count_for_pitcher' => 0,
            'b9_count_for_pitcher' => 0,
            'p_era_king_count' => 0,
            'p_win_king_count' => 0,
            'p_win_ratio_king_count' => 0,
            'p_sansin_king_count' => 0,
            'p_hold_king_count' => 0,
            'p_save_king_count' => 0,
            'kitei_tokyu_count' => 0,
            'p_game_50_count' => 0,
            'p_era_1ten_count' => 0,
            'p_era_2ten_count' => 0,
            'p_win_10_count' => 0,
            'p_win_13_count' => 0,
            'p_win_15_count' => 0,
            'p_hold_30_count' => 0,
            'p_save_30_count' => 0,
        ];
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

    private function setTitleRank($basePlayerId, $field)
    {
        if (empty($this->titleRank[$basePlayerId])) {
            $this->titleRank[$basePlayerId] = $this->baseRank;
        }
        $this->titleRank[$basePlayerId][$field]++;
    }

    public function shukei()
    {
        // ランキング
        $endSeasons = Season::where('regular_flag', true)
            ->where(\DB::raw('NOT EXISTS(SELECT 1 FROM teams WHERE teams.season_id = seasons.id AND teams.remain > 0)'))
            ->get();

        $endSeasonIds = [];
        foreach ($endSeasons as $endSeason) {
            $endSeasonIds[] = $endSeason->id;
            $title = $endSeason->getTitle();
            foreach ($title['avg']['players'] as $avgPlayer) {
                $this->setTitleRank($avgPlayer->base_player_id, 'avg_king_count');
            }
            foreach ($title['hr']['players'] as $avgPlayer) {
                $this->setTitleRank($avgPlayer->base_player_id, 'hr_king_count');
            }
            foreach ($title['daten']['players'] as $avgPlayer) {
                $this->setTitleRank($avgPlayer->base_player_id, 'daten_king_count');
            }
            foreach ($title['hit']['players'] as $avgPlayer) {
                $this->setTitleRank($avgPlayer->base_player_id, 'hit_king_count');
            }
            foreach ($title['steal']['players'] as $avgPlayer) {
                $this->setTitleRank($avgPlayer->base_player_id, 'steal_king_count');
            }
            foreach ($title['p_era']['players'] as $avgPlayer) {
                $this->setTitleRank($avgPlayer->base_player_id, 'p_era_king_count');
            }
            foreach ($title['p_win']['players'] as $avgPlayer) {
                $this->setTitleRank($avgPlayer->base_player_id, 'p_win_king_count');
            }
            foreach ($title['p_win_ratio']['players'] as $avgPlayer) {
                $this->setTitleRank($avgPlayer->base_player_id, 'p_win_ratio_king_count');
            }
            foreach ($title['p_sansin']['players'] as $avgPlayer) {
                $this->setTitleRank($avgPlayer->base_player_id, 'p_sansin_king_count');
            }
            foreach ($title['p_hold']['players'] as $avgPlayer) {
                $this->setTitleRank($avgPlayer->base_player_id, 'p_hold_king_count');
            }
            foreach ($title['p_save']['players'] as $avgPlayer) {
                $this->setTitleRank($avgPlayer->base_player_id, 'p_save_king_count');
            }
            if ($title['mvp']) {
                if ($title['mvp']->position_main == 1) {
                    $this->setTitleRank($title['mvp']->base_player_id, 'mvp_count_for_pitcher');
                } else {
                    $this->setTitleRank($title['mvp']->base_player_id, 'mvp_count_for_fielder');
                }
            }
            if ($title['bb_1']) {
                $this->setTitleRank($title['bb_1']->base_player_id, 'b9_count_for_pitcher');
            }
            if ($title['bb_2']) {
                $this->setTitleRank($title['bb_2']->base_player_id, 'b9_count_for_fielder');
            }
            if ($title['bb_3']) {
                $this->setTitleRank($title['bb_3']->base_player_id, 'b9_count_for_fielder');
            }
            if ($title['bb_4']) {
                $this->setTitleRank($title['bb_4']->base_player_id, 'b9_count_for_fielder');
            }
            if ($title['bb_5']) {
                $this->setTitleRank($title['bb_5']->base_player_id, 'b9_count_for_fielder');
            }
            if ($title['bb_6']) {
                $this->setTitleRank($title['bb_6']->base_player_id, 'b9_count_for_fielder');
            }
            if ($title['bb_7']) {
                $this->setTitleRank($title['bb_7']->base_player_id, 'b9_count_for_fielder');
            }
            if ($title['bb_8']) {
                $this->setTitleRank($title['bb_8']->base_player_id, 'b9_count_for_fielder');
            }
            if ($title['bb_9']) {
                $this->setTitleRank($title['bb_9']->base_player_id, 'b9_count_for_fielder');
            }
        }

        // 成績
        $seisekiCheckPlayers = Player::leftjoin('teams', 'teams.id', '=', 'players.team_id')
            ->whereIn('teams.season_id', $endSeasonIds)
            ->select([
                'players.base_player_id',
                'players.daseki',
                'players.avg',
                'players.hr',
                'players.daten',
                'players.steal_success',
                'players.p_inning',
                'players.p_game',
                'players.p_era',
                'players.p_win',
                'players.p_win_ratio',
                'players.p_sansin',
                'players.p_hold',
                'players.p_save',
                'teams.game as team_game',
            ])
            ->get();
        foreach ($seisekiCheckPlayers as $seisekiCheckPlayer) {
            //kitei_daseki_count
            if ($seisekiCheckPlayer->daseki >= $seisekiCheckPlayer->team_game * 3.1) {
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'kitei_daseki_count');
                if ($seisekiCheckPlayer->avg >= 0.28){
                    $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'avg_2wari_8bu_count');
                }
                if ($seisekiCheckPlayer->avg >= 0.3){
                    $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'avg_3wari_count');
                }
                if ($seisekiCheckPlayer->avg >= 0.32){
                    $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'avg_3wari_2bu_count');
                }
                if ($seisekiCheckPlayer->avg >= 0.34){
                    $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'avg_3wari_4bu_count');
                }
            }
            if ($seisekiCheckPlayer->hr >= 10){
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'hr_10_count');
            }
            if ($seisekiCheckPlayer->hr >= 20){
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'hr_20_count');
            }
            if ($seisekiCheckPlayer->hr >= 30){
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'hr_30_count');
            }
            if ($seisekiCheckPlayer->hr >= 40){
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'hr_40_count');
            }
            if ($seisekiCheckPlayer->daten >= 60){
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'daten_60_count');
            }
            if ($seisekiCheckPlayer->daten >= 80){
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'daten_80_count');
            }
            if ($seisekiCheckPlayer->daten >= 100){
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'daten_100_count');
            }
            if ($seisekiCheckPlayer->steal_success >= 10){
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'steal_10_count');
            }
            if ($seisekiCheckPlayer->steal_success >= 20){
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'steal_20_count');
            }
            if ($seisekiCheckPlayer->steal_success >= 30){
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'steal_30_count');
            }
            if ($seisekiCheckPlayer->steal_success >= 40){
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'steal_40_count');
            }
            if ($seisekiCheckPlayer->p_inning >= $seisekiCheckPlayer->team_game * 3){
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'kitei_tokyu_count');
                if ($seisekiCheckPlayer->p_era < 2){
                    $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'p_era_1ten_count');
                }
                if ($seisekiCheckPlayer->p_era < 3){
                    $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'p_era_2ten_count');
                }
            }

            if ($seisekiCheckPlayer->p_game >= 50){
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'p_game_50_count');
            }

            if ($seisekiCheckPlayer->p_win >= 10){
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'p_win_10_count');
            }

            if ($seisekiCheckPlayer->p_win >= 13){
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'p_win_13_count');
            }

            if ($seisekiCheckPlayer->p_win >= 15){
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'p_win_15_count');
            }

            if ($seisekiCheckPlayer->p_hold >= 30){
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'p_hold_30_count');
            }

            if ($seisekiCheckPlayer->p_save >= 30){
                $this->setTitleRank($seisekiCheckPlayer->base_player_id, 'p_save_30_count');
            }

        }

        // dump($this->titleRank);
        // exit;

        $sumFields = [
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
            // 'avg',
            // 'obp',
            // 'ops',
            // 'slg',
            'p_game',
            'p_win',
            'p_lose',
            'p_hold',
            'p_save',
            'p_daseki',
            'p_dasu',
            // 'p_win_ratio',
            'p_sansin',
            // 'p_sansin_ratio',
            'p_hit',
            'p_hr',
            'p_walk',
            'p_dead',
            // 'p_avg',
            'p_inning',
            'p_jiseki',
            // 'p_era',
            'p_kanto',
            'p_kanpu',
        ];

        $selectMake = [];
        $selectMake[] = 'base_players.id';
        foreach ($sumFields as $sumField) {
            $selectMake[] = \DB::raw('coalesce(sum(players.' . $sumField . '), 0) as ' . $sumField);
        }

        $basePlayerShukeis = $this::leftjoin('players', 'players.base_player_id', '=', 'base_players.id')
            ->leftjoin('teams', 'teams.id', '=', 'players.team_id')
            ->leftjoin('seasons', 'seasons.id', '=', 'teams.season_id')
            ->where(function($q) {
                $q->where('seasons.regular_flag', true)
                    ->orWhere('seasons.regular_flag', null);
            })
            ->select($selectMake)
            ->groupBy('base_players.id')
            ->get();

        foreach ($basePlayerShukeis as $basePlayerShukei) {
            $shukei = $basePlayerShukei->toArray();
            foreach ($this->appends as $append) {
                unset($shukei[$append]);
            }

            $updateBaseData = $this::find($shukei['id']);

            if (empty($this->titleRank[$shukei['id']])) {
                $this->titleRank[$shukei['id']] = $this->baseRank;
            }
            $shukei = array_merge($shukei, $this->titleRank[$shukei['id']]);

            unset($shukei['id']);

            // 打撃集計
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

            // 投手集計
            $shukei['p_avg'] = 0;
            if ($shukei['p_dasu']) {
                $shukei['p_avg'] = $shukei['p_hit'] / $shukei['p_dasu'];
            }
            $shukei['p_era'] = 0;
            if ($shukei['p_inning']) {
                $shukei['p_era'] = $shukei['p_jiseki'] / $shukei['p_inning'] * 27;
            }
            $shukei['p_win_ratio'] = 0;
            if ($shukei['p_win'] + $shukei['p_lose']) {
                $shukei['p_win_ratio'] = $shukei['p_win'] / ($shukei['p_win'] + $shukei['p_lose']);
            }
            $shukei['p_sansin_ratio'] = 0;
            if ($shukei['p_inning']) {
                $shukei['p_sansin_ratio'] = $shukei['p_sansin'] / $shukei['p_inning'] * 27;
            }


            $updateBaseData->update($shukei);
        }

    }

    public function getRank(string $sortType, $zeroCheck = false)
    {
        $players = $this::join('base_teams', 'base_teams.id', '=', 'base_players.base_team_id')
            ->select([
                'base_players.*',
                'base_teams.ryaku_name as team_ryaku_name'
            ]);

        switch ($sortType) {
            case 'p_era':
            case 'p_avg':
                // 防御率は昇順/規定投球回数到達の実
                $players->where('p_game', '>' , 0)->orderBy($sortType, 'ASC');
                break;
            default:
            $players->orderBy($sortType, 'DESC');
        }

        if ($zeroCheck) {
            $players->where($sortType, '>', 0);
        }

        return $players->get();

    }
}
