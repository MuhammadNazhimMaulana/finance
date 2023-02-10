<?php

namespace App\Http\Requests\Employe;

use Illuminate\Foundation\Http\FormRequest;

class EmployeRequest extends FormRequest
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
            'company_id' => 'required|integer',
            'branch_id' => 'required|integer',
            'department_id' => 'required|integer',
            'position_id' => 'required|integer',
            'name' => 'required|string|max:191',
            'email' => 'required|email',
            'nik' => 'nullable|string|max:191',
            'address' => 'nullable|string|max:191',
            'date_of_birth' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:191',
            'phone' => 'nullable|string|max:191',
            'monthly_salary' => 'required',
        ];
    }
}
