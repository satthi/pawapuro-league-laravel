<?php

namespace App\Http\Requests;

use App\Models\Game;
use App\Models\Result;
use Illuminate\Foundation\Http\FormRequest;

class GamePlayRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'selectedResult' => [
                'required'
            ],
            'now_player_id' => [
                'required',
                'integer',
            ],
            'now_pitcher_id' => [
                'required',
                'integer',
            ],
            'out' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $gameInfo = Game::find($this->game_id);
                    if ($gameInfo->out + $value > 3) {
                        $fail('3アウトを超えています');
                    }
                },
            ],
            'point' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $result = Result::find($this->selectedResult);
                    if (!is_null($result) && $result->point_require_flag && $value == 0) {
                        $fail('得点の入力が必要です');
                    }
                },
            ]
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
            'selectedResult' => '打撃結果',
        ];
    }

    /**
     * エラーメッセージ
     *
     * @return array
     */
    public function messages() {
        return [
            'selectedResult.required' => ':attributeが未選択です',
        ];
    }
}
