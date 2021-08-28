<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GamePinchHitterRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pinch_hitter_id' => 'required|integer',
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
            'pinch_hitter_id' => '代打',
        ];
    }

    /**
     * エラーメッセージ
     *
     * @return array
     */
    public function messages() {
        return [
            'pinch_hitter_id.required' => ':attributeを入力してください',
        ];
    }
}
