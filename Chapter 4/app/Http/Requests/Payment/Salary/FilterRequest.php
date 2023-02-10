<?php

namespace App\Http\Requests\Payment\Salary;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
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
            'name' => 'nullable|string',
            'company_id' => 'nullable|integer',
            'branch_id' => 'nullable|integer',
            'department_id' => 'nullable|integer',
            'position_id' => 'nullable|integer',
            'status' => 'nullable|string',
            'date' => 'nullable|date',
        ];
    }
}
