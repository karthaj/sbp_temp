<?php

namespace Shopbox\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ShippingFormRequest extends FormRequest
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
            'shipping_id' => 'required|numeric|consignment_exists'
        ];
    }

    public function messages()
    {
        return [
            'shipping_id.consignment_exists' => 'Shipping id not found.'
        ];
    }
}
