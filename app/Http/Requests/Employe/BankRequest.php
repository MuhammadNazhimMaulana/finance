<?php

namespace App\Http\Requests\Employe;

use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
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
            'employe_id' => 'required|integer',
            'code' => 'required|string|max:191',
            'name' => 'required|string|max:191',
            'account_holder_name' => 'required|string|max:191',
            'account_number' => 'required|numeric',
        ];
    }
}
