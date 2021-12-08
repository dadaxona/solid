<?php

namespace Modules\CRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientFamilyMemberRequest extends FormRequest
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
            'client_id' => 'required|integer',
            'full_name' => 'required|string',
            'relation_type' => 'required|string',
            'work' => 'required|string',
            'work_address' => 'required|string',
            'salary' => 'required|string',
        ];
    }
    
}