<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditAgentAccountRequest extends Request
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
            'prc_license_number' => 'required',
            'username' => 'required|unique:users,username,'. $this->user->id,
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'birthdate' => 'required',
            'address' => 'required',
            'contact_number' => 'required',
            'email' => 'required|email|unique:users,email,'. $this->user->id
        ];
    }
}
