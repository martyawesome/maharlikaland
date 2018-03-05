<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditDeveloperAccountRequest extends Request
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
            'developer_name' => 'required|unique:developers,name,'. $this->user->developer_id,
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
