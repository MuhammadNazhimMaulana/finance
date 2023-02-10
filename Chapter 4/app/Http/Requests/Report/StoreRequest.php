<?php

namespace App\Http\Requests\Report;

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
            'type' => 'required|in:BALANCE_HISTORY,TRANSACTIONS,UPCOMING_TRANSACTIONS',
            'from' => 'required|date|before:to',
            'to' => 'required|date|after:from'
        ];
    }
}
