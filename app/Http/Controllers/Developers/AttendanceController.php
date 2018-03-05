<?php

namespace App\Http\Controllers\Developers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Attendance;
use App\User;
use App\Developer;

use DateTime;
use Hash;
use Auth;
use Input;
use Excel;

class AttendanceController extends Controller
{
    /**
     * Display the calendar.
     *
    */
    public static function showCalendar()
    {
        return view('developers.attendance.calendar');   
    }

    /**
    * Display the attendance of the users of the selected date from the calendar.
    *
    */
    public static function showAttendancesOnDay($date)
    {
    	$attendances = Attendance::getAttendancesOfAdminUsers($date);

        foreach($attendances as $attendance){
            $attendance->hours = Attendance::getHours($attendance);
        }

        $formatted_date = new DateTime($date);
    	$formatted_date = $formatted_date->format('F j, Y');
    	return view('developers.attendance.users', compact('date','attendances','formatted_date'));
    }

    /**
    * Show the form for adding the attendance of a user.
    *
    */
    public static function showAddAttendance($date)
    {
        $users = Attendance::getUsersWithoutAttendence($date);

        $attendance = new Attendance();

        $formatted_date = new DateTime($date);
        $formatted_date = $formatted_date->format('F j, Y');   
        return view('developers.attendance.add', compact('date','attendance','formatted_date','users'));
    }

    /**
    * Add attendance of a user.
    *
    */
    public static function addAttendance($date, Request $request)
    {
        $attendance = new Attendance();
        $return = Attendance::addEditAttendance($date, $attendance, $request);
        
        if($return["success"]) {
            return redirect(route('attendances_date', $date))->withSuccess('Attendance successfully added');
        } else {
            return redirect(route('attendances_date', $date))->withDanger('Attendance unsuccessfully added');
        }
    }

    /**
    * Import the attendances from an Excel file and save the data to the database.
    *
    */
    public static function importFromExcel($date)
    {
        if(Input::hasFile('excel')){
            $path = Input::file('excel')->getRealPath();
            $data = Excel::selectSheetsByIndex(0)->load($path, function($reader) {
                $reader->formatDates(false);
            })->get();
            $return = Attendance::importFromExcel($date, $data);

            if($return["success"]){
                return redirect(route('attendances_date', array($date)))->withSuccess('Attendances were successfully imported');
            } else {
                return redirect(route('attendances_date', array($date)))->withDanger($return['message']);
            }
        } else {
            return redirect(route('attendances_date', array($date)))->withDanger('No file selected');
        }
    }


    /**
    * Show the form for editing the attendance of a user.
    *
    */
    public static function showEditAttendance($date, $attendance)
    {
        $users = User::getListAdminUsersOfDeveloper();

        $formatted_date = new DateTime($date);
        $formatted_date = $formatted_date->format('F j, Y');   
        return view('developers.attendance.edit', compact('date','attendance','formatted_date','users'));
    }

    /**
    * Add attendance of a user.
    *
    */
    public static function editAttendance($date, Attendance $attendance, Request $request)
    {
        $return = Attendance::addEditAttendance($date, $attendance, $request);
        
        if($return["success"]) {
            return redirect(route('attendances_date', $date))->withSuccess('Attendance successfully edited');
        } else {
            return redirect(route('attendances_date', $date))->withDanger('Attendance unsuccessfully edited');
        }
    }

    /**
    * Delete an attendance.
    *
    */
    public static function deleteAttendance($date, Attendance $attendance, Request $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = Attendance::deleteAttendance($attendance);
            if($return["success"]) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        }   
    }

}
