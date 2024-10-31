<?php

namespace Modules\Product\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:191|unique_to_store:store_locations,name,'.$this->store_location,
            'country' => 'required',
            'status' => 'required|numeric',
            'online_store' => 'required|numeric'
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
            'name.required' => 'Name is required.',
            'name.unique_to_store' => 'Name is already exists.',
            'country.required'  => 'Country is required.',
            'status.required' => 'Active must be numeric',
            'online_store.required' => 'Online store must be numeric'
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
