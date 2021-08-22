<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'season_id' => 'required|integer',
            'date' => 'required|date',
            'home_team_id' => 'required|integer',
            'visitor_team_id' => 'required|integer',
            'dh_flag' => 'boolean',
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
            'season_id' => 'シーズン',
            'date' => '試合日',
            'home_team_id' => 'ホーム',
            'visitor_team_id' => 'ビジター',
            'dh_flag' => 'DHフラグ',
        ];
    }

    /**
     * エラーメッセージ
     *
     * @return array
     */
    public function messages() {
        return [
            'season_id.required' => ':attributeを入力してください',
            'date.required' => ':attributeを入力してください',
            'date.date' => ':attributeを正しく入力してください',
            'home_team_id.required' => ':attributeを入力してください',
            'home_team_id.integer' => ':attributeを正しく入力してください',
            'visitor_team_id.required' => ':attributeを入力してください',
            'visitor_team_id.integer' => ':attributeを正しく入力してください',
            'dh_flag.boolean' => ':attributeを正しく入力してください',
        ];
    }
}
