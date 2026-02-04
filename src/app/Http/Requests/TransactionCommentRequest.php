<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\PreviewErrorService;
use App\Constants\PreviewErrorStatus;

class TransactionCommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            PreviewErrorStatus::TRANSACTION_COMMENT_NAME => ['required', 'max:400'],
        ];
    }

    public function messages(){
        return [
            PreviewErrorStatus::TRANSACTION_COMMENT_NAME.".required" => "文字を入力してください",
            PreviewErrorStatus::TRANSACTION_COMMENT_NAME.".max" => "本文は400文字以内で入力してください",
        ];
    }

}
