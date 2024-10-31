<?php

namespace Modules\HNB\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HNBStoreFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'display_name' => 'required|max:255',
            'merchant_id' => 'required|numeric',
            'acquirer_id' => 'required|numeric',
            'password' => 'required|max:255',
            'test_mode' => 'required|boolean|max:255',
            'currency' => 'required|numeric',
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
