<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateAgentAccountRequest extends Request
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
        if(!$this->user->id) {
            return [
                'password' => 'required|confirmed|min:5',
                'password_confirmation' => 'required',
                'first_name' => 'required',
                'middle_name' => 'required',
                'last_name' => 'required',
                'birthdate' => 'required',
                'address' => 'required',
                'contact_number' => 'required',
                'email' => 'required'
            ];
        } else {
            return [];
        }
    }
}
