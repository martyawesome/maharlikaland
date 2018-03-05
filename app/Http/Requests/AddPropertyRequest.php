<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddPropertyRequest extends Request
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
            'name' => 'required|max:255',
            'price' => config('constants.REQUEST_NUMERIC'),
            'price_per_sqm' => config('constants.REQUEST_NUMERIC'),
            'coordinates' => config('constants.REQUEST_COORDINATES')
        ];

        // Residential properties
        if($this->property_type == '1' ||
            $this->property_type == '2' ||
            $this->property_type == '3') {

            $rules['lot_number'] = 'required|integer';
            $rules['block_number'] = 'required|integer';

            for($i = 1; $i <= $this->floors;$i++ ){
                $rules['floor_area_per_floor.'.$i] = config('constants.REQUEST_NUMERIC_REQUIRED');
            }

            $rules['floor_area'] = config('constants.REQUEST_NUMERIC');
            $rules['lot_area'] = config('constants.REQUEST_NUMERIC_REQUIRED');
        }
        // Lot  
        else if ($this->property_type == '4') {
            $rules['lot_number'] = 'required|integer';
            $rules['block_number'] = 'required|integer';
        }
        // Condominium Unit  
        else if ($this->property_type == '5') {
            $rules['floor_area'] = config('constants.REQUEST_NUMERIC');
        }
        // Commercial Unit  
        else if ($this->property_type == '6') {
            $rules['floor_area'] = config('constants.REQUEST_NUMERIC');
        }
        // Commercial Building  
        else if ($this->property_type == '7') {
            $rules['lot_area'] = config('constants.REQUEST_NUMERIC');
            
            for($i = 1; $i <= $this->floors;$i++ ){
                $rules['floor_area_per_floor.'.$i] = config('constants.REQUEST_NUMERIC');
            }
        }

        return $rules;
    }
}
