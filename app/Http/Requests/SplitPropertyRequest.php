<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SplitPropertyRequest extends Request
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
        $rules = [
            'lots' => 'required|integer'
        ];

        for($i = 0; $i < $this->lots; $i++) {
            $rules['lots_lot_area.'.$i] = config('constants.REQUEST_NUMERIC_REQUIRED');
        }

        return $rules;
    }
}
