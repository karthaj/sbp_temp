<?php

namespace Modules\Order\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderStatusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'required|string|exists:order_states,slug'
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


    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
    */

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if($this->order->state->slug === 'cancelled' && $this->status === 'completed') {
                
                $store = $this->order->store->onlineStore;

                foreach ($this->order->details as $item) {
                    $stock = $item->product->stock;
        
                    if($item->product_attribute) {
                        $stock = $item->product_attribute->stock;
                    }

                    if(!$stock->product->available_for_order) {

                        $store_stock = $store->stocks->where('stock_id', $stock->id)->first();

                        if($store_stock->quantity < $item->product_quantity) {
                            $validator->errors()->add('status', 'failed');
                            break;
                        }
                    }
                }
            }
        });
    }
}
