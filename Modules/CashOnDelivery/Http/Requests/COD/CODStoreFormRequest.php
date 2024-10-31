<?php

namespace Modules\CashOnDelivery\Http\Requests\COD;

use Illuminate\Foundation\Http\FormRequest;

class CODStoreFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'shipping_zone' => 'required|numeric',
            'surcharge' => 'nullable|numeric',
            'status' => 'nullable|numeric',
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
