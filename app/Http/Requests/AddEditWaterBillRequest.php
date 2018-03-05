<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddEditWaterBillRequest extends Request
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
            'date_covered' => 'required',
            'consumption' => config('constants.REQUEST_NUMERIC_REQUIRED'),
            'bill' => config('constants.REQUEST_NUMERIC_REQUIRED')
        ];
    }
}
