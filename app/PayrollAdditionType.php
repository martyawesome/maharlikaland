<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollAdditionType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payroll_addition_types';

    /**
    * Get the list of types for the form
    *
    */
    public static function getAll()
    {
    	return PayrollAdditionType::lists('type','id');
    }
}
