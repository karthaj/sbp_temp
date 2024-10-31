<?php

namespace Shopbox\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class ProfileStoreRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.auth()->id(),
            'phone' => 'nullable|numeric|min:10|max:13|unique:users,phone.'.auth()->id(),
            'avatar' => 'image',
            //'current_password' => 'nullable|required_with:password|current_password',
            'password' => 'nullable|required_with:current_password|string|min:6|confirmed',
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
            'current_password.current_password' => 'Your current password is incorrect.',
        ];
    }
}
