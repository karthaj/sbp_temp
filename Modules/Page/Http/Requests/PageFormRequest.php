<?php

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required',Rule::in(['page','contact']),
            'title' => 'required|max:255|unique_to_store:pages,title,'.$this->page,
            'page_title' => 'max:70',
            'meta_description' => 'max:160',
            'meta_keywords' => 'max:255',
            'url_handle' => 'unique_to_store:pages,slug,'.$this->page,
            'active' => 'required|numeric',
            'enable_form' => 'nullable|numeric'
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
