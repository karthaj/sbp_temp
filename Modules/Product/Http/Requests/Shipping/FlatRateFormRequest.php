<?php

namespace Modules\Product\Http\Requests\Shipping;

use Illuminate\Foundation\Http\FormRequest;

class FlatRateFormRequest extends FormRequest
{

    protected function validationData()
    {
        if($this->shipping_rate) {
            $rate = explode(',', $this->shipping_rate);
            $rate = array_filter($rate);
            $rate = implode('', $rate);
            $rate = (float) $rate;
            $this->merge(['shipping_rate' => $rate]);
        }
        
        return $this->all();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'display_name' => 'required|max:255',
            'shipping_rate' => 'required|numeric',
            'charge_type' => 'required|numeric',
            'flat_rate_email' => 'required|email|max:255'
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
            'display_name.required' => 'Display name is required.',
            'shipping_rate.required' => 'Cost is required.',
            'charge_type.required_if'  => 'Please choose how to charge.',
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
