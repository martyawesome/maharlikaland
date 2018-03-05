<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditProjectBasicInfoRequest extends Request
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
            'name' => 'required|unique:projects,name,'.$this->project->id,
            'project_type' => 'required'
        ];
    }
}
