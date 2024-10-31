<?php

namespace Shopbox\Http\Requests\Store;

use Shopbox\Models\Zpanel\Country;
use Illuminate\Foundation\Http\FormRequest;

class StoreFormRequest extends FormRequest
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
            'store_name' => 'required|max:255',
            'domain' => 'required|max:255|unique:stores|unique_domain:restricted_domains,word', 
            'address1' => 'required|max:255',
            'address2' => 'nullable|max:255',
            'city' => 'required|max:255',
            'country' => 'required',
            'state' => 'required_state:'.$this->country, 
            'postal_code' => 'required|max:255|zip_code_format:country'
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
            'state.required_state' => 'The state field is required.',
            'postal_code.zip_code_format' => 'Zipcode/Postcode is invalid.',
            'domain.unique_domain' => 'Domain not available.'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            request()->session()->flash('country', Country::find($this->country));
        });
    }

}
