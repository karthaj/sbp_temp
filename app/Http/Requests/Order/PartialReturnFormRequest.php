<?php

namespace Shopbox\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class PartialReturnFormRequest extends FormRequest
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
            'selected.*' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'return_reason.required' => 'Please provide a reason for your RMA.',
            'selected.*.required' => 'Please select one or more items to return.'
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
