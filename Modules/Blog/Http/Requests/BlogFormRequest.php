<?php

namespace Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255|unique_to_store:blogs,title,'.$this->blog,
            'page_title' => 'max:70',
            'meta_description' => 'max:160',
            'meta_keywords' => 'max:255',
            'url_handle' => 'unique_to_store:blogs,slug,'.$this->blog,
            'visibility' => 'required|numeric',
            'featured_image' => 'image',
            'author' => 'nullable|max:255'
        ];
    }

    public function messages()
    {
        return [ 
            'title.unique_to_store' => 'Title already exists.',
            'url_handle.unique_to_store' => 'Url handle already exists.'
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
