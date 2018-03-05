<?php

namespace App;

use App\Developer;

use Illuminate\Database\Eloquent\Model;

class PayrollDeductionType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payroll_deduction_types';

    /**
    * Get the list of types for the form
    *
    */
    public static function getAll()
    {
    	$developer = Developer::getCurrentDeveloper();
    	return PayrollDeductionType::whereDeveloperId($developer->id)->lists('type','id');
    }


}
