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
            'start_date' => 'required|date',
            'regular_flag' => 'boolean',
            'game_count' => 'required|integer',
            'selected_teams' => 'array|min:1',
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
            'start_date' => '開始日',
            'regular_flag' => 'レギュラーシーズンフラグ',
            'game_count' => 'ゲーム数',
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
            'start_date.required' => ':attributeを入力してください',
            'start_date.date' => ':attributeを正しく入力してください',
            'game_count.required' => ':attributeを入力してください',
            'game_count.integer' => ':attributeを正しく入力してください',
            'selected_teams.required' => ':attributeを入力してください',
            'selected_teams.array' => ':attributeを正しく入力してください',
            'selected_teams.min' => ':attributeを一つ以上選択してください',
        ];
    }
}
