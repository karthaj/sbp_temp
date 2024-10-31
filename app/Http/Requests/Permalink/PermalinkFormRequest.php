<?php

namespace Shopbox\Http\Requests\Permalink;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PermalinkFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'entity' => 'required',Rule::in(['category','page','blog']),
            'handle' => 'required'
        ];
    }

}