<?php

namespace Modules\Education\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
            'description' => 'required',
            'finished_at' => 'required',
            'certificate_number' => 'required',
            'user.id' => 'sometimes',
            'user.full_name' => 'required',
            'user.email' => 'required',
            'user.address' => 'nullable|string|max:250',
            'user.phone' => 'nullable|required',
            'user.phone_additional' => 'nullable|required',
            'user.description' => 'nullable|string',
            'user.image' => 'nullable|max:5120',
            'user.status' =>  'nullable|in:active,disactive',
            'user.gender' =>  'nullable|in:male,female',
            'user.birth_date' => 'nullable|date'
        ];
    }
}