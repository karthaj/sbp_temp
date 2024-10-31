<?php

namespace Modules\Menu\Http\Requests\Menu;

use Illuminate\Foundation\Http\FormRequest;

class MenuStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'menu_name' => 'required|max:255|string|unique_to_store:menus,name',
        ];
    }

    public function messages()
    {
        return [
            'menu_name.unique_to_store' => 'Menu name already exists.'
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
