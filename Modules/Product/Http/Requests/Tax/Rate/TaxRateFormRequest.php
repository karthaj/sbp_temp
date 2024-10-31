<?php

namespace Modules\Product\Http\Requests\Tax\Rate;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TaxRateFormRequest extends FormRequest
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
            'tax_zone' => 'required|numeric',
            'tax_name' => 'required|max:191',
            'rates.*' => 'required|numeric|min:0',
            'calculation_order' => 'required|numeric|min:0'
        ];
    }

    public function messages()
    {
        return [
            'rates.*.required' => 'Rate is required.',
            'rates.*.numeric' => 'Rate must be numeric.',
            'rates.*.min' => 'Minimum rate must be 0.',
            'calculation_order.required' => 'Calculation order is required.',
            'calculation_order.numeric' => 'Calculation order must be numeric.',
            'calculation_order.min' => 'Minimum rate must be 0.',
        ];
    }
    
}
