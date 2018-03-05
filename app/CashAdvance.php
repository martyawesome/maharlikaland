<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Http\Requests\AddEditCashAdvanceRequest;

use DB;
use DateTime;

class CashAdvance extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cash_advances';


    /**
    * Get the remaining ca of all users.
    *
    */
    public static function getAllRemaining()
    {
        $developer = Developer::getCurrentDeveloper();
        return CashAdvance::selectRaw('sum(cash_advances.amount) - IFNULL(total_ca_payments, 0) as amount, users.first_name, users.last_name, user_types.user_type as user_type')
        ->leftJoin('users','users.id','=','cash_advances.user_id')
        ->leftJoin('user_types','users.user_type_id','=','user_types.id')
        ->leftJoin(DB::raw('(select user_id, sum(amount) as total_ca_payments from cash_advance_payments left join (select * from users where developer_id = '.$developer->id.') as users on user_id  = users.id group by user_id) as total_payments'),'total_payments.user_id','=','cash_advances.user_id')
        ->whereRaw(DB::raw('users.developer_id = '.$developer->id))
        ->groupBy('cash_advances.user_id')
        ->get();
    }

    /**
    * Get all of the cash advances for the developer.
    *
    */
    public static function getAll()
    {
    	$developer = Developer::getCurrentDeveloper();
    	return CashAdvance::selectRaw('users.first_name, users.middle_name, users.last_name, cash_advances.*,user_types.user_type as user_type')
    	->leftJoin('users','users.id','=','cash_advances.user_id')
        ->leftJoin('user_types','users.user_type_id','=','user_types.id')
    	->whereRaw(DB::raw('users.developer_id = '.$developer->id))
    	->get();
    }

    /**
    * Get all users.
    *
    */
    public static function getUserForList(CashAdvance $cash_advance)
    {
        return User::selectRaw(DB::raw('CONCAT(users.last_name,", ",users.first_name," ",users.middle_name) as full_name, users.id, cash_advances.user_id'))
        ->leftJoin('cash_advances','users.id','=','cash_advances.user_id')
        ->whereRaw(DB::raw('cash_advances.user_id = '.$cash_advance->user_id))
        ->orderBy('full_name','asc')
        ->lists('full_name', 'id');
    }

    /**
    * Save or update a cash advance.
    *
    */
    public static function addEdit(CashAdvance $cash_advance, AddEditCashAdvanceRequest $request)
    {
    	DB::beginTransaction();

    	try {
    		$cash_advance->user_id = $request->get('user');
    		$cash_advance->date = $request->get('date');
    		$cash_advance->amount = $request->get('amount');

    		$return['success'] = $cash_advance->touch();

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
    * Save the cash advances imported from an excel file.
    *
    */
    public static function importLedger($data)
    {
        DB::beginTransaction();

        $counter = true;
        $row_counter = 1;
        
        foreach($data as $datum) {
            if($datum->last_name != null and $datum->first_name != null and $datum->amount != null and $datum->date != null) {
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
                $cash_advance = CashAdvance::whereRaw('user_id = '.$user->id.' and date = "'.$date->format('Y-m-d').'"')->first();

                if(!$cash_advance)
                    $cash_advance = new CashAdvance();

                $cash_advance->user_id = $user->id;
                $cash_advance->date = $date;
                $cash_advance->amount = str_replace(',','',$datum->amount);
                $cash_advance->date = $date->format('Y-m-d');

                if($cash_advance->touch()){
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
            if($return['message'] == "") {
                $return['message'] = "Cash advances were unsuccessfully imported";
            }
        }

        return $return;
    }

    /**
    * Delete a cash advance.
    *
    */
    public static function deleteCa(CashAdvance $cash_advance)
    {
        DB::beginTransaction();

        try {
            $return['success'] = $cash_advance->delete();

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
    * Get all of the salary rates for the developer.
    *
    */
    public static function getFromDate($user_id, $date)
    {
        return CashAdvance::selectRaw(DB::raw('amount'))
        ->whereRaw(DB::raw('user_id = '.$user_id. ' and date = "'.$date.'"'))
        ->first();
    }

    /**
    * Check if the employee has cash advances over his/her salary.
    * Compute for all of the cash advances, starting from the first cash advance, 
    * then subtract the total to the total of the salary dates. 
    *
    */
    public static function getRemainingCa($user_id, $date)
    {
        return CashAdvance::whereUserId($user_id)->where('date','<=',$date)->sum('amount');
    }
}
