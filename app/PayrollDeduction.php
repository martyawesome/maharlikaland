<?php

namespace App;

use App\Http\Requests\AddEditPayrollDeductionRequest;

use Illuminate\Database\Eloquent\Model;

use DB;
use DateTime;

class PayrollDeduction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payroll_deductions';

    /**
    * Get all of the cash advance payments for the developer.
    *
    */
    public static function getAll()
    {
    	return PayrollDeduction::selectRaw('users.first_name, users.middle_name, users.last_name, payroll_deductions.*, payroll_deduction_types.type')
    	->leftJoin('users','users.id','=','payroll_deductions.user_id')
    	->leftJoin('payroll_deduction_types','payroll_deduction_types.id','=','payroll_deductions.payroll_deduction_type_id')
    	->get();
    }

    /**
    * Get all users for transaction selection.
    *
    */
    public static function getUserForList(PayrollDeduction $payroll_deduction)
    {
        return User::selectRaw(DB::raw('CONCAT(users.last_name,", ",users.first_name," ",users.middle_name) as full_name, users.id, payroll_deductions.user_id'))
        ->leftJoin('payroll_deduction','users.id','=','payroll_deduction.user_id')
        ->whereRaw(DB::raw('payroll_deduction.user_id = '.$payroll_deduction->user_id))
        ->lists('full_name', 'id');
    }

    /**
    * Save or update a cash advance payment.
    *
    */
    public static function addEdit(PayrollDeduction $payroll_deduction, AddEditPayrollDeductionRequest $request)
    {
    	DB::beginTransaction();

    	try {
    		$payroll_deduction->user_id = $request->get('user');
    		$payroll_deduction->date = $request->get('date');
    		$payroll_deduction->amount = $request->get('amount');
    		$payroll_deduction->payroll_deduction_type_id = $request->get('type');

    		$return['success'] = $payroll_deduction->touch();

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
    * Save the cash advance payments imported from an excel file.
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
                $payroll_deduction = PayrollDeduction::whereRaw('user_id = '.$user->id.' and date = "'.$date->format('Y-m-d').'"')->first();


                if(!$payroll_deduction)
                    $payroll_deduction = new PayrollDeduction();
                
                $payroll_deduction_type = PayrollDeductionType::whereRaw(DB::raw("type like '%".$datum->type."%'"))->first();
                if(!$payroll_deduction_type)
                    $payroll_deduction_type = new PayrollDeductionType();
                
                $payroll_deduction->user_id = $user->id;
                $payroll_deduction->date = $date;
                $payroll_deduction->amount = str_replace(',','',$datum->amount);
                $payroll_deduction->date = $date->format('Y-m-d');
                $payroll_deduction->payroll_deduction_type_id = $payroll_deduction_type->id;

                if($payroll_deduction->touch()){
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
                $return['message'] = "Payroll deductions were unsuccessfully imported";
            }
        }

        return $return;
    }

    /**
    * Delete a payroll deduction.
    *
    */
    public static function deleteDeduction(PayrollDeduction $payroll_deduction)
    {
        DB::beginTransaction();

        try {
            $return['success'] = $payroll_deduction->delete();

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
    * Get the payroll deduction for a given date.
    *
    */
    public static function getFromDate($user_id, $date)
    {
        return PayrollDeduction::whereRaw(DB::raw('user_id = '.$user_id. ' and date = "'.$date.'"'))
        ->sum('amount');
        /*return PayrollDeduction::selectRaw(DB::raw('amount'))
        ->whereRaw(DB::raw('user_id = '.$user_id. ' and date = "'.$date.'"'))
        ->first();*/
    }

}
