<?php

namespace Modules\Product\Http\Requests\Tax;

use Illuminate\Foundation\Http\FormRequest;

class TaxClassFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tax_class' => 'required|max:191|unique_to_store:tax_classes,name,'.$this->id
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
            'tax_class.required' => 'Please enter a tax class name.',
            'tax_class.unique_to_store' => 'Tax class already exists.',
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
