<?php

namespace Shopbox\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class CartUpdateFormRequest extends FormRequest
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
            'id' => 'required|numeric',
            'qty' => 'required|numeric'
        ];
    }
}
