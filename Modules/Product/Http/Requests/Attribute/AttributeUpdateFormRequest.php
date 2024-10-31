<?php

namespace Modules\Product\Http\Requests\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class AttributeUpdateFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'option_name' => 'required|max:255|unique_to_store:attributes,name,'.$this->option_id,
            'display_name' => 'required|max:255',
            'display_style' => 'required_if:display_type,multiple choice|max:255',
            'display_type' => 'required|max:255',
            'values.*.label' => 'required_if:display_type,multiple choice|max:255',
            'swatches.*.label' => 'required_if:display_type,swatch|max:255',
            'swatches.*.color' => 'required_if:swatches.*.type,color|max:255',
            'swatches.*.type' => 'required_if:display_type,swatch|max:255',
            'swatches.*.image' => 'pattern_exists:swatches.*.id,swatches.*.type,'.$this->option_id
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
            'option_name.required' => 'Please enter an option name',
            'option_name.unique_to_store' => 'Option already exists',
            'display_name.required'  => 'Please enter a display name',
            'display_style.required'  => 'Please choose a display style',
            'display_type.required'  => 'Please choose a display type',
            'values.*.label.required_if' =>  'Value is required',
            'swatches.*.label.required_if' =>  'Swatch name is required',
            'swatches.*.type.required_if' =>  'Swatch type is required',
            'swatches.*.color.required_if' =>  'Color is required',
            'swatches.*.image.pattern_exists' => 'Pattern is required'
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
