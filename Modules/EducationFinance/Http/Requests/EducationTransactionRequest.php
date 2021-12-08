<?php

namespace Modules\EducationFinance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EducationTransactionRequest extends FormRequest
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
            'group_id' => 'required',
            'student_id' => 'required',
            'performed_at' => 'required',
            'amount' => 'required',
            'description' => 'nullable'
        ];
    }
}