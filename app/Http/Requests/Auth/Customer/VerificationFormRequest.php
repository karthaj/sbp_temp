<?php

namespace Shopbox\Http\Requests\Auth\Customer;

use Illuminate\Foundation\Http\FormRequest;

class VerificationFormRequest extends FormRequest
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
            'newsletter' => 'required|numeric|between:0,1',
            'agree' => 'accepted'
        ];
    }
}
