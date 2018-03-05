<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

use DB;

use App\SalaryRate;

class Attendance extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attendances';

    /**
    * Show the attendance of the users on the given day.
    *
    */
    public static function getAttendancesOfAdminUsers($date)
    {
    	$developer = Developer::getCurrentDeveloper();

        return Attendance::selectRaw(DB::raw('attendances.*, users.first_name, users.last_name, users.id as user_id'))
        ->leftJoin('users','users.id','=','attendances.user_id')
    	->whereRaw(DB::raw('attendances.date = "'.$date.'" and users.developer_id = '.$developer->id.'
    	 	and (users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_ADMIN').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_SECRETARY').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_ACCOUNTANT').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_EMPLOYEE').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_CONSTRUCTION').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_GUARD').')'))
    	->get();
    }


    /**
    * Add.
    *
    */
    public static function addEditAttendance($date, $attendance, Request $request)
    {   
        DB::beginTransaction();

        try {
            $attendance->date = $date;
            $attendance->user_id = $request->get('user');
            $attendance->time_in = $request->get('time-in');
            $attendance->time_out = $request->get('time-out');

            $return['success'] = $attendance->touch();

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
    * Save the attendances imported from an excel file.
    *
    */
    public static function importFromExcel($date, $data)
    {
        DB::beginTransaction();

        $counter = true;
        $row_counter = 1;
        
        foreach($data as $datum) {
            if($datum->last_name != null and $datum->first_name != null and $datum->middle_name != null
                and $datum->time_in != null and $datum->time_out != null) {
                $user = User::getByWholeName($datum->first_name, $datum->middle_name, $datum->last_name);

                if(!$user){
                    DB::rollback();
                    $counter = false;
                    $return['success'] = false;
                    $return['message'] = "User not found in row ".($row_counter+1);
                    break;
                }

                // Check the attendance for the day has already been created
                $attendance = Attendance::whereRaw('user_id = '.$user->id.' and date = "'.$date.'"')->first();

                if(!$attendance)
                    $attendance = new Attendance();

                $attendance->user_id = $user->id;
                $attendance->date = $date;
                $attendance->time_in = $datum->time_in;
                $attendance->time_out = $datum->time_out;

                if($attendance->touch()){
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
                $return['message'] = "Attendances were unsuccessfully imported";
            }
        }

        return $return;
    }

    /**
    * Delete an attendance.
    *
    */
    public static function deleteAttendance(Attendance $attendance)
    {
        DB::beginTransaction();

        try {
            $return['success'] = $attendance->delete();

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
    * Get the users who do not yet have an attendance on a certain date.
    *
    */
    public static function getUsersWithoutAttendence($date)
    {
        $developer = Developer::getCurrentDeveloper();
        return User::selectRaw(DB::raw("CONCAT(users.last_name,', ',users.first_name) as full_name, users.id"))
        ->leftJoin('attendances','users.id','=','attendances.user_id')
        ->whereRaw(DB::raw('(users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_ADMIN').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_SECRETARY').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_ACCOUNTANT').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_EMPLOYEE').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_CONSTRUCTION').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_GUARD')).
            ') and users.developer_id = '.$developer->id)
        ->lists('full_name','id');
    }

    /**
    * Check if the user was present on the given date, and return the object.
    *
    */
    public static function getAttendanceOfUserOnDate($date, $user_id)
    {
        return Attendance::whereRaw(DB::raw('date = "'.$date.'" and user_id = '.$user_id))->first();
    }

    /**
    * Get the number of hours of the employee.
    *
    */
    public static function getHours(Attendance $attendance)
    {
        $time_in = strtotime($attendance->date . ' '. date("H:i", strtotime($attendance->time_in)));
        $time_out= strtotime($attendance->date . ' '. date("H:i", strtotime($attendance->time_out)));

        $lunch_start = strtotime($attendance->date . ' '. date("H:i", strtotime("12:00 PM")));
        $lunch_end = strtotime($attendance->date . ' '. date("H:i", strtotime("1:00 PM")));

        $day_start = strtotime($attendance->date . ' '. date("H:i", strtotime("8:00 AM")));
        $day_end = strtotime($attendance->date . ' '. date("H:i", strtotime("5:00 PM")));

        $day_start_ns = strtotime($attendance->date . ' '. date("H:i", strtotime("5:00 PM")));
        $day_end_ns = strtotime($attendance->date . ' '. date("H:i", strtotime("8:00 AM")));

        //return floor(abs($ts1 - $ts2) / 3600);

        if($time_in < $day_start) {
            $time_in = $day_start;
        }

        $user = User::find($attendance->user_id);

        // If user is not a guard
        if($user){
            if($user->user_type_id != config('constants.USER_TYPE_DEVELOPER_GUARD')) {
                // Half Day morning
                if($time_in < $lunch_start){
                    // Stayed only until lunch time
                    if($time_out <= $lunch_start){
                        return round(abs($time_out - $time_in) / 3600,2);
                    }
                    // Stayed after lunch 
                    else {
                        return round(abs($lunch_start - $time_in) / 3600,2) +
                        round(abs($time_out - $lunch_end) / 3600,2);
                    }
                }
                // Timed in after lunch
                else {
                    if ($time_out <= $lunch_end){
                        return 0;
                    } else {
                        return round(abs($time_out - $time_in) / 3600, 2);
                    }
                }
            } else {
                return round(abs($lunch_start - $day_start) / 3600,2) +
                        round(abs($day_end - $lunch_end) / 3600,2);
            }
        } else {
            return 0;
        }

    }

    /**
    * Get the sum of all the previous salaries for cash advance checking.
    *
    */
    public static function getPreviousSalary($user_id, $date)
    {
        $attendances = Attendance::whereRaw(DB::raw('date < "'.$date.'" and user_id = '.$user_id))->get();

        $total = 0;
        $hourly_rate = SalaryRate::getHourlyRate($user_id)->hourly_rate;
        foreach($attendances as $attendance){
            $working_hours = Attendance::getHours($attendance);
            $total += $working_hours * $hourly_rate;
        }

        return $total;
    }

}
