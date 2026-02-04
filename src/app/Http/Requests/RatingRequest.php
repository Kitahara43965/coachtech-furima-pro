<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RatingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'rating_value' => ['required','integer','min:1','max:5'],
        ];
    }

    public function messages()
    {
        return [
            'rating_value.required' => '評価して下さい。',
            'rating_value.integer' => '整数値を入力して下さい。',
            'rating_value.min' => '評価は星1から5個までで評価してください。',
            'rating_value.max' => '評価は星1から5個までで評価してください。',
        ];
    }
}
