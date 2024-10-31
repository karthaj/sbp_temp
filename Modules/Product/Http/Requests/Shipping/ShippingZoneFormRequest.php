<?php

namespace Modules\Product\Http\Requests\Shipping;

use Shopbox\Tenant\Manager;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ShippingZoneFormRequest extends FormRequest
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
            'zone_name' => 'required|max:191|unique_to_store:shipping_zones,zone_name,'.$this->shipping_zone,
            'zone_type' => 'required|'.Rule::in(['country','state','zip_code']),
            'zone_country' => 'required_if:zone_type,country',
            'zip_country' => 'required_if:zone_type,zip_code',
            'zip_code' => 'required_if:zone_type,zip_code',
            'state_country' => 'required_if:zone_type,state',
            'states' => 'required_if:zone_type,state'

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
            'zone_name.required' => 'Please enter a name for this shipping zone.',
            'zone_name.unique_to_store' => 'Zone already exists.',
            'zone_type.required' => 'Please select a zone type.',
            'zone_country.required_if'  => 'Please select one or more countries.',
            'zip_country.required_if'  => 'Country is required.',
            'zip_code.required_if' => 'Please enter one or more ZIP/postal codes.',
            'state_country.required_if' => 'Please select one or more countries.',
            'states.required_if' => 'Please select one or more states.'
        ];
    }

}
