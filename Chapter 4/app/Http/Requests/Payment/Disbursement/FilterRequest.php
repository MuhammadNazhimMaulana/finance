<?php

namespace App\Http\Requests\Payment\Disbursement;

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
            'to_name' => 'nullable|string|max:191',
            'amount' => 'nullable|numeric',
            'date' => 'nullable|date',
            'status' => 'nullable|string|in:PENDING,FAILED,COMPLETED'
        ];
    }
}
