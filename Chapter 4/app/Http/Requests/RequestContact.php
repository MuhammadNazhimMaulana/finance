<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestContact extends FormRequest
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
            'account_code' => 'required|string|max:191',
            'name' => 'required|string|max:191',
            'email' => 'required|email',
            'bank_code' => 'required|string|max:191',
            'bank_name' => 'required|string|max:191',
            'bank_account_holder_name' => 'required|string|max:191',
            'bank_account_number' => 'required|numeric',
        ];
    }
}
