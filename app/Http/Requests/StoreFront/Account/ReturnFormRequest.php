<?php

namespace Shopbox\Http\Requests\StoreFront\Account;

use Illuminate\Foundation\Http\FormRequest;

class ReturnFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'return_reason' => 'required',
            'return_qty' => 'array',
            'return_qty.*' => 'required|numeric|valid_rma:'.$this->order->id
        ];
    }

    public function messages()
    {
        return [
            'return_reason.required' => 'Please provide a reason for your RMA.',
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