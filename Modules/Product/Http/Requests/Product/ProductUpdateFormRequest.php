<?php

namespace Modules\Product\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_type' => 'required|string|'.Rule::in(['standard','variant','virtual']),
            'title' => 'required|max:255',
            'url_handle' => 'required|max:255|unique_to_store:products,slug,'.$this->product->id,
            'min_qty' => 'nullable|numeric|min:1|max:9999',
            'low_qty' => 'nullable|numeric|min:0|max:9999',
            'weight' => 'required_if:product_type,standard,variant',
            'width' => 'nullable',
            'depth' => 'nullable',
            'height' => 'nullable',
            'product_availability' => 'nullable|'.Rule::in(['none','preorder','backorder']),
            'instock' => 'nullable|max:255',
            'outofstock' => 'nullable|max:255',
            'related_products' => 'array',
            'available_date' => 'nullable|date_format:Y-m-d',
            'special_start_date' => 'required_with:special_price|nullable|date_format:Y-m-d',
            'special_start_time' => 'required_with:special_price',
            'special_end_date' => 'required_with:special_price|nullable|date_format:Y-m-d',
            'special_end_time' => 'required_with:special_price',
            'meta_title' => 'nullable|max:70',
            'meta_description' => 'nullable|max:160',
            'meta_keywords' => 'nullable|max:255',
            'max_downloads' => 'nullable|numeric',
            'product_file' => 'nullable|max:5000',
            'selling_price' => 'required',
            'cost_price' => 'nullable',
            'special_price' => 'nullable',
            'category' => 'required',
            'live' => 'required|min:1|max:2',
            'tax_class' => 'required|numeric',
            'shipping_class' => 'nullable|numeric',
            'sku' => 'nullable|max:255',
            'barcode' => 'nullable|max:255',
            'isbn' => 'nullable|max:32',
            'upc' => 'nullable|max:12',
            'online' => 'nullable|numeric',
            'condition' => 'required|'.Rule::in(['new','used','refurbished']),
            'show_condition' => 'nullable|numeric',
            'max_downloads' => 'nullable|numeric',
            "brand" => 'nullable|numeric',

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
            'category.required' => 'Select at least 1 category for this product.',
            'url_handle.unique_to_store' => 'Url duplicate.',
            'live.min' => 'Invalid data for live.',
            'live.max' => 'Invalid data for live.',
            'weight.required_if' => 'Product weight is required to calculate shipping rates.'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->product_type === 'variant' && !$this->product->variations->count()) {
                $validator->errors()->add('variant', 'Product must have at least one variant.');
            }
        });
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
