<?php

namespace Modules\Telegram\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TelegramUserRequest extends FormRequest
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
            'user_id' => 'sometimes',
            'user_id' => 'sometimes',
            'name' => 'sometimes',
            'second_name' => 'sometimes',
            'full_name' => 'sometimes',
            'region_id' => 'sometimes',
            'direction_id' => 'sometimes',
            'birth_date' => 'sometimes',
            'registration_status' => 'sometimes',
            'is_admin' => 'sometimes'
        ];
    }
}