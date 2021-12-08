<?php

namespace Modules\Education\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
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
            'description' => 'string|max:200',
            'experience' => 'string|max:200',
            'user.id' => 'sometimes',
            'user.full_name' => 'required',
            'user.email' => 'required',
            'user.address' => 'nullable|string|max:250',
            'user.phone' => 'nullable|numeric',
            'user.phone_additional' => 'nullable|numeric',
            'user.description' => 'nullable|string',
            'user.image' => 'nullable|file|max:5120',
            'user.status' =>  'nullable|in:active,disactive',
            'user.gender' =>  'nullable|in:male,female',
            'user.birth_date' => 'nullable|date',
        ];
    }
}