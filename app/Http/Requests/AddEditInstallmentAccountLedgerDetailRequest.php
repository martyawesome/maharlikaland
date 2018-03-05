<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddEditInstallmentAccountLedgerDetailRequest extends Request
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
        if($this->payment_type != config('constants.PAYMENT_TYPE_PENALTY_FEE')) {
            if($this->payment_type == config('constants.PAYMENT_TYPE_FULL_PAYMENT')) {
                $rules = [
                    'payment_type' => 'required|integer',
                    'payment_date' => 'required|date'
                ];
            } else {
                $rules = [
                    'payment_type' => 'required|integer',
                    'payment_date' => 'required|date',
                    'amount_paid' => config('constants.REQUEST_NUMERIC_REQUIRED'),
                ];
            }
        } else {
            $rules = [
                'payment_type' => 'required|integer'
            ];
        }

        return $rules;
    }
}
