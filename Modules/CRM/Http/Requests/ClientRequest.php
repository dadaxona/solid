<?php

namespace Modules\CRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'spouse' => 'sometimes|string',
            'spouse_work' => 'sometimes|string',
            'children_count' => 'sometimes|numeric',
            'family_member_count' => 'sometimes|numeric',
            'main_family_expense' => 'sometimes|numeric',
            'home_type' => 'sometimes|string',
            'home_owning' => 'sometimes|string',
            'user.id' => 'sometimes',
            'user.full_name' => 'required',
            'user.email' => 'required',
            'user.roles' => 'sometimes|array',
            'user.roles.*' => 'numeric',
            'user.address_registred' => 'nullable|string|max:250',
            'user.address_living' => 'nullable|string|max:250',
            'user.estimated_address' => 'nullable|string|max:250',
            'user.phone' => 'nullable|numeric',
            'user.phone_additional' => 'nullable|numeric',
            'user.description' => 'nullable|string',
            'user.image' => 'max:5120',
            'user.status' =>  'nullable|in:active,disactive',
            'user.gender' =>  'nullable|in:male,female',
            'user.education_degree' =>  'nullable|in:high,middle,low',
            'user.marriage' =>  'nullable|in:yes,no',
            'user.birth_date' => 'nullable|date',
            'user.department_id' => 'request|nullable',
            
            'client_family_members.*.id' => 'sometimes',
            'client_family_members.*.full_name' => 'required|string',
            'client_family_members.*.relation_type' => 'required|string',
            'client_family_members.*.work' => 'required|string',
            'client_family_members.*.work_address' => 'required|string',
            'client_family_members.*.salary' => 'required|string',
        ];
    }
}