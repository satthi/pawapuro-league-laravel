<?php

namespace App\Http\Requests;

use App\Enums\Kiki;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BasePlayerRequest extends FormRequest
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
                Rule::unique('base_players')->ignore($this->id)->where(function($query) {
                    $query->where('base_team_id', $this->input('base_team_id'));
                }),
            ],
            'name' => 'required',
            'name_short' => 'required',
            'hand_p' => [
                'required',
                'integer',
                Rule::in(Kiki::getValues()),
            ],
            'hand_b' => 'required|integer',
            'position_main' => 'required|integer',
            'position_sub1' => 'nullable|integer',
            'position_sub2' => 'nullable|integer',
            'position_sub3' => 'nullable|integer',
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
            'name' => '背番号',
            'name_short' => '選手名',
            'hand_p' => '投',
            'hand_b' => '打',
            'position_main' => 'メインポジション',
            'position_sub1' => 'サブポジション1',
            'position_sub2' => 'サブポジション2',
            'position_sub3' => 'サブポジション3',
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
            'name.required' => ':attributeを入力してください',
            'name_short.required' => ':attributeを入力してください',
            'hand_p.required' => ':attributeを入力してください',
            'hand_p.integer' => ':attributeは無効な入力です',
            'hand_p.in' => ':attributeは無効な入力です',
            'hand_p.integer' => ':attributeは無効な入力です',
            'hand_b.required' => ':attributeを入力してください',
            'position_main.required' => ':attributeを入力してください',
        ];
    }

}
