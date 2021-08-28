<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GamePinchRunnerRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'base_runner_id' => 'required|integer',
            'pinch_runner_id' => 'required|integer',
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
            'base_runner_id' => '代走元',
            'pinch_runner_id' => '代走',
        ];
    }

    /**
     * エラーメッセージ
     *
     * @return array
     */
    public function messages() {
        return [
            'base_runner_id.required' => ':attributeを入力してください',
            'pinch_runner_id.required' => ':attributeを入力してください',
        ];
    }
}
