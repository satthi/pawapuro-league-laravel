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
        'accident_type',
        'walk_ritsu',
        'p_walk_ritsu',
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

    public function shukei()
    {
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
}
