<?php

namespace Modules\Product\Http\Requests\Shipping;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryRateFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'display_name' => 'required|max:191',
            'charge_shipping' => 'required|numeric',
            'ranges.*.from' => 'required|numeric',
            'ranges.*.to' => 'required|numeric',
            'ranges.*.cost' => 'required|numeric',
            'email' => 'nullable|email|max:255'
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
            'charge_shipping.required' => 'Charge shipping is required.',
            'ranges.*.from.required'  => 'Required.',
            'ranges.*.to.required'  => 'Required.',
            'ranges.*.cost.required'  => 'Cost required.',
            'ranges.*.from.numeric'  => 'From must be number.',
            'ranges.*.to.numeric'  => 'To must be number.',
            'ranges.*.cost.numeric'  => 'Cost must be number.',
            'email.email' => 'Invalid email.'
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
