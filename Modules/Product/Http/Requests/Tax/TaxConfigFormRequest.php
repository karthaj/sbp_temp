<?php

namespace Modules\Product\Http\Requests\Tax;

use Illuminate\Foundation\Http\FormRequest;

class TaxConfigFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tax_label' => 'required|max:191',
            'price_tax' => 'numeric',
            'calculate_tax' => 'string',
            'shipping_tax' => 'numeric',
            'charge_tax' => 'numeric',
            'product_listing_tax' => 'numeric',
            'product_page_tax' => 'numeric',
            'cart_tax' => 'numeric',
            'cart_charge' => 'numeric',
            'order_invoice_tax' => 'numeric',
            'order_tax' => 'numeric',
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
