<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\ProspectBuyer;

class AddEditProspectBuyerRequest extends Request
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
        if($this->prospect_buyer == null)
            $this->prospect_buyer = new ProspectBuyer();
        return [
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'sex' => 'required',
            'address' => 'required',
            'contact_number' => 'required',
            'email' => 'unique:prospect_buyers,email,'.$this->prospect_buyer->id.'|email|required'
        ];
    }
}
