<?php

namespace Modules\Order\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Get data to be validated from the request.
     *
     * @return array
    */
    protected function validationData()
    {
        if($this->amount) {
            $amount = explode(',', $this->amount);
            $amount = array_filter($amount);
            $amount = implode('', $amount);
            $amount = (float) $amount;

            $this->merge(['amount' => $amount]);
        }
        
        return $this->all();
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'transaction_id' => 'required|max:255',
            'currency' => 'required|max:3',
            'amount' => 'required|numeric'
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
