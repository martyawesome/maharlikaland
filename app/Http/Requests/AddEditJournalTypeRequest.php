<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddEditJournalTypeRequest extends Request
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
        if($this->journal_type){
            return [
                'type' => 'unique:journal_types,type,'.$this->journal_type->id.'|required'
            ];
        } else {
            return [
                'type' => 'unique:journal_types,type|required'
            ];
        }
    }
}
