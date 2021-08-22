<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameProbablePitcherRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'visitor_probable_pitcher_id' => 'required|integer',
            'home_probable_pitcher_id' => 'required|integer',
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
            'visitor_probable_pitcher_id' => 'ビジター 予告先発',
            'home_probable_pitcher_id' => 'ホーム 予告先発',
        ];
    }

    /**
     * エラーメッセージ
     *
     * @return array
     */
    public function messages() {
        return [
            'visitor_probable_pitcher_id.required' => ':attributeを入力してください',
            'visitor_probable_pitcher_id.integer' => ':attributeを正しく入力してください',
            'home_probable_pitcher_id.required' => ':attributeを入力してください',
            'home_probable_pitcher_id.integer' => ':attributeを正しく入力してください',
        ];
    }
}
