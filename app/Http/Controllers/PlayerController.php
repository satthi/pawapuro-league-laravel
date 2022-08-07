<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerTradeRequest;
use App\Enums\PlayerPosition;
use App\Models\Play;
use App\Models\Player;
use App\Models\Season;
use App\Models\GamePitcher;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
     public function view(Player $player)
     {
        $playModel = new Play();
        $playerModel = new Player();
        $playerModel = new Player();
        $gamePitcherModel = new GamePitcher();

        $player->team->season;
        $player->base_player;

        $seasonFielderHistories = $playerModel->getSeasonFielderHistory($player);
        $seasonPitcherHistories = $playerModel->getSeasonPitcherHistory($player);

        // 紹介文
        $count = [
            // MVP
            'mvp' => 0,
            // B9
            'b9' => 0,
            // 首位打者
            'avg' => 0,
            // HR
            'hr' => 0,
            // 打点
            'daten' => 0,
            // 最多安打
            'hit' => 0,
            // 盗塁王
            'steal' => 0,
            // 規定打席到達
            'kitei_daseki' => 0,
            // 3割
            'avg_3wari' => 0,
            // 30本
            'hr_30' => 0,
            // 100打点
            'daten_100' => 0,
            // 30盗塁
            'steal_30' => 0,

            // 最優秀防御率
            'era' => 0,
            // 最多勝
            'win' => 0,
            // 最高勝率
            'win_ratio' => 0,
            // 最多奪三振
            'sansin' => 0,
            // 最優秀中継ぎ投手
            'hold' => 0,
            // 最優秀救援投手
            'save' => 0,
            // 規定投球回数
            'kitei_tokyu' => 0,
            // 50試合以上
            'game_50' => 0,
            // 10勝
            'win_10' => 0,
            // 防御率1点台
            'era_1ten' => 0,
            // 30ホールド
            'hold_30' => 0,
            // 30セーブ
            'save_30' => 0,

        ];
        foreach ($seasonFielderHistories as $seasonFielderHistory) {
            // MVP
            if ($seasonFielderHistory->is_mvp) {
                $count['mvp']++;
            }
            // B9
            if ($seasonFielderHistory->is_b9) {
                $count['b9']++;
            }
            // 首位打者
            if ($seasonFielderHistory->avg_rank == '<b>(1)</b>') {
                $count['avg']++;
            }
            // HR
            if ($seasonFielderHistory->hr_rank == '<b>(1)</b>') {
                $count['hr']++;
            }
            // 打点
            if ($seasonFielderHistory->daten_rank == '<b>(1)</b>') {
                $count['daten']++;
            }
            // 最多安打
            if ($seasonFielderHistory->hit_rank == '<b>(1)</b>') {
                $count['hit']++;
            }
            // 盗塁王
            if ($seasonFielderHistory->steal_success_rank == '<b>(1)</b>') {
                $count['steal']++;
            }
            // 規定打席到達
            if ($seasonFielderHistory->daseki >= $seasonFielderHistory->team->game * 3.1) {
                $count['kitei_daseki']++;
            }
            // 3割
            if (
                $seasonFielderHistory->daseki >= $seasonFielderHistory->team->game * 3.1 &&
                $seasonFielderHistory->avg >= 0.3
            ) {
                $count['avg_3wari']++;
            }
            // 30本
            if ($seasonFielderHistory->hr >= 30) {
                $count['hr_30']++;
            }
            // 100打点
            if ($seasonFielderHistory->daten >= 100) {
                $count['daten_100']++;
            }
            // 30盗塁
            if ($seasonFielderHistory->steal_success >= 30) {
                $count['steal_30']++;
            }
        }
        foreach ($seasonPitcherHistories as $seasonPitcherHistory) {

            // 最優秀防御率
            if ($seasonPitcherHistory->p_era_rank == '<b>(1)</b>') {
                $count['era']++;
            }
            // 最多勝
            if ($seasonPitcherHistory->p_win_rank == '<b>(1)</b>') {
                $count['win']++;
            }
            // 最高勝率
            if ($seasonPitcherHistory->p_win_ratio_rank == '<b>(1)</b>') {
                $count['win_ratio']++;
            }
            // 最多奪三振
            if ($seasonPitcherHistory->p_sansin_rank == '<b>(1)</b>') {
                $count['sansin']++;
            }
            // 最優秀中継ぎ投手
            if ($seasonPitcherHistory->p_hold_rank == '<b>(1)</b>') {
                $count['hold']++;
            }
            // 最優秀救援投手
            if ($seasonPitcherHistory->p_save_rank == '<b>(1)</b>') {
                $count['save']++;
            }
            // 規定投球回数
            if ($seasonPitcherHistory->p_inning >= $seasonPitcherHistory->team->game * 3) {
                $count['kitei_tokyu']++;
            }
            // 50試合以上
            if ($seasonPitcherHistory->p_game >= 50) {
                $count['game_50']++;
            }
            // 10勝
            if ($seasonPitcherHistory->p_win >= 10) {
                $count['win_10']++;
            }
            // 防御率1点台
            if ($seasonPitcherHistory->p_inning >= $seasonPitcherHistory->team->game * 3 && $seasonPitcherHistory->p_era < 2) {
                $count['era_1ten']++;
            }
            // 30ホールド
            if ($seasonPitcherHistory->p_hold >= 30) {
                $count['hold_30']++;
            }
            // 30セーブ
            if ($seasonPitcherHistory->p_save >= 30) {
                $count['save_30']++;
            }

        }

//         dump($seasonFielderHistories->toArray());
//         dump($seasonPitcherHistories);
// exit;
        // dump($count);
        // exit;
         return [
            'player' => $player,
            'monthly_fielder_infos' => $playModel->getMonthlyFielder($player),
            'monthly_pitcher_infos' => $gamePitcherModel->getMothryPitcher($player),
            'fielder_histories' => $playModel->getFielderHistory($player),
            'pitcher_histories' => $gamePitcherModel->getPitcherHistory($player),
            'season_fielder_histories' => $seasonFielderHistories,
            'season_pitcher_histories' => $seasonPitcherHistories,
            'count' => $count,
         ];
     }

    public function getOptions(Season $season)
    {
        $playerModel = new Player();
        return $playerModel
            ->select('players.id as value', \DB::raw('\'[\' || teams.ryaku_name || \']\' || \'[\' || players.number || \']\' || players.name as text'))
            ->join('teams', 'teams.id', '=', 'players.team_id')
            ->where('teams.season_id', $season->id)
            ->orderBy('teams.id', 'ASC')
            ->orderBy(\DB::raw('players.number::integer'), 'ASC')
            ->get();
    }

    public function trade(PlayerTradeRequest $request)
    {
        (new Player())->trade($request->all());
    }

}
