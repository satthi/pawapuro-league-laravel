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
            'team_id_1' => [
                'required',
                'integer',
            ],
            'team_id_2' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    
                    if ($this->team_id_1 == $value) {
                        $fail('チームが重複しています');
                    }
                },
            ],
            'team_id_3' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    
                    if ($this->team_id_1 == $value || $this->team_id_2 == $value) {
                        $fail('チームが重複しています');
                    }
                },
            ],
            'team_id_4' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    
                    if ($this->team_id_1 == $value || $this->team_id_2 == $value || $this->team_id_3 == $value) {
                        $fail('チームが重複しています');
                    }
                },
            ],
            'team_id_5' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    
                    if ($this->team_id_1 == $value || $this->team_id_2 == $value || $this->team_id_3 == $value || $this->team_id_4 == $value) {
                        $fail('チームが重複しています');
                    }
                },
            ],
            'team_id_6' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    
                    if ($this->team_id_1 == $value || $this->team_id_2 == $value || $this->team_id_3 == $value || $this->team_id_4 == $value || $this->team_id_5 == $value) {
                        $fail('チームが重複しています');
                    }
                },
            ],
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
            'team_id_1' => '1位チーム',
            'team_id_2' => '1位チーム',
            'team_id_3' => '1位チーム',
            'team_id_4' => '1位チーム',
            'team_id_5' => '1位チーム',
            'team_id_6' => '1位チーム',
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
            'team_id_1.required' => ':attributeを入力してください',
            'team_id_1.integer' => ':attributeを正しく入力してください',
            'team_id_2.required' => ':attributeを入力してください',
            'team_id_2.integer' => ':attributeを正しく入力してください',
            'team_id_3.required' => ':attributeを入力してください',
            'team_id_3.integer' => ':attributeを正しく入力してください',
            'team_id_4.required' => ':attributeを入力してください',
            'team_id_4.integer' => ':attributeを正しく入力してください',
            'team_id_5.required' => ':attributeを入力してください',
            'team_id_5.integer' => ':attributeを正しく入力してください',
            'team_id_6.required' => ':attributeを入力してください',
            'team_id_6.integer' => ':attributeを正しく入力してください',
        ];
    }
}
