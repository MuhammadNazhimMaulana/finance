<?php

namespace App\Http\Requests\Payment\Disbursement;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'category' => 'required|string|max:191',
            'amount' => 'required',
            'to_name' => 'required|string|max:191',
            'to_email' => 'required|email',
            'bank_code' => 'required|string|max:191',
            'bank_name' => 'required|string|max:191',
            'bank_account_holder_name' => 'required|string|max:191',
            'bank_account_number' => 'required|numeric',
            'description' => 'required|string',
            'pin' => 'required|digits:6',
        ];
    }
}
