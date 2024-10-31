<?php

namespace Modules\Product\Http\Requests\tax\zone;

use Illuminate\Foundation\Http\FormRequest;

class ZoneFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'zone_name' => 'required|max:191|unique_to_store:tax_zones,name,'.$this->tax_zone,
            'zone_country' => 'required_if:zone_type,country',
            'zip_country' => 'required_if:zone_type,zip',
            'zip_code' => 'required_if:zone_type,zip',
            'state_country' => 'required_if:zone_type,state',
            'states' => 'required_if:zone_type,state',
            'zone_status' => 'nullable|numeric'

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
            'zone_name.required' => 'Please enter a name for this tax zone.',
            'zone_name.unique_to_store' => 'Zone name already exists.',
            'zone_country.required_if'  => 'Please select one or more countries.',
            'zip_code.required_if' => 'Please enter one or more ZIP/postal codes.',
            'state_country.required_if' => 'Please select one or more countries.',
            'states.required_if' => 'Please select one or more states.',
            'zone_status.numeric' => 'Active must be numeric.'
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
