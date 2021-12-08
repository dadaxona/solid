<?php

namespace Modules\WMS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductTransactionRequest extends FormRequest
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
            'wms_product_id' => 'nullable',
            'warehouse_id' => 'nullable',
            'quantity' => 'numeric|required'
        ];
    }
}