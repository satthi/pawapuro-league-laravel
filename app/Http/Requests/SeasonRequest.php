<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeasonRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'regular_flag' => 'boolean',
            'selected_teams' => 'array|min:2',
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
            'name' => 'シーズン名',
            'regular_flag' => 'レギュラーシーズンフラグ',
            'selected_teams' => '参加チーム',
        ];
    }

    /**
     * エラーメッセージ
     *
     * @return array
     */
    public function messages() {
        return [
            'name.required' => ':attributeを入力してください',
            'selected_teams.required' => ':attributeを入力してください',
            'selected_teams.array' => ':attributeを正しく入力してください',
            'selected_teams.min' => ':attributeを二つ以上選択してください',
        ];
    }
}
