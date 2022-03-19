<?php

namespace App\Http\Requests;

use App\Enums\Kiki;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlayerTradeRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'number' => [
                'required',
                'regex:/^[0-9]+$/i',
            ],
            'team_id' => 'required|integer',
            'player_id' => 'required|integer',
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
            'number' => '背番号',
            'team_id' => 'チームID',
            'player_id' => '選手ID',
        ];
    }

    /**
     * エラーメッセージ
     *
     * @return array
     */
    public function messages() {
        return [
            'number.required' => ':attributeを入力してください',
            'number.regex' => ':attributeは数値で入力してください',
            'number.unique' => ':attributeはすでに入力済みです',
            'team_id.required' => ':attributeを入力してください',
            'player_id.required' => ':attributeを入力してください',
        ];
    }

}
