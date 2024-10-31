<?php

namespace Shopbox\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class CartFormRequest extends FormRequest
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
            'product_id' => 'bail|required|numeric|product_exists|product_eligible',
            'attribute_id' => 'bail|nullable|numeric|product_exists:'.$this->product_id.'|required_if_variant:'.$this->product_id,
            'qty' => 'bail|required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'product_id.product_exists' => 'Product not found.',
            'product_id.product_eligible' => 'Product cannot be added to existing cart. Remove existing products to add it.',
            'attribute_id.product_exists' => 'Variant not found.',
            'attribute_id.required_if_variant' => 'Variant id is required.',
        ];
    }

}
