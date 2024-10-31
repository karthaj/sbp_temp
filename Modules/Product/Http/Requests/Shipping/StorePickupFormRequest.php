<?php

namespace Modules\Product\Http\Requests\Shipping;

use Illuminate\Foundation\Http\FormRequest;

class StorePickupFormRequest extends FormRequest
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
            'store_pickup' => 'required|min:0|max:1'
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
            'display_name.required' => 'Display name is required.',
            'store_pickup.min' => 'Enable store pickup contains invalid data.',
            'store_pickup.max' => 'Enable store pickup contains invalid data.',
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
