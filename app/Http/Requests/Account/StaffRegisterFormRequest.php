<?php

namespace Shopbox\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class StaffRegisterFormRequest extends FormRequest
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
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'locations.*' => 'nullable|numeric'
        ];
    }

    public function messages()
    {
        return [
            'locations.*.numeric' => 'Store location must be numeric'
        ];
    }
}
