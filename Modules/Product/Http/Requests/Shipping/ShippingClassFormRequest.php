<?php

namespace Modules\Product\Http\Requests\Shipping;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ShippingClassFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255|unique_to_store:shipping_classes,name,'.$this->shipping_class,
            'shipping_zones' => 'required',
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
            'name.required' => 'Please enter a name for this shipping class.',
            'name.unique_to_store' => 'Class name has already been taken.',
            'shipping_zones.required' => 'Please select one or more shipping zone.',
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
