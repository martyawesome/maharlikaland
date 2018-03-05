<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddEditInstallmentAccountLedgerRequest extends Request
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
            'property' => 'required|integer',
            'tcp' => config('constants.REQUEST_NUMERIC_REQUIRED'),
            'years_to_pay' => 'required|integer',
            'reservation_fee' => config('constants.REQUEST_NUMERIC_REQUIRED'),
            'dp' => config('constants.REQUEST_NUMERIC_REQUIRED'),
            'dp_percentage' => 'required|numeric',
            'due_date' => 'required|integer|between:1,30',
            'mo_interest' => config('constants.REQUEST_NUMERIC_REQUIRED'),
            'mo_amortization' => config('constants.REQUEST_NUMERIC_REQUIRED'),
            'years_to_pay' => 'required|integer',
            'balance' => config('constants.REQUEST_NUMERIC'),
            'contract_date' => 'date'
        ];
    }
}
