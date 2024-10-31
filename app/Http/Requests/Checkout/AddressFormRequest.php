<?php

namespace Shopbox\Http\Requests\Checkout;

use Illuminate\Validation\Rule;
use Shopbox\Models\Zpanel\State;
use Shopbox\Models\Zpanel\Country;
use Illuminate\Foundation\Http\FormRequest;

class AddressFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->billing['country']) {
            return (bool) Country::where('iso_code', $this->billing['country'])->count();
        }

        if($this->shipping['country']) {
            return (bool) Country::where('iso_code', $this->shipping['country'])->count();
        }

        if($this->billing['state']) {
            return (bool) State::where('iso_code', $this->billing['state'])->count();
        }

        if($this->shipping['state']) {
            return (bool) State::where('iso_code', $this->shipping['state'])->count();
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data =  [
             'billing.firstname' => 'required|max:255',
             'billing.lastname' => 'required|max:255',
             'billing.address1' => 'required',
             'billing.address2' => 'nullable|max:255',
             'billing.city' => 'required|max:255',
             'billing.country' => 'required',
             'billing.state' => 'required_state:iso_code,'.$this->billing['country'],
             'billing.phone' => 'required|numeric|min:10',
             'billing.postcode' => 'required|zip_code_format:billing.country',
             'same_address' => 'required',Rule::in(['true', 'false']),
        ];

        if($this->same_address == 'false') {
            $data['shipping.firstname'] = 'required|max:255';
             $data['shipping.lastname'] = 'required|max:255';
             $data['shipping.address1'] = 'required';
             $data['shipping.address2'] = 'nullable|max:255';
             $data['shipping.city'] = 'required|max:255';
             $data['shipping.country'] = 'required';
             $data['shipping.state'] = 'required_state:iso_code,'.$this->shipping['country'];
             $data['shipping.phone'] = 'nullable|numeric|min:10';
             $data['shipping.postcode'] ='required|zip_code_format:shipping.country';
        }

        return $data;
    }

    public function messages()
    {
        return [
            'billing.firstname.required' => 'First name is required',
            'billing.lastname.required' => 'Last name is required',
            'billing.address1.required' => 'Address is required',
            'billing.city.required' => 'City is required',
            'billing.country.required' => 'Country is required',
            'billing.state.required_state' => 'State is required',
            'billing.phone.required' => 'Phone is required',
            'billing.phone.numeric' => 'Please enter a valid phone number',
            'billing.postcode.required' => 'Postal code is required',
            'billing.postcode.zip_code_format' => 'Invalid postal code',
            'shipping.firstname.required_if' => 'First name is required',
            'shipping.lastname.required_if' => 'Last name is required',
            'shipping.address1.required_if' => 'Address is required',
            'shipping.city.required_if' => 'City is required',
            'shipping.country.required_if' => 'Country is required',
            'shipping.postcode.required_if' => 'Postal code is required',
            'shipping.postcode.zip_code_format' => 'Invalid postal code',
            'shipping.phone.required' => 'Phone is required',
            'shipping.phone.numeric' => 'Please enter a valid phone number',
            'shipping.state.required_state' => 'State is required'
        ];
    }
}
