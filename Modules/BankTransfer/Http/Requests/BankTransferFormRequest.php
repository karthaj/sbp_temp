<?php

namespace Modules\BankTransfer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankTransferFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'display_name' => 'required|max:255',
            'account_name' => 'required',
            'account_number' => 'required',
            'bank_name' => 'required',
            'bank_branch' => 'required',
            'swift_code' => 'required',
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
