<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddProjectRequest extends Request
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
            'name' => 'required|unique:projects,name',
            'project_type' => 'required',
            'province' => 'required',
            'city_municipality' => 'required',
            'blocks' => 'required|integer',
            'electricity_source' => 'required',
            'water_source' => 'required',
            'is_active' => 'required'
        ];

        if($this->project_type == 1) {
            $rules['blocks'] = 'required|integer';
            for($i = 0; $i < $this->lots; $i++) {
                $rules['lots_blocks.'.$i] = 'required|integer';
            }
        } else {
        }

        return $rules;
    }
}
