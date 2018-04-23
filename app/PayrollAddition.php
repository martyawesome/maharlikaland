<?php

namespace App;

use App\Http\Requests\AddEditPayrollAdditionRequest;

use Illuminate\Database\Eloquent\Model;

use DB;
use DateTime;

class PayrollAddition extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payroll_additions';

    /**
    * Get all of the cash advance payments for the developer.
    *
    */
    public static function getAll()
    {
    	return PayrollAddition::selectRaw('users.first_name, users.middle_name, users.last_name, payroll_additions.*, payroll_addition_types.type')
    	->leftJoin('users','users.id','=','payroll_additions.user_id')
    	->leftJoin('payroll_addition_types','payroll_addition_types.id','=','payroll_additions.payroll_addition_type_id')
    	->get();
    }

    /**
    * Get all users for transaction selection.
    *
    */
    public static function getUserForList(PayrollAddition $payroll_addition)
    {
        return User::selectRaw(DB::raw('CONCAT(users.last_name,", ",users.first_name," ",users.middle_name) as full_name, users.id, payroll_additions.user_id'))
        ->leftJoin('payroll_addition','users.id','=','payroll_addition.user_id')
        ->whereRaw(DB::raw('payroll_addition.user_id = '.$payroll_addition->user_id))
        ->lists('full_name', 'id');
    }

    /**
    * Save or update a cash advance payment.
    *
    */
    public static function addEdit(PayrollAddition $payroll_addition, AddEditPayrollAdditionRequest $request)
    {
    	DB::beginTransaction();

    	try {
    		$payroll_addition->user_id = $request->get('user');
    		$payroll_addition->date = $request->get('date');
    		$payroll_addition->amount = $request->get('amount');
    		$payroll_addition->payroll_addition_type_id = $request->get('type');

    		$return['success'] = $payroll_addition->touch();

    		if($return['success']) {
    			DB::commit();
    		} else {
    			DB::rollback();
    		}
    	} catch(Exception $e) {
    		$return['success'] = false;
    		DB::rollback();
    	}

    	return $return;
    }

    /**
    * Save the payroll additions imported from an excel file.
    *
    */
    public static function importLedger($data)
    {
        DB::beginTransaction();

        $counter = true;
        $row_counter = 1;
        
        foreach($data as $datum) {
            if($datum->last_name != null and $datum->first_name != null and $datum->middle_name != null
                and $datum->amount != null and $datum->date != null and $datum->type != null) {
                $user = User::getByWholeName($datum->first_name, $datum->middle_name, $datum->last_name);

                if(!$user){
                    DB::rollback();
                    $counter = false;
                    $return['success'] = false;
                    $return['message'] = "User not found in row ".($row_counter+1);
                    break;
                }

                // Format date
                $date = new DateTime($datum->date);
                $payroll_addition = PayrollAddition::whereRaw('user_id = '.$user->id.' and date = "'.$date->format('Y-m-d').'"')->first();


                if(!$payroll_addition)
                    $payroll_addition = new PayrollAddition();
                
                $payroll_addition_type = PayrollAdditionType::whereRaw(DB::raw("type like '%".$datum->type."%'"))->first();
                if(!$payroll_addition_type)
                    $payroll_addition_type = new PayrollAdditionType();
                
                $payroll_addition->user_id = $user->id;
                $payroll_addition->date = $date;
                $payroll_addition->amount = str_replace(',','',$datum->amount);
                $payroll_addition->date = $date->format('Y-m-d');
                $payroll_addition->payroll_addition_type_id = $payroll_addition_type->id;

                if($payroll_addition->touch()){
                    $counter = true;
                } else{
                    $counter = false;
                }
            } else {
                // Skip the headers
                if($row_counter > 1){
                    break;
                    DB::rollback();
                    $return['success'] = false;
                    $return['message'] = "Data missing in row ".$row_counter;
                }
            }
            $row_counter++;
        }

        if($counter){
            DB::commit();
            $return['success'] = true;
        } else {
            DB::rollback();
            $return['success'] = false;
            if(!$return['message']) {
                $return['message'] = "Payroll additions were unsuccessfully imported";
            }
        }

        return $return;
    }

    /**
    * Delete a payroll additions.
    *
    */
    public static function deleteAddition(PayrollAddition $payroll_addition)
    {
        DB::beginTransaction();

        try {
            $return['success'] = $payroll_addition->delete();

            if($return['success']) {
                DB::commit();
            } else {
                DB::rollback();
            }
        } catch(Exception $e) {
            DB::rollback();
            $return['success'] = false;
        }

        return $return;
    }

    /**
    * Get the payroll addition for a given date.
    *
    */
    public static function getFromDate($user_id, $date)
    {
        return PayrollAddition::whereRaw(DB::raw('user_id = '.$user_id. ' and date = "'.$date.'"'))
        ->sum('amount');
        /*return PayrollAddition::selectRaw(DB::raw('sum(amount) as amount'))
        ->whereRaw(DB::raw('user_id = '.$user_id. ' and date = "'.$date.'"'))
        ->get();*/
    }

}
