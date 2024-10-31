<?php

namespace Modules\Product\Http\Requests\Attribute\Sets;

use Illuminate\Foundation\Http\FormRequest;

class OptionSetUpdateFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255|unique_to_store:option_sets,name,'.$this->option_set,
            'options.*' => 'required|numeric',
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
            'name.unique_to_store' => 'Option set already taken.',
            'options.*'  => 'Please choose atleast one variation',
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
