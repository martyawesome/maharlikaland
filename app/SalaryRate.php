<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Http\Requests\AddEditSalaryRateRequest;

use App\Developer;
use App\User;
use App\Holiday;

use DB;

class SalaryRate extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'salary_rates';

    /**
    * Get all of the salary rates for the developer.
    *
    */
    public static function getAll()
    {
    	$developer = Developer::getCurrentDeveloper();
    	return SalaryRate::selectRaw('users.first_name, users.middle_name, users.last_name, salary_rates.*')
    	->leftJoin('users','users.id','=','salary_rates.user_id')
    	->whereRaw(DB::raw('users.developer_id = '.$developer->id))
    	->get();
    }

    /**
    * Get all users who still doesn't have a salary rate.
    *
    */
    public static function getUsersWithoutSalaryRate()
    {
        $developer = Developer::getCurrentDeveloper();
    	return User::selectRaw(DB::raw('CONCAT(users.last_name,", ",users.first_name) as full_name, users.id, salary_rates.user_id'))
    	->leftJoin('salary_rates','users.id','=','salary_rates.user_id')
    	->whereRaw(DB::raw('(users.user_type_id = '.config('constants.USER_TYPE_ADMIN').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_ADMIN').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_SECRETARY').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_ACCOUNTANT').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_EMPLOYEE').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_CONSTRUCTION').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_GUARD').
            ') and (salary_rates.user_id is NULL and users.developer_id = '.$developer->id.')'))
    	->lists('full_name', 'id');
    }

    /**
    * Get all users who still doesn't have a salary rate.
    *
    */
    public static function getUsersSalaryRateForList(SalaryRate $salary_rate)
    {
        $developer = Developer::getCurrentDeveloper();
        return User::selectRaw(DB::raw('CONCAT(users.last_name,", ",users.first_name," ",users.middle_name) as full_name, users.id, salary_rates.user_id'))
        ->leftJoin('salary_rates','users.id','=','salary_rates.user_id')
        ->whereRaw(DB::raw('salary_rates.user_id = '.$salary_rate->user_id.' and users.developer_id = '.$developer->id))
        ->lists('full_name', 'id');
    }

    /**
    * Save the newly added salary rate.
    *
    */
    public static function addEditSalaryRate(SalaryRate $salary_rate, AddEditSalaryRateRequest $request)
    {
    	DB::beginTransaction();

    	try {
    		$salary_rate->user_id = $request->get('user');
    		$salary_rate->rate = $request->get('rate');

    		$return['success'] = $salary_rate->touch();

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
    * Delete a salary rate.
    *
    */
    public static function deleteSalaryRate(SalaryRate $salary_rate)
    {
        DB::beginTransaction();

        try {
            $return['success'] = $salary_rate->delete();

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
    * Get the hourly rates of the employees. The hours per day of the office
    * is stated in the constants file.
    *
    */
    public static function getHourlyRatesOffice()
    {
        $working_hours = config('constants.HOURS_OF_WORKING');

        $developer = Developer::getCurrentDeveloper();
        return SalaryRate::selectRaw(DB::raw('(salary_rates.rate / '.$working_hours.') 
            as hourly_rate, salary_rates.rate, users.id as user_id, CONCAT(users.last_name,", ",users.first_name) as employee_name'))
            ->leftJoin('users','users.id','=','salary_rates.user_id')
            ->whereRaw(DB::raw('users.developer_id = '.$developer->id.'
            and (users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_SECRETARY').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_ACCOUNTANT').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_EMPLOYEE').')'))
            ->get();
    }

    /**
    * Get the hourly rates of the construction workers. The hours per day of the office
    * is stated in the constants file.
    *
    */
    public static function getHourlyRatesConstruction()
    {
        $working_hours = config('constants.HOURS_OF_WORKING');

        $developer = Developer::getCurrentDeveloper();
        return SalaryRate::selectRaw(DB::raw('(salary_rates.rate / '.$working_hours.') 
            as hourly_rate, salary_rates.rate, users.id as user_id, CONCAT(users.last_name,", ",users.first_name) as employee_name'))
            ->leftJoin('users','users.id','=','salary_rates.user_id')
            ->whereRaw(DB::raw('users.developer_id = '.$developer->id.'
            and (users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_CONSTRUCTION').')'))
            ->get();
    }

    /**
    * Get the hourly rates of the guards. The hours per day of the office
    * is stated in the constants file.
    *
    */
    public static function getHourlyRatesGuard()
    {
        $working_hours = config('constants.HOURS_OF_WORKING');

        $developer = Developer::getCurrentDeveloper();
        return SalaryRate::selectRaw(DB::raw('(salary_rates.rate / '.$working_hours.') 
            as hourly_rate, salary_rates.rate, users.id as user_id, CONCAT(users.last_name,", ",users.first_name) as employee_name'))
            ->leftJoin('users','users.id','=','salary_rates.user_id')
            ->whereRaw(DB::raw('users.developer_id = '.$developer->id.'
            and (users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_GUARD').')'))
            ->get();
    }

    /**
    * Get the hourly rate of an employee. The hours per day of the office
    * is stated in the constants file.
    *
    */
    public static function getHourlyRate($user_id)
    {
        $working_hours = config('constants.HOURS_OF_WORKING');

        $developer = Developer::getCurrentDeveloper();
        return SalaryRate::selectRaw(DB::raw('(salary_rates.rate / '.$working_hours.') 
            as hourly_rate, salary_rates.rate, users.id as user_id, CONCAT(users.last_name,", ",users.first_name) as employee_name'))
            ->leftJoin('users','users.id','=','salary_rates.user_id')
            ->whereUserId($user_id)
            ->first();
    }

    /**
    * Update the salary rate of an employee. Check if the current day is a holiday.
    *
    */
    public static function getUpdatedSalaryRate(Holiday $holiday, $hourly_rate, $date, $hours_of_work, $ot)
    {
        // check if the current day is a Sunday
        $is_sunday = date('w', strtotime($date)) == 0 ? true : false;

        // computations of the total salary for the day
        if($holiday == new Holiday()){
            return ($hourly_rate * $hours_of_work) + ($hourly_rate * 1.25 * $ot);
        } else {
            if($holiday->type == config('constants.HOLIDAY_REGULAR')){
                /**
                * If an employee did not work, he/she shall be paid 100 percent of his/her
                * salary for that day. The COLA is included in the computation of holiday pay. 
                * Sample computation: [(Daily rate + COLA) x 100 percent];
                */
                if($hours_of_work == 0){
                    return $hourly_rates[$j]->hourly_rate * $hours_of_work;
                } else {
                    /**
                    * If an employee worked, he/she shall be paid 200 percent of his/her regular 
                    * salary for that day for the first eight hours. The COLA is also included
                    * in the computation of holiday pay. Sample computation: [(Daily rate + COLA) x 
                    * 200 percent]. If an employee worked in excess of eight hours (overtime work),
                    * he/she shall be paid an additional 30 percent of his/her hourly rate on said day.
                    * Sample computation: [Hourly rate of the basic daily wage x 200 percent x 130 
                    * percent x number of hours worked];
                    */
                    if(!$is_sunday){
                        return ($hourly_rates[$j]->hourly_rate * 2 * $hours_of_work)
                        + ($hourly_rates[$j]->hourly_rate * 2 * 1.3 * $ot);
                    }
                    /**
                    * If an employee worked during a regular holiday that also falls on his/her rest
                    * day, he/she shall be paid an additional 30 percent of his/her daily rate of
                    * 200 percent. Sample computation: [(Daily rate + COLA) x 200 percent] + 
                    * (30 percent [Daily rate x 200 percent)]; and   • If an employee worked in excess
                    * of eight hours (overtime work) during a regular holiday that also falls on his/her
                    * rest day, he/she shall be paid an additional 30 percent of his/her hourly rate on
                    * said day. Sample computation: (Hourly rate of the basic daily wage x 200 percent x
                    * 130 percent x 130 percent x number of hours worked).
                    */
                    else {
                        return ($hourly_rates[$j]->hourly_rate * 2 * $hours_of_work)
                        + ($hourly_rates[$j]->hourly_rate * 2 * $hours_of_work) * 0.3
                        + ($hourly_rates[$j]->hourly_rate * 2 * 1.3 * 1.3 * $ot);
                    }
                }
            }
            else if($holiday->type == config('constants.HOLIDAY_SPECIAL_NON_WORKING')){
                /**
                * For work done during the special day, the workers shall be paid an additional 30 percent
                * of their daily rate on the first eight hours of work. The ‘Daily rate x 130 percent
                * plus COLA’ scheme will be observed. For work done in excess of eight hours (overtime work),
                * the workers will be paid an additional 30 percent of their hourly rate on said day.
                * The computation will be: hourly rate of the basic daily wage x 130 percent x 
                * 130 percent x number of hours worked.
                */
                if(!$is_sunday){
                    return ($hourly_rates[$j]->hourly_rate * 1.3 * $hours_of_work)
                    + ($hourly_rates[$j]->hourly_rate * 1.3 * 1.3 * $ot);
                }
                /**
                * For work done during a special day that also falls on the workers’ rest day, they
                * shall be paid an additional 50 percent of their daily rate on the first eight hours
                * of work, thus, the ‘Daily rate x 150 percent + COLA’ computation will apply.
                * For work done in excess of eight hours (overtime work) during a special day that also
                * falls on the workers’ rest day, they shall be paid an additional 30 percent of their
                * hourly rate on said day, or a computation of ‘hourly rate of the basic daily wage x
                * 150 percent x 130 percent x number of hours worked.(DOLE)
                */
                else {
                    return ($hourly_rates[$j]->hourly_rate * 1.5 * $hours_of_work)
                    + ($hourly_rates[$j]->hourly_rate * 1.5 * 1.3 * $ot);
                }
            }
        }
    }

    /**
    * Update the salary rate of an construction worker. No bonuses for holidays.
    *
    */
    public static function getUpdatedSalaryRateConstruction($hourly_rate, $hours_of_work, $ot)
    {
       return ($hourly_rate * $hours_of_work) + ($hourly_rate * $ot);
    }

    /**
    * Save the salary rates imported from an excel file.
    *
    */
    public static function importFromExcel($data)
    {
        DB::beginTransaction();

        $counter = true;
        $row_counter = 1;
        $developer = Developer::getCurrentDeveloper();

        
        foreach($data as $datum) {
            if($datum->last_name != null and $datum->first_name != null and $datum->rate != null) {
                $user = User::getByWholeName($datum->first_name, $datum->middle_name, $datum->last_name);

                if($user){
                    $salary_rate = new SalaryRate();
                    $salary_rate->user_id = $user->id;
                    $salary_rate->rate = $datum->rate;

                    if($salary_rate->touch()){
                        $counter = true;
                    } else{
                        $counter = false;
                        break;
                    }
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
                $return['message'] = "Salary Rates were unsuccessfully imported";
            }
        }

        return $return;
    }


}
