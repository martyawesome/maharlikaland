<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Http\Requests\AddEditCashAdvanceRequest;

use DB;
use DateTime;

class CashAdvancePayment extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cash_advance_payments';

    /**
    * Get all of the cash advance payments for the developer.
    *
    */
    public static function getAll()
    {
    	$developer = Developer::getCurrentDeveloper();
    	return CashAdvancePayment::selectRaw('users.first_name, users.middle_name, users.last_name, cash_advance_payments.*,user_types.user_type as user_type')
    	->leftJoin('users','users.id','=','cash_advance_payments.user_id')
        ->leftJoin('user_types','users.user_type_id','=','user_types.id')
    	->whereRaw(DB::raw('users.developer_id = '.$developer->id))
    	->get();
    }

    /**
    * Get all users for transaction selection.
    *
    */
    public static function getUserForList(CashAdvancePayment $cash_advance_payment)
    {
        return User::selectRaw(DB::raw('CONCAT(users.last_name,", ",users.first_name," ",users.middle_name) as full_name, users.id, cash_advance_payments.user_id'))
        ->leftJoin('cash_advance_payments','users.id','=','cash_advance_payments.user_id')
        ->whereRaw(DB::raw('cash_advance_payments.user_id = '.$cash_advance_payment->user_id))
        ->orderBy('full_name','asc')
        ->lists('full_name', 'id');
    }

    /**
    * Save or update a cash advance payment.
    *
    */
    public static function addEdit(CashAdvancePayment $cash_advance_payment, AddEditCashAdvanceRequest $request)
    {
    	DB::beginTransaction();

    	try {
    		$cash_advance_payment->user_id = $request->get('user');
    		$cash_advance_payment->date = $request->get('date');
    		$cash_advance_payment->amount = $request->get('amount');

    		$return['success'] = $cash_advance_payment->touch();

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
            if($datum->last_name != null and $datum->first_name != null
                and $datum->amount != null and $datum->date != null) {
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
                $cash_advance_payment = CashAdvancePayment::whereRaw('user_id = '.$user->id.' and date = "'.$date->format('Y-m-d').'"')->first();

                if(!$cash_advance_payment)
                    $cash_advance_payment = new CashAdvancePayment();

                $cash_advance_payment->user_id = $user->id;
                $cash_advance_payment->date = $date;
                $cash_advance_payment->amount = str_replace(',','',$datum->amount);
                $cash_advance_payment->date = $date->format('Y-m-d');

                if($cash_advance_payment->touch()){
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
                $return['message'] = "Cash advance payments were unsuccessfully imported";
            }
        }

        return $return;
    }

    /**
    * Delete a cash advance payment.
    *
    */
    public static function deleteCa(CashAdvancePayment $cash_advance_payment)
    {
        DB::beginTransaction();

        try {
            $return['success'] = $cash_advance_payment->delete();

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
    * Get a cash advance payment via a date.
    *
    */
    public static function getFromDate($user_id, $date)
    {
        return CashAdvancePayment::selectRaw(DB::raw('amount'))
        ->whereRaw(DB::raw('user_id = '.$user_id. ' and date = "'.$date.'"'))
        ->first();
    }

    /**
    * Get the total cash advance payments on a current given date.
    *
    */
    public static function getCaPayment($user_id, $begin, $end)
    {
        return CashAdvancePayment::whereUserId($user_id)->whereRaw(DB::raw('date >= "'.$begin.'" and date <= "'.$end.'"'))->sum('amount');
    }
}
