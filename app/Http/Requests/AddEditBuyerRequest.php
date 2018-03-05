<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Buyer;

class AddEditBuyerRequest extends Request
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
        if($this->buyer == null)
            $this->buyer = new Buyer();
        return [
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'sex' => 'required',
            'home_address' => 'required',
            'contact_number_mobile' => 'required',
            'email' => 'unique:buyers,email,'.$this->buyer->id.'|email',
            'birthdate' => 'date',
            'num_of_children' => 'integer'
        ];
    }
}
