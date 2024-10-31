<?php

namespace Modules\Discount\Http\Requests\Discount;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DiscountFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'discount_code' => 'required|max:255|alpha_num',
            'discount_name' => 'required|max:255',
            'discount_type' => 'required|string',
            'discount_value' => 'required',
            'minimum_amount' => 'nullable',
            'quantity' => 'required|numeric',
            'quantity_per_customer' => 'nullable|numeric',
            'expiry_date' => 'nullable|date',
            'status' => 'nullable|numeric',
            'discount_condition' => 'required|string|'.Rule::in(['entire_order','specific_category','specific_product']),
            'customer_restriction' => 'required|string|'.Rule::in(['everyone','specific_group','specific_customer']),
            'group' => 'nullable|required_if:customer_restriction,specific_group|numeric',
            'customer' => 'nullable|required_if:customer_restriction,specific_customer|numeric',
            'countries' => 'nullable|array',
            'category' => 'nullable|required_if:discount_condition,specific_category|numeric',
            'product' => 'nullable|required_if:discount_condition,specific_product|numeric',
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
