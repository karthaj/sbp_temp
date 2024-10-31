<?php

namespace Modules\Product\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class StockTransferFormRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'transfer_type' => 'required|string',
            'transfer_store' => 'required|numeric'
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
            'transfer_type.required' => 'Type is required.',
            'transfer_store.required' => 'Store is required.',
            'transfer_store.numeric' => 'Store must be a number.'
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
