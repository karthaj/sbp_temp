<?php

namespace Modules\Product\Http\Requests\Shipping;

use Illuminate\Foundation\Http\FormRequest;

class ShippingMethodRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'free_shipping' => 'nullable|numeric',
            'flat_rate' => 'nullable|numeric',
            'ship_weight_order' => 'nullable|numeric',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'free_shipping.numeric' => 'Free shipping status must be numeric',
            'flat_rate.numeric' => 'Free shipping status must be numeric',
            'ship_weight_order.numeric' => 'Free shipping status must be numeric',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
