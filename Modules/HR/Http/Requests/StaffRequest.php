<?php

namespace Modules\HR\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'sometimes',
            'came_at' => 'required',
            'work_book' => 'required',
            'study_degree' => 'required',
            'specialization'=>'required',
            'experience' => 'required',
            'staffposition_id'=> 'required',
            'user_id' => 'required'
        ];
    }

   
}
