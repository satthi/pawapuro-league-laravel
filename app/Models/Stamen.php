<?php

namespace App\Models;

use App\Enums\Position;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Stamen extends Model
{
    use HasFactory;
    protected $fillable = [
        'game_id',
        'team_id',
        'dajun',
        'position',
        'player_id',
    ];
    protected $appends = [];

    /**
     * home team
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
    /**
     * home team
     */
    public function game()
    {
        return $this->belongsTo(Player::class, 'game_id');
    }

    public function getStartSeisekiAttribute($value)
    {
        $playerModel = new Player();
        $gameModel = new Game();
        $gamePitcherModel = new GamePitcher();
        // 試合前の成績取得
        // 該当のplayer_idでかつgame_idより前の日程の合算をする
        $player = $playerModel::find($this->player_id);
        $game = $gameModel::find($this->game_id);

        $gameSubDay = (new Carbon($game->date))->subDay()->format('Y/m/d');

        $dagekiSeiseki = $player->getTargetDateSeisekiInfo($gameSubDay);
        $pitcherSeiseki = $gamePitcherModel->getSeiseki($this->player_id, $gameSubDay);
        return [
            'dageki' => $dagekiSeiseki['target_avg'] . ' ' . $dagekiSeiseki['hr'] . '本 ' . $dagekiSeiseki['daten'] . '点 ',
            'pitcher' => $pitcherSeiseki['game_sum'] . '試' . $pitcherSeiseki['win_count'] . '勝' . $pitcherSeiseki['lose_count'] . '敗 ' . $pitcherSeiseki['era'],
        ];
    }

    public function getStamenInitialData(Game $game, string $stamenType)
    {
        $playerModel = new Player();
        $gamePitcherModel = new GamePitcher();

        $stamenInitialData = $this->getStamenInitialDataBase($game, $stamenType);
        $gameSubDay = (new Carbon($game->date))->subDay()->format('Y/m/d');

        foreach ($stamenInitialData['stamen'] as $stamenKey => $stamen) {
            $player = $playerModel::find($stamen['player']['id']);
            $dagekiSeiseki = $player->getTargetDateSeisekiInfo($gameSubDay);
            $pitcherSeiseki = $gamePitcherModel->getSeiseki($stamen['player']['id'], $gameSubDay);

            $stamenInitialData['stamen'][$stamenKey]['player']['seiseki'] = [
                'dageki' => $dagekiSeiseki['target_avg'] . ' ' . $dagekiSeiseki['hr'] . '本 ' . $dagekiSeiseki['daten'] . '点 ',
                'pitcher' => $pitcherSeiseki['game_sum'] . '試' . $pitcherSeiseki['win_count'] . '勝' . $pitcherSeiseki['lose_count'] . '敗 ' . $pitcherSeiseki['era'],
            ];
        }
        foreach ($stamenInitialData['hikae'] as $hikaeKey => $hikae) {
            $player = $playerModel::find($hikae['id']);
            $dagekiSeiseki = $player->getTargetDateSeisekiInfo($gameSubDay);
            $pitcherSeiseki = $gamePitcherModel->getSeiseki($hikae['id'], $gameSubDay);

            $stamenInitialData['hikae'][$hikaeKey]['seiseki'] = [
                'dageki' => $dagekiSeiseki['target_avg'] . ' ' . $dagekiSeiseki['hr'] . '本 ' . $dagekiSeiseki['daten'] . '点 ',
                'pitcher' => $pitcherSeiseki['game_sum'] . '試' . $pitcherSeiseki['win_count'] . '勝' . $pitcherSeiseki['lose_count'] . '敗 ' . $pitcherSeiseki['era'],
            ];
        }

        return $stamenInitialData;
    }

    public function getStamenInitialDataBase(Game $game, string $stamenType)
    {
        if ($stamenType == 'visitor') {
            $teamId = $game->visitor_team_id;
            $probablePitcherId = $game->visitor_probable_pitcher_id;
        } else if ($stamenType == 'home') {
            $teamId = $game->home_team_id;
            $probablePitcherId = $game->home_probable_pitcher_id;
        } else {
            // error.
        }

        // 現在のスタメンの編集ということで現在情報を取得
        $stamens = $this::where('game_id', $game->id)
            ->where('team_id', $teamId)
            ->with('player')
            ->orderBy('dajun', 'ASC')
            ->get();
        if (!$stamens->isEmpty()) {
            return $this->showStamenData($stamens, $teamId);
        }

        // 最新のスタメンの情報を取得してセット
        // 最新のスタメンがある場合はそちらを参照してセット
        $latestStamen = $this->where('team_id', $teamId)
            ->join('games', 'games.id', '=', 'stamens.game_id')
            ->orderBy('games.date', 'DESC')
            ->orderBy('games.id', 'DESC')
            ->first();

        if (!is_null($latestStamen)) {
            $stamens = $this::where('game_id', $latestStamen->game_id)
                ->where('team_id', $teamId)
                ->with('player')
                ->orderBy('dajun', 'ASC')
                ->get();
            return $this->stamenBeforelData($stamens, $game, $teamId, $probablePitcherId);
        }

        // 何もない時の挙動
        return $this->stamenInitialData($game, $teamId, $probablePitcherId);
    }

    public function showStamenData($stamens, $teamId) {
        if (empty($stamens)) {
            return [];
        }
        $values = Position::getValues();
        $positionOptions = [];
        foreach ($values as $value) {
            $positionOptions[$value] = [
                'value' => $value,
                'text' => Position::getDescription($value)
            ];
        }

        $stamenSeiri = [];
        $stamePlayerIds = [];
        foreach ($stamens as $stamen) {
            $stamePlayerIds[] = $stamen->player_id;
            $stamenSeiri[$stamen->dajun] = [
                'dajun' => $stamen->dajun == 10 ? 'P' : (string)$stamen->dajun,
                'position' => $positionOptions[$stamen->position],
                'player' => $stamen->player->toArray(),
                'seiseki' => $stamen->start_seiseki,
            ];
        }

        // 残りのデータをセットする(予告先発以外)
        $players = Player::where('team_id', $teamId)
            ->whereNotIn('id', $stamePlayerIds)
            ->orderBy('position_main', 'ASC')
            ->orderBy(\DB::raw('number::numeric'), 'ASC')
            ->get()
            ->toArray();

        $hikae = [];
        // position打順は10まであるので11からとする
        $hikaeKey = 11;
        foreach ($players as $playerKey => $player) {
            $hikae[$hikaeKey] = $player;
            $hikaeKey++;
        }

        return [
            'stamen' => $stamenSeiri,
            'hikae' => $hikae,
        ];
    }

    private function stamenBeforelData($stamens, Game $game, int $teamId, ?int $probablePitcherId)
    {
        // 予告先発者の取得
        if (!$probablePitcherId) {
            $probablePitcherId = 0;
        }
        $probablePitcher = Player::find($probablePitcherId);
        if (!is_null($probablePitcher)) {
            $probablePitcher = $probablePitcher->toArray();
        }

        $values = Position::getValues();
        $positionOptions = [];
        foreach ($values as $value) {
            $positionOptions[$value] = [
                'value' => $value,
                'text' => Position::getDescription($value)
            ];
        }
        $pitcherPosition = $positionOptions[Position::POSITION_P];


        // まずはスタメンを当てはめる
        $stamenSeiri = [];
        $stamePlayerIds = [];
        $dhExists = false;
        foreach ($stamens as $stamen) {
            // $stamePlayerIds[] = $stamen->player_id;
            // // ピッチャーとDHは考えることがある
            // if ($stamen->position == POSITION_P) {
            //     if ($stamen->dajun == 10 && !$game->dh_flag) {
            //         $dhPitcher = $stamen->player;
            //     }
            // } elseif ($stamen->position == POSITION_DH) {
            //     if (!$game->dh_flag) {
            //         // 今回DHがない時はここを投手とする
            //         $stamenSeiri[$stamen->dajun] = [
            //             'position' => Position::POSITION_P,
            //             'player' => null,
            //         ];
            //     }
            // }
            if ($stamen->position == Position::POSITION_DH) {
                $dhExists = true;
            }
            $stamenSeiri[$stamen->dajun] = [
                'dajun' => $stamen->dajun == 10 ? 'P' : (string)$stamen->dajun,
                'position' => $positionOptions[$stamen->position],
                'player' => $stamen->player->toArray(),
            ];
        }

        // ピッチャーについて予告先発が設定されているときは予告先発投手に変える
        if (!is_null($probablePitcher)) {
            foreach ($stamenSeiri as $dajun => $stamenSeiriParts) {
                if ($stamenSeiriParts['position']['value'] == Position::POSITION_P) {
                    $stamenSeiri[$dajun]['player'] = $probablePitcher;
                }
            }
        }

        // 前のスタメンDHがあるとき>ゲームがDHなし
        if ($dhExists && !$game->dh_flag) {
            $picherInfo = $stamenSeiri[10];
            unset($stamenSeiri[10]);
            foreach ($stamenSeiri as $dajun => $stamenSeiriParts) {
                if ($stamenSeiriParts['position']['value'] == Position::POSITION_DH) {
                    // ピッチャー情報をセット
                    $stamenSeiri[$dajun] = $picherInfo;
                    $stamenSeiri[$dajun]['dajun'] = $dajun;
                }
            }
        }
        // 前のスタメンDHがあるない>ゲームがDHあり
        if (!$dhExists && $game->dh_flag) {
            foreach ($stamenSeiri as $dajun => $stamenSeiriParts) {
                if ($stamenSeiriParts['position']['value'] == Position::POSITION_P) {
                    // ピッチャーを10番に移動
                    $stamenSeiri[10] = [
                        'dajun' => 'P',
                        'position' => $positionOptions[Position::POSITION_P],
                        'player' => $stamenSeiriParts['player'],
                    ];

                    // DHを設定
                    $stamenSeiri[$dajun] = [
                        'dajun' => (string)$dajun,
                        'position' => $positionOptions[Position::POSITION_DH],
                        'player' => null,
                    ];
                }
            }
        }

        $stamenPlayerIds = [];
        foreach ($stamenSeiri as $dajun => $stamenSeiriParts) {
            if (!is_null($stamenSeiriParts['player'])) {
                $stamenPlayerIds[] = $stamenSeiriParts['player']['id'];
            }
        }


        // dump($stamenSeiri);
        // exit;

        // DHがない時の対応
        // if (!$game->dh_flag) {
        //     // ピッチャーは不要
        //     unset($stamenSeiri[10]);
        // }




        // if ($game->dh_flag) {
        //     // ピッチャーが普通の打順から除去
        //     unset($positionOptions[Position::POSITION_P]);
        // } else {
        //     // DHが不要
        //     unset($positionOptions[Position::POSITION_DH]);
        // }

        // $stamen = [];
        // $dajun = 0;
        // foreach ($positionOptions as $positionKey => $positionOption) {
        //     $dajun++;
        //     $stamen[$dajun] = [
        //         'position' => $positionOption,
        //         'player' => $positionKey === Position::POSITION_P ? $probablePitcher : null,
        //     ];
        // }

        // if ($game->dh_flag) {
        //     // DHの時はピッチャー情報をセット
        //     $stamen[10] = [
        //         'position' => $pitcherPosition,
        //         'player' => $probablePitcher,
        //     ];
        // }


        // // 枠を準備するところまではInitialと同様
        //         dump($stamens);
        // exit;


        // 残りのデータをセットする(予告先発以外)
        $players = Player::where('team_id', $teamId)
            ->orderBy('position_main', 'ASC')
            ->orderBy(\DB::raw('number::numeric'), 'ASC')
            ->whereNotIn('id', $stamenPlayerIds)
            ->get()
            ->toArray();

        foreach ($stamenSeiri as $dajun => $stamenSeiriParts) {
            if (is_null($stamenSeiriParts['player'])) {
                $player = array_shift($players);
                $stamenSeiri[$dajun]['player'] = $player;
            }
        }


        $hikae = [];
        // position打順は10まであるので11からとする
        $hikaeKey = 11;
        foreach ($players as $playerKey => $player) {
            $hikae[$hikaeKey] = $player;
            $hikaeKey++;
        }

        return [
            'stamen' => $stamenSeiri,
            'hikae' => $hikae,
        ];
    }

    private function stamenInitialData(Game $game, int $teamId, ?int $probablePitcherId)
    {
        // 予告先発者の取得
        if (!$probablePitcherId) {
            $probablePitcherId = 0;
        }
        $probablePitcher = Player::find($probablePitcherId);
        if (!is_null($probablePitcher)) {
            $probablePitcher = $probablePitcher->toArray();
        }

        $values = Position::getValues();
        $positionOptions = [];
        foreach ($values as $value) {
            $positionOptions[$value] = [
                'value' => $value,
                'text' => Position::getDescription($value)
            ];
        }
        $pitcherPosition = $positionOptions[Position::POSITION_P];

        unset($positionOptions[Position::POSITION_PH]);
        unset($positionOptions[Position::POSITION_PR]);
        if ($game->dh_flag) {
            // ピッチャーが普通の打順から除去
            unset($positionOptions[Position::POSITION_P]);
        } else {
            // DHが不要
            unset($positionOptions[Position::POSITION_DH]);
        }

        $stamen = [];
        $dajun = 0;
        foreach ($positionOptions as $positionKey => $positionOption) {
            $dajun++;
            $stamen[$dajun] = [
                'dajun' => (string)$dajun,
                'position' => $positionOption,
                'player' => $positionKey === Position::POSITION_P ? $probablePitcher : null,
            ];
        }

        if ($game->dh_flag) {
            // DHの時はピッチャー情報をセット
            $stamen[10] = [
                'dajun' => 'P',
                'position' => $pitcherPosition,
                'player' => $probablePitcher,
            ];
        }

        // 残りのデータをセットする(予告先発以外)
        $players = Player::where('team_id', $teamId)
            ->orderBy('position_main', 'ASC')
            ->orderBy(\DB::raw('number::numeric'), 'ASC')
            ->whereNotIn('id', [$probablePitcherId])
            ->get()
            ->toArray();

        foreach ($stamen as $dajun => $stamenParts) {
            if (is_null($stamenParts['player'])) {
                $player = array_shift($players);
                $stamen[$dajun]['player'] = $player;
            }
        }

        $hikae = [];
        // position打順は10まであるので11からとする
        $hikaeKey = 11;
        foreach ($players as $playerKey => $player) {
            $hikae[$hikaeKey] = $player;
            $hikaeKey++;
        }

        return [
            'stamen' => $stamen,
            'hikae' => $hikae,
        ];
    }

    public function setStamen(array $requestData, Game $game, string $stamenType)
    {
        if ($stamenType == 'visitor') {
            $teamId = $game->visitor_team_id;
        } else if ($stamenType == 'home') {
            $teamId = $game->home_team_id;
        } else {
            // error.
        }

        // 元のスタメンデータがあれば削除する
        $this::where('game_id', $game->id)
            ->where('team_id', $teamId)
            ->delete();

        // スタメンデータの保存
        foreach ($requestData['stamen'] as $dajun => $stamen) {
            $this::create([
                'game_id' => $game->id,
                'team_id' => $teamId,
                'dajun' => $dajun,
                'position' => $stamen['position']['value'],
                'player_id' => $stamen['player']['id'],
            ]);
        }
    }

    public function getStamen(Game $game)
    {
        // home
        $homeTeamId = $game->home_team_id;
        // 現在のスタメンの編集ということで現在情報を取得
        $homeStamens = $this->where('game_id', $game->id)
            ->where('team_id', $homeTeamId)
            ->with('player')
            ->orderBy('dajun', 'ASC')
            ->get();

        foreach ($homeStamens as $homeStamen) {
            $homeStamen->append('seiseki');
        }

        // visitor
        $visitorTeamId = $game->visitor_team_id;
        // 現在のスタメンの編集ということで現在情報を取得
        $visitorStamens = $this::where('game_id', $game->id)
            ->where('team_id', $visitorTeamId)
            ->with('player')
            ->orderBy('dajun', 'ASC')
            ->get();

        foreach ($visitorStamens as $visitorStamen) {
            $visitorStamen->append('seiseki');
        }

        return [
            'home_team' => $this->showStamenData($homeStamens, $homeTeamId),
            'visitor_team' => $this->showStamenData($visitorStamens, $visitorTeamId),
        ];
        
    }
}
