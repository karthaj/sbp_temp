<?php

namespace Shopbox\Http\Requests\StoreFront\Account;

use Shopbox\Models\Zpanel\State;
use Shopbox\Models\Zpanel\Country;
use Illuminate\Validation\Validator;
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
        if($this->country) {
            return Country::find($this->country);
        }

        if($this->state) {
            return State::find($this->state);
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
        return [
            'alias' => 'required|max:255',
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'company' => 'max:255|nullable',
            'phone' => 'numeric|min:10|nullable',
            'address1' => 'required|max:255',
            'address2' => 'max:255|nullable',
            'city' => 'required|max:255',
            'country' => 'required',
            'state' => 'required_state:'.$this->country,
            'zipcode' => 'required|max:255|zip_code_format:country'
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
            'alias.required' => 'The location name field is required.',
            'alias.max' => 'The location name may not be greater than 255 characters.',
            'state.required_state' => 'The state field is required.',
            'zipcode.zip_code_format' => 'Zipcode/Postcode is invalid.'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            request()->session()->flash('country', Country::find($this->country));
        });
    }

}
