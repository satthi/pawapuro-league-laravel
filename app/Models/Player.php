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
    ];

    protected $appends = [
        'hand_p_text',
        'hand_b_text',
        'hand_full_text',
        'position_main_text',
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
            ->where('type', PlayType::TYPE_DAGEKI_KEKKA)
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
}
