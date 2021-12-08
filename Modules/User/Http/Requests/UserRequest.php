<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'full_name' => 'required',
            'email' => 'required',
            'roles' => 'sometimes|array',
            'roles.*' => 'numeric',
            'address_registred' => 'nullable|string|max:250',
            'address_living' => 'nullable|string|max:250',
            'estimated_address' => 'nullable|string|max:250',
            'phone' => 'nullable|numeric',
            'phone_additional' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image' => 'max:5120',
            'status' =>  'nullable|in:active,disactive',
            'gender' =>  'nullable|in:male,female',
            'education_degree' =>  'nullable|in:high,middle,low',
            'marriage' =>  'nullable|in:yes,no',
            'birth_date' => 'nullable|date',
            'department_id' => 'request|nullable',
        ];
    }
}