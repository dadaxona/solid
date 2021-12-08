<?php

namespace Modules\CRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DealRequest extends FormRequest
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
            'code' => 'sometimes|max:100',
            'status' => 'required',
            'warehouse_id' => 'required',
            'products' => 'array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.pivot.quantity' => 'required|min:1',
            'products.*.pivot.discount' => 'nullable',
            'products.*.pivot.paid_price' => 'nullable',
            'payment_term' => 'numeric',
            'created_by' => 'sometimes|numeric',
            'created_by_conclusion' => 'sometimes|string',
            'monitored_by' => 'sometimes|numeric',
            'monitored_at' => 'sometimes',
            'committee_member' => 'sometimes|numeric',
            'committee_conclusion' => 'sometimes',
            'committee_date' => 'sometimes',
            'client.id' => 'sometimes',
            'client.spouse' => 'sometimes|string',
            'client.spouse_work' => 'sometimes|string',
            'client.children_count' => 'sometimes|numeric',
            'client.family_member_count' => 'sometimes|numeric',
            'client.main_family_expense' => 'sometimes|numeric',
            'client.home_type' => 'sometimes|string',
            'client.home_owning' => 'sometimes|string',
            'client.user.id' => 'sometimes',
            'client.user.full_name' => 'required',
            'client.user.email' => 'required',
            'client.user.roles' => 'sometimes|array',
            'client.user.roles.*' => 'numeric',
            'client.user.address_registred' => 'nullable|string|max:250',
            'client.user.address_living' => 'nullable|string|max:250',
            'client.user.estimated_address' => 'nullable|string|max:250',
            'client.user.phone' => 'nullable|numeric',
            'client.user.phone_additional' => 'nullable|numeric',
            'client.user.description' => 'nullable|string',
            'client.user.image' => 'max:5120',
            'client.user.status' =>  'nullable|in:active,disactive',
            'client.user.gender' =>  'nullable|in:male,female',
            'client.user.education_degree' =>  'nullable|in:high,middle,low',
            'client.user.marriage' =>  'nullable|in:yes,no',
            'client.user.birth_date' => 'nullable|date',
            'client.user.department_id' => 'request|nullable',
            'client.client_family_members.*.id' => 'sometimes',
            'client.client_family_members.*.full_name' => 'required|string',
            'client.client_family_members.*.relation_type' => 'required|string',
            'client.client_family_members.*.work' => 'required|string',
            'client.client_family_members.*.work_address' => 'required|string',
            'client.client_family_members.*.salary' => 'required|string',
        
        ];
    }
}