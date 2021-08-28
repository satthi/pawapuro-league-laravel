<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameStealRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'steal_player_id' => 'required|integer',
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
            'steal_player_id' => '盗塁選手',
        ];
    }

    /**
     * エラーメッセージ
     *
     * @return array
     */
    public function messages() {
        return [
            'steal_player_id.required' => ':attributeを入力してください',
        ];
    }
}
