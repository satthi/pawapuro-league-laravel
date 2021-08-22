<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class GameAutoRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if ((new Carbon($value))->format('w') != 5) {
                        $fail('金曜日を指定してください');
                    }
                },
            ],
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
            'start_date' => '試合日',
        ];
    }

    /**
     * エラーメッセージ
     *
     * @return array
     */
    public function messages() {
        return [
            'start_date.required' => ':attributeを入力してください',
            'start_date.date' => ':attributeを正しく入力してください',
        ];
    }
}
