<?php

namespace Shopbox\Http\Requests\Domain;

use Illuminate\Foundation\Http\FormRequest;

class DomainRequest extends FormRequest
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
            'domain' => ['required', 'regex:^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}$^', 'unique:stores,store_url,'.$this->tenant()->id]
        ];
    }

    public function messages()
    {
        return [
            'domain.required' => 'Domain is required.',
            'domain.unique' => 'domain taken.',
        ];
    }

}
