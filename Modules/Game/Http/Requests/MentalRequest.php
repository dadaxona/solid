<?php

namespace Modules\Game\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MentalRequest extends FormRequest
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
        // protected $fillable = ['numbers','answers','result', 'user_id', 'limit', 'number_of_tasks', 'number_delay', 'interval', 'room', 'type', 'lesson_id'];

        return [
            'id' => 'sometimes',
            'answers'=> 'sometimes|array',
            'room' => 'sometimes|integer',
            'lesson_id' => 'sometimes|integer',
            'limit' => 'sometimes|integer',
            'type' => 'sometimes',
            'number_of_tasks' => 'sometimes',
            'number_delay' => 'sometimes',
            'interval' => 'sometimes|sometimes',
            'game_type' => 'sometimes|in:flash-card,train',
            'group_mode' => 'sometimes',
            'same_numbers' => 'boolean',
            'dificulty' => 'array'
        ];
    }
}