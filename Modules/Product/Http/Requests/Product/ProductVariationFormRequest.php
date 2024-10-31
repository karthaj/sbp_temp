<?php

namespace Modules\Product\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductVariationFormRequest extends FormRequest
{

    protected function validationData()
    {
        return json_decode($this->variation, true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cost_price' => 'nullable|numeric',
            'selling_price' => 'nullable|numeric',
            'special_price' => 'nullable|numeric',
            'sku' => 'nullable:max:255',
            'barcode' => 'nullable:max:255',
            'isbn' => 'nullable:max:32',
            'upc' => 'nullable:max:12',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'depth' => 'nullable|numeric',
            'depth' => 'numeric',
            'special_active_date' => 'nullable|required_with:special_active_time,special_end_date,special_end_time',
            'special_active_time' => 'nullable|required_with:special_active_date,special_end_date,special_end_time',
            'special_end_date' => 'nullable|required_with:special_end_time,special_active_date,special_active_time',
            'special_end_time' => 'nullable|required_with:special_end_date,special_active_date,special_active_time',
            'image' => 'nullable|image'
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
           'special_active_date.required_with' => 'Required',
           'special_active_time.required_with' => 'Required',
           'special_end_date.required_with' => 'Required',
           'special_end_time.required_with' => 'Required',
           'image.image' => 'Variant image must be an image.',
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
