<?php

namespace Modules\WMS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required|string',
            'expiry_day' => 'required|date',
            'price' => 'numeric|required',
            'description' => 'nullable',
            'unit_id' => 'nullable',
            'product_category_ids' => 'sometimes|array'
        ];
    }
}