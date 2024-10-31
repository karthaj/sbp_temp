<?php

namespace Shopbox\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class PreferenceRequest extends FormRequest
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
            'page_title' => 'required|max:255',
            'password' => 'required|max:255|min:6', 
            'message' => 'required',
            'enable_password' => 'nullable|numeric', 
            'google_analytics' => 'nullable|max:255', 
            'facebook_pixel_id' => 'nullable|max:255', 
            'captcha_site_key' => 'nullable|max:255', 
            'captcha_secret_key' => 'nullable|max:255', 
        ];
    }
}
