<?php

namespace App\Http\Requests;

use App\Models\Game;
use App\Models\Play;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class GameEndRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'pitcherResult' => [
                'required',
                // win入力確認
                function ($attribute, $value, $fail) {
                    $gameInfo = Game::find($this->game_id);
                    if (
                        $gameInfo->home_point != $gameInfo->visitor_point &&
                        empty($value['win'])
                    ) {
                        $fail('勝が未選択です');
                    }
                },
                // lose
                function ($attribute, $value, $fail) {
                    $gameInfo = Game::find($this->game_id);
                    if (
                        $gameInfo->home_point != $gameInfo->visitor_point &&
                        empty($value['lose'])
                    ) {
                        $fail('負けが未選択です');
                    }
                },
                // 自責点入力確認
                function ($attribute, $value, $fail) {
                    $gameInfo = Game::find($this->game_id);
                    $playModel = new Play();
                    $pitcherInfo = $playModel->getPitcherInfo($gameInfo);
                    foreach ($pitcherInfo['visitor_team'] as $p) {
                        if (
                            !array_key_exists($p['player']['id'], $value['jiseki']) ||
                            $value['jiseki'][$p['player']['id']] === ''
                        ) {
                            $fail($p['player']['name'] . 'の自責点が未選択です');
                        } elseif (!preg_match('/^[0-9]{1,2}$/', $value['jiseki'][$p['player']['id']])) {
                            $fail($p['player']['name'] . 'の自責点を数値で入力してください');
                        }

                    }
                },
            ],
        ];
    }


    /**
     * 項目名
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'start_date' => '試合日',
            'team_id_1' => '1位チーム',
            'team_id_2' => '2位チーム',
            'team_id_3' => '3位チーム',
            'team_id_4' => '4位チーム',
            'team_id_5' => '5位チーム',
            'team_id_6' => '6位チーム',
        ];
    }

    /**
     * エラーメッセージ
     *
     * @return array
     */
    public function messages() {
        return [
            'start_date.required' => ':attributeを入力してください',
            'start_date.date' => ':attributeを正しく入力してください',
            'team_id_1.required' => ':attributeを入力してください',
            'team_id_1.integer' => ':attributeを正しく入力してください',
            'team_id_2.required' => ':attributeを入力してください',
            'team_id_2.integer' => ':attributeを正しく入力してください',
            'team_id_3.required' => ':attributeを入力してください',
            'team_id_3.integer' => ':attributeを正しく入力してください',
            'team_id_4.required' => ':attributeを入力してください',
            'team_id_4.integer' => ':attributeを正しく入力してください',
            'team_id_5.required' => ':attributeを入力してください',
            'team_id_5.integer' => ':attributeを正しく入力してください',
            'team_id_6.required' => ':attributeを入力してください',
            'team_id_6.integer' => ':attributeを正しく入力してください',
        ];
    }
}
