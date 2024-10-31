<?php

namespace Modules\Customer\Http\Requests\Customer\Group;

use Illuminate\Foundation\Http\FormRequest;

class GroupFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'group_name' => 'required|max:255|unique_to_store:groups,name,'.$this->group->id,
            'group_discount' => 'nullable|numeric',
            'category_discount.*.category' => 'nullable|numeric|exists:categories,id',
            'category_discount.*.reduction' => 'nullable|numeric'
        ];
    }

    public function messages()
    {
        return [
            'group_name.unique_to_store' => 'Group name already exists.'
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
