<?php

namespace Shopbox\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class StaffUpdateFormRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:users,email,'.$this->user->id,
            'locations.*' => 'nullable|numeric'

        ];
    }
}
