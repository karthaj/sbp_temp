<?php

namespace Modules\Product\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryFormRequest extends FormRequest
{ 
        
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:191',
            'cover_image' => 'image',
            'category' => 'required|numeric',
            'status' => 'numeric',
            'sort_order' => 'numeric',
            'page_title' => 'max:70',
            'meta_description' => 'max:160',
            'url_handle' => 'required|unique_to_store:categories,slug,'.$this->category_edit,
        ];
    }

    public function messages()
    {
        return [
            'category.required' => 'Please choose atleast on category.',
            'url_handle' => 'Handle is required.',
            'url_handle.unique_to_store' => 'Handle already exists.',
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
