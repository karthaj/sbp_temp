<?php

namespace Modules\Customer\Http\Requests\Customer\Address;

use Illuminate\Foundation\Http\FormRequest;


class AddressFormRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|exists:customers,email',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|numeric|min:10,max:10',
            'address1' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'numeric|required_if:state,'.null,
            'state' => 'required_if:country,'.null,
            'postcode' => 'required|zip_code_format',
            'status' => 'numeric|max:1',
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
