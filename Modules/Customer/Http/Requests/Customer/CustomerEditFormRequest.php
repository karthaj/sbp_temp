<?php

namespace Modules\Customer\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerEditFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email,'.$this->customer->id,
            'phone' => 'required|numeric|min:10,max:10',
            'group' => 'nullable|numeric',
            'address_firstname' => 'required|string|max:255',
            'address_lastname' => 'required|string|max:255',
            'address_company' => 'nullable|string|max:255',
            'address_phone' => 'nullable|numeric|min:10,max:10',
            'address1' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'numeric|required_if:state,'.null,
            'state' => 'required_if:country,'.null,
            'postcode' => 'required|zip_code_format:postcode',
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
