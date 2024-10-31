<?php

namespace Shopbox\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'store_name' => 'required|max:255',
            'account_email' => 'required|email',
            'customer_email' => 'required|email',
            'company' => 'required|max:255',
            'phone' => 'nullable|min:10',
            'address1' => 'required|max:255',
            'address2' => 'max:255',
            'city' => 'required|max:255',
            'zip_code' => 'required|max:255',
            'country' => 'required|numeric',
            'state' => 'numeric|nullable|required_state:id,'.$this->country,
            'timezone' => 'required|numeric',
            'store_currency' => 'required|numeric',
            'order_prefix' => 'required|max:255',
            'order_suffix' => 'nullable|max:255',
            'weight_unit' => 'required|numeric',
            'logo' => 'nullable|image:jpg,jpeg,gif|mimes:jpeg,jpg,png,gif',
            'favicon' => 'nullable|image:ico,jpg,jpeg,gif|mimes:jpeg,jpg,png,gif',
            'enable_returns' => 'nullable|numeric'
        ];
    }

    public function messages()
    {
        return [
            'return_reasons.required_if' => 'return reason is required',
            'return_actions.required_if' => 'return action is required',
            'company.required' => 'Legal business name is required.',
            'company.max' => 'Legal business may not be greater than 255',
            'address1.required' => 'Legal address 1 is required.',
            'address1.max' => 'Legal address 1 may not be greater than 255',
            'address2.max' => 'Legal address 2 may not be greater than 255'
        ];
    }
}
