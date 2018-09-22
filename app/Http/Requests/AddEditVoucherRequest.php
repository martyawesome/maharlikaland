<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Voucher;

class AddEditVoucherRequest extends Request
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
        $this->voucher = $this->voucher == null ? new Voucher() : $this->voucher;

        return [
            'date' => 'required|date',
            'payee' => 'required',
            'issued_by' => 'required',
            'received_by' => 'required',
            'voucher_number' => 'unique:vouchers,voucher_number,'.$this->voucher->id.'|integer|required'
        ];
    }
}
