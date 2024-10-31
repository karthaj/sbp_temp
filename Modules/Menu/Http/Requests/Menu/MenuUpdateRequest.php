<?php

namespace Modules\Menu\Http\Requests\Menu;

use Illuminate\Foundation\Http\FormRequest;

class MenuUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'menu_name' => 'required|max:255|string|unique_to_store:menus,name,'.$this->menu,
            'menu_item_url.*' => 'required|max:255',
            'menu_item_label.*' => 'required|string|max:255'
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
            'menu_name.required' => 'Menu name is required.',
            'menu_item_url.*.required' => 'Menu url is required.',
            'menu_item_label.*.required' => 'Menu label is required.',
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
