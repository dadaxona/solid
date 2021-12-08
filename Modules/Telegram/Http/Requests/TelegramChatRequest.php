<?php

namespace Modules\Telegram\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TelegramChatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'sometimes',
            'chat_id' => 'sometimes',
            'state' => 'sometimes',
            'job_application_id' => 'job_application_id',
            'language' => 'sometimes',
            'telegram_bot_id' => 'sometimes',
            'is_admin_chat' => 'sometimes'
        ];
    }
}