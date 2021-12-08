<?php

namespace Modules\Education\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassScheduleRequest extends FormRequest
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
            'room_id' => 'required|numeric',
            'group_id' => 'required|numeric',
            'monday_from' => 'required_if:monday,true',
            'monday_to' => 'required_if:monday,true',
            'tuesday_from' => 'required_if:tuesday,true',
            'tuesday_to' => 'required_if:tuesday,true',
            'wednesday_from' => 'required_if:wednesday,true',
            'wednesday_to' => 'required_if:wednesday,true',
            'thursday_from' => 'required_if:thursday,true',
            'thursday_to' => 'required_if:thursday,true',
            'friday_from' => 'required_if:friday,true',
            'friday_to' => 'required_if:friday,true',
            'saturday_from' => 'required_if:saturday,true',
            'saturday_to' => 'required_if:saturday,true',
            'sunday_from' => 'required_if:sunday,true',
            'sunday_to' => 'required_if:sunday,true',
        ];
    }
}