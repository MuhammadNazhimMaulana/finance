<?php

namespace App\Http\Requests\Payment\Salary;

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
            'employe_salary_id' => 'nullable|integer',
            'employe_id' => 'required|integer',
            'employe_bank_id' => 'required|integer',
            'pin' => 'required|digits:6',
            'try_count' => 'nullable|integer'
        ];
    }
}
