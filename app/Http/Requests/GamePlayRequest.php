<?php

namespace App\Http\Requests;

use App\Models\Game;
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
            'out' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $gameInfo = Game::find($this->game_id);
                    if ($gameInfo->out + $value > 3) {
                        $fail('3アウトを超えています');
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
