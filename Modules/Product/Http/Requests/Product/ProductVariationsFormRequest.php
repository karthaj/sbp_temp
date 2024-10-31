<?php

namespace Modules\Product\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductVariationsFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'variation.0.cost_price' => 'nullable|numeric',
            'variation.0.selling_price' => 'nullable|numeric',
            'variation.0.special_price' => 'nullable|numeric',
            'variation.0.special_active_date' => 'nullable|required_with:variation.0.special_active_time,variation.0.special_end_date,variation.0.special_end_time',
            'variation.0.special_active_time' => 'nullable|required_with:variation.0.special_active_date,variation.0.special_end_date,variation.0.special_end_time',
            'variation.0.special_end_date' => 'nullable|required_with:variation.0.special_end_time,variation.0.special_active_date,variation.0.special_active_time',
            'variation.0.special_end_time' => 'nullable|required_with:variation.0.special_end_date,variation.0.special_active_date,variation.0.special_active_time',
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
           'variation.0.cost_price.numeric' => 'cost price must be numeric',
           'variation.0.selling_price.numeric' => 'selling price must be numeric',
           'variation.0.special_price.numeric' => 'special price must be numeric',
           'variation.0.special_active_date.required_with' => 'Required',
           'variation.0.special_active_time.required_with' => 'Required',
           'variation.0.special_end_date.required_with' => 'Required',
           'variation.0.special_end_time.required_with' => 'Required',
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
