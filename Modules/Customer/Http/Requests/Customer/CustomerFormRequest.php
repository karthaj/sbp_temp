<?php

namespace Modules\Customer\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerFormRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|unique_email:customers,email,'.$this->customer,
            'phone' => 'required|numeric|min:10,max:10',
            'group' => 'nullable|numeric',
            // 'address_company' => 'nullable|string|max:255',
            // 'address_phone' => 'nullable|numeric|min:10,max:10',
            // 'address1' => 'required|string|max:255',
            // 'address2' => 'nullable|string|max:255',
            // 'city' => 'required|string|max:255',
            // 'country' => 'required|numeric',
            // 'postcode' => 'nullable|zip_code_format:postcode',
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
            'address_firstname.required' => 'First name required.',
            'address_lastname.required' => 'Last name required.',
            'email.unique_email' => 'Email already taken.'
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
