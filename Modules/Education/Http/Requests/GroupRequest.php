<?php

namespace Modules\Education\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
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
            'name' => 'required|string|max:200',
            'course_id' => 'nullable',
            'room_id' => 'nullable',
            'student_ids' => 'array'
        ];
    }
}