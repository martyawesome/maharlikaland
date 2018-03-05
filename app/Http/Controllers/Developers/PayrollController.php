<?php

namespace App\Http\Controllers\Developers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\AddEditSalaryRateRequest;
use App\Http\Requests\AddEditCashAdvanceRequest;
use App\Http\Requests\AddEditHolidayRequest;
use App\Http\Requests\AddEditPayrollDeductionRequest;
use App\Http\Requests\AddEditPayrollAdditionRequest;
use App\Http\Requests\SelectDateRangeRequest;
use App\Http\Controllers\Controller;

use App\SalaryRate;
use App\CashAdvance;
use App\CashAdvancePayment;
use App\PayrollDeduction;
use App\PayrollDeductionType;
use App\PayrollAddition;
use App\PayrollAdditionType;
use App\Holiday;
use App\Attendance;
use App\Developer;
use App\User;

use Hash;
use DateTime;
use DateInterval;
use DatePeriod;
use Excel;
use Input;
use DB;

class PayrollController extends Controller
{
    ////////////////////////////////////// Generate ////////////////////////////////////////////
    
    /**
    * Show the form for choosing the date range for the payroll generation.
    *
    */
    public function showSelectDateRange()
    {
        return view('developers.payroll.generate.dates');
    }

    /**
    * Generate the payroll according to the selected date range.
    *
    */
    public function generatePayroll(SelectDateRangeRequest $request)
    {
        $date_range = $request->get('date_range');
        $dates = explode(" - ",$date_range);

        $begin = new DateTime($dates[0]);
        $end   = new DateTime($dates[1]);

        $counter = '';
        for($i = $begin, $j = 0; $begin <= $end; $i->modify('+1 day'), $j++){
            $dates[$j] = $i->format("Y-m-d");
        }

        $hourly_rates_office = SalaryRate::getHourlyRatesOffice();
        $payroll_records_office = array();

        $hourly_rates_construction = SalaryRate::getHourlyRatesConstruction();
        $payroll_records_construction = array();

        $hourly_rates_guards = SalaryRate::getHourlyRatesGuard();
        $payroll_records_guards = array();


        for($i = 0; $i < count($dates); $i++){
            $holiday = Holiday::getHoliday($dates[$i]);
            
            $total = 0;
            $payroll_records_office[$i][0]['date'] = $dates[$i];
            $payroll_records_construction[$i][0]['date'] = $dates[$i];
            $payroll_records_guards[$i][0]['date'] = $dates[$i];

            // check if the current day is a holiday
            if(!$holiday){
                $payroll_records_office[$i][0]['holiday'] = "";
                $payroll_records_construction[$i][0]['holiday'] = "";
            } else {
                $payroll_records_office[$i][0]['holiday'] = $holiday->name;
                $payroll_records_construction[$i][0]['holiday'] = $holiday->name;
            }

            // computations for the total amount per day for per employee
            for($j = 0; $j < count($hourly_rates_office); $j++){
                $attendance = Attendance::getAttendanceOfUserOnDate($dates[$i], $hourly_rates_office[$j]->user_id);
                
                // get the attendance object of an employee for the day. If the object
                // is null, it is understood that he/she is absent
                if($attendance){
                    $hours_of_work = Attendance::getHours($attendance);
                } else {
                    $hours_of_work = 0;   
                }

                $cash_advance = CashAdvance::getFromDate($hourly_rates_office[$j]->user_id, $dates[$i]);
                $deduction = PayrollDeduction::getFromDate($hourly_rates_office[$j]->user_id, $dates[$i]);
                $addition = PayrollAddition::getFromDate($hourly_rates_office[$j]->user_id, $dates[$i]);
                
                $payroll_records_office[$i][$j+1]['user_id'] = $hourly_rates_office[$j]->user_id;
                $payroll_records_office[$i][$j+1]['employee_name'] = $hourly_rates_office[$j]->employee_name ? $hourly_rates_office[$j]->employee_name : "";
                $payroll_records_office[$i][$j+1]['hourly_rate'] = $hourly_rates_office[$j]->hourly_rate;
                $payroll_records_office[$i][$j+1]['rate'] = $hourly_rates_office[$j]->rate;
                $payroll_records_office[$i][$j+1]['hours_of_work'] = $hours_of_work;
                if($cash_advance){
                    $payroll_records_office[$i][$j+1]['cash_advance'] = $cash_advance->amount;
                } else {
                    $payroll_records_office[$i][$j+1]['cash_advance'] = 0;
                }
                if($deduction){
                    $payroll_records_office[$i][$j+1]['deduction'] = $deduction;
                } else {
                    $payroll_records_office[$i][$j+1]['deduction'] = 0;
                }
                if($addition){
                    $payroll_records_office[$i][$j+1]['addition'] = $addition;
                } else {
                    $payroll_records_office[$i][$j+1]['addition'] = 0;
                }

                // compute for the total number of hours or the overtime
                if($hours_of_work > config('constants.HOURS_OF_WORKING')){
                    $ot = $hours_of_work - config('constants.HOURS_OF_WORKING');
                    $hours_of_work =  $hours_of_work - $ot;
                } else {
                    $ot = 0;
                }
                $holiday = $holiday == null ? new Holiday() : $holiday;
                $payroll_records_office[$i][$j+1]['total'] = SalaryRate::getUpdatedSalaryRate($holiday, $hourly_rates_office[$j]->hourly_rate, $dates[$i], $hours_of_work, $ot);              
            }

            // computations for the total amount per day for per construction worker
            for($j = 0; $j < count($hourly_rates_construction); $j++){
                $attendance = Attendance::getAttendanceOfUserOnDate($dates[$i], $hourly_rates_construction[$j]->user_id);
                
                // get the attendance object of an a construction worker for the day. If the object
                // is null, it is understood that he/she is absent
                if($attendance){
                    $hours_of_work = Attendance::getHours($attendance);
                } else {
                    $hours_of_work = 0;   
                }

                $cash_advance = CashAdvance::getFromDate($hourly_rates_construction[$j]->user_id, $dates[$i]);
                $deduction = PayrollDeduction::getFromDate($hourly_rates_construction[$j]->user_id, $dates[$i]);
                $addition = PayrollAddition::getFromDate($hourly_rates_construction[$j]->user_id, $dates[$i]);
                
                $payroll_records_construction[$i][$j+1]['user_id'] = $hourly_rates_construction[$j]->user_id;
                $payroll_records_construction[$i][$j+1]['employee_name'] = $hourly_rates_construction[$j]->employee_name ? $hourly_rates_construction[$j]->employee_name : "";
                $payroll_records_construction[$i][$j+1]['hourly_rate'] = $hourly_rates_construction[$j]->hourly_rate;
                $payroll_records_construction[$i][$j+1]['rate'] = $hourly_rates_construction[$j]->rate;
                $payroll_records_construction[$i][$j+1]['hours_of_work'] = $hours_of_work;
                if($cash_advance){
                    $payroll_records_construction[$i][$j+1]['cash_advance'] = $cash_advance->amount;
                } else {
                    $payroll_records_construction[$i][$j+1]['cash_advance'] = 0;
                }if($deduction){
                    $payroll_records_construction[$i][$j+1]['deduction'] = $deduction;
                } else {
                    $payroll_records_construction[$i][$j+1]['deduction'] = 0;
                }
                if($addition){
                    $payroll_records_construction[$i][$j+1]['addition'] = $addition;
                } else {
                    $payroll_records_construction[$i][$j+1]['addition'] = 0;
                }


                // compute for the total number of hours or the overtime
                if($hours_of_work > config('constants.HOURS_OF_WORKING')){
                    $ot = $hours_of_work - config('constants.HOURS_OF_WORKING');
                    $hours_of_work =  $hours_of_work - $ot;
                } else {
                    $ot = 0;
                }
                $holiday = $holiday == null ? new Holiday() : $holiday;
                $payroll_records_construction[$i][$j+1]['total'] = SalaryRate::getUpdatedSalaryRateConstruction($hourly_rates_construction[$j]->hourly_rate, $hours_of_work, $ot);              
            }

            // computations for the total amount per day for per guard
            for($j = 0; $j < count($hourly_rates_guards); $j++){
                $attendance = Attendance::getAttendanceOfUserOnDate($dates[$i], $hourly_rates_guards[$j]->user_id);
                
                // get the attendance object of an a construction worker for the day. If the object
                // is null, it is understood that he/she is absent
                if($attendance){
                    $hours_of_work = Attendance::getHours($attendance);
                } else {
                    $hours_of_work = 0;   
                }

                $cash_advance = CashAdvance::getFromDate($hourly_rates_guards[$j]->user_id, $dates[$i]);
                $deduction = PayrollDeduction::getFromDate($hourly_rates_guards[$j]->user_id, $dates[$i]);
                $addition = PayrollAddition::getFromDate($hourly_rates_guards[$j]->user_id, $dates[$i]);
                
                $payroll_records_guards[$i][$j+1]['user_id'] = $hourly_rates_guards[$j]->user_id;
                $payroll_records_guards[$i][$j+1]['employee_name'] = $hourly_rates_guards[$j]->employee_name ? $hourly_rates_guards[$j]->employee_name : "";
                $payroll_records_guards[$i][$j+1]['hourly_rate'] = $hourly_rates_guards[$j]->hourly_rate;
                $payroll_records_guards[$i][$j+1]['rate'] = $hourly_rates_guards[$j]->rate;
                $payroll_records_guards[$i][$j+1]['hours_of_work'] = $hours_of_work;
                if($cash_advance){
                    $payroll_records_guards[$i][$j+1]['cash_advance'] = $cash_advance->amount;
                } else {
                    $payroll_records_guards[$i][$j+1]['cash_advance'] = 0;
                }if($deduction){
                    $payroll_records_guards[$i][$j+1]['deduction'] = $deduction;
                } else {
                    $payroll_records_guards[$i][$j+1]['deduction'] = 0;
                }
                if($addition){
                    $payroll_records_guards[$i][$j+1]['addition'] = $addition;
                } else {
                    $payroll_records_guards[$i][$j+1]['addition'] = 0;
                }


                // compute for the total number of hours or the overtime
                if($hours_of_work > config('constants.HOURS_OF_WORKING')){
                    $ot = $hours_of_work - config('constants.HOURS_OF_WORKING');
                    $hours_of_work =  $hours_of_work - $ot;
                } else {
                    $ot = 0;
                }
                $holiday = $holiday == null ? new Holiday() : $holiday;
                $payroll_records_guards[$i][$j+1]['total'] = SalaryRate::getUpdatedSalaryRateConstruction($hourly_rates_guards[$j]->hourly_rate, $hours_of_work, $ot);              
            }


        }
        
        // Calculate if the employees have previous cash advances that should be paid.
        $remaining_ca_office = [];
        for($i = 1; $i < count($payroll_records_office[0]); $i++){
            $remaining_ca_office[$i-1] = CashAdvancePayment::getCaPayment($payroll_records_office[0][$i]['user_id'], $dates[0], $dates[count($dates)-1]);
        }

        // Calculate if the construction workers have previous cash advances that should be paid.
        $remaining_ca_construction = [];
        for($i = 1; $i < count($payroll_records_construction[0]); $i++){
            $remaining_ca_construction[$i-1] = CashAdvancePayment::getCaPayment($payroll_records_construction[0][$i]['user_id'], $dates[0], $dates[count($dates)-1]);
        }

        // Calculate if the guards have previous cash advances that should be paid.
        $remaining_ca_guards = [];
        for($i = 1; $i < count($payroll_records_guards[0]); $i++){
            $remaining_ca_guards[$i-1] = CashAdvancePayment::getCaPayment($payroll_records_guards[0][$i]['user_id'], $dates[0], $dates[count($dates)-1]);
        }

        $formatted_begin = new DateTime($dates[0]);
        $formatted_begin = $formatted_begin->format('F j, Y'); 

        $formatted_end = new DateTime($dates[count($dates)-1]);
        $formatted_end = $formatted_end->format('F j, Y'); 

        //dd($payroll_records);

        return view('developers.payroll.generate.payroll', compact('payroll_records_office','payroll_records_construction','payroll_records_guards','formatted_begin',
            'formatted_end','remaining_ca_office','remaining_ca_construction','remaining_ca_guards'));
    }


    ////////////////////////////////////// Salary Rates ////////////////////////////////////////////

    /**
    * Show all salary rates.
    *
    */ 
    public function showSalaryRates()
    {
        $salary_rates = SalaryRate::getAll();
        return view('developers.payroll.salary_rates.all', compact('salary_rates'));
    }

    /**
    * Show the form for adding a salary rate.
    *
    */
    public function showAddSalaryRate()
    {
        $salary_rate = new SalaryRate();
        $users = SalaryRate::getUsersWithoutSalaryRate();
        return view('developers.payroll.salary_rates.add', compact('salary_rate','users'));
    }

    /**
    * Add a salary rate.
    *
    */
    public function addSalaryRate(AddEditSalaryRateRequest $request)
    {
        $return = SalaryRate::addEditSalaryRate(new SalaryRate(), $request);
        
        if($return["success"]) {
            return redirect(route('salary_rates'))->withSuccess('Salary rate was successfully added');
        } else {
            return redirect(route('add_salary_rate'))->withDanger('Salary rate was unsuccessfully added');
        }
    }

    /**
    * Show the form for editing a salary rate.
    *
    */
    public function showEditSalaryRate(SalaryRate $salary_rate)
    {
        $users = SalaryRate::getUsersSalaryRateForList($salary_rate);
        return view('developers.payroll.salary_rates.edit', compact('salary_rate','users'));
    }

    /**
    * Add a salary rate.
    *
    */
    public function editSalaryRate(AddEditSalaryRateRequest $request, SalaryRate $salary_rate)
    {
        $return = SalaryRate::addEditSalaryRate($salary_rate, $request);
        
        if($return["success"]) {
            return redirect(route('salary_rates'))->withSuccess('Salary rate was successfully edited');
        } else {
            return redirect(route('edit_salary_rate'))->withDanger('Salary rate was unsuccessfully edited');
        }
    }

    /**
    * Delete a salary rate.
    *
    */
    public function deleteSalaryRate(SalaryRate $salary_rate, Request $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = SalaryRate::deleteSalaryRate($salary_rate);
            if($return["success"]) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        }  
    }

    /**
    * Import the users from an Excel file and save the data to the database.
    *
    */
    public static function importSalaryRatesFromExcel()
    {
        if(Input::hasFile('excel')){
            $path = Input::file('excel')->getRealPath();
            $data = Excel::selectSheetsByIndex(0)->load($path, function($reader) {
                $reader->formatDates(false);
            })->get();
            $return = SalaryRate::importFromExcel($data);

            if($return["success"]){
                return redirect(route('salary_rates'))->withSuccess('Salary Rates were successfully imported');
            } else {
                return redirect(route('salary_rates'))->withDanger($return['message']);
            }
        } else {
            return redirect(route('salary_rates'))->withDanger('No file selected');
        }
    }

    ////////////////////////////////////// Cash Advances ////////////////////////////////////////////

    /**
    * Show all cash advances.
    *
    */ 
    public function showCashAdvances()
    {
        $cash_advances = CashAdvance::getAllRemaining();
        return view('developers.payroll.cash_advances.all', compact('cash_advances'));
    }

    ////////////////////////////////////// Cash Advances Credit ////////////////////////////////////////////

    /**
    * Show all cash advances credit.
    *
    */ 
    public function showCashAdvancesCredit()
    {
        $cash_advances = CashAdvance::getAll();
        return view('developers.payroll.cash_advances.credit.all', compact('cash_advances'));
    }

    /**
    * Show the form for adding a cash advance credit.
    *
    */
    public function showAddCashAdvanceCredit()
    {
        $cash_advance = new CashAdvance();
        $users = User::getListAdminUsersOfDeveloper();
        return view('developers.payroll.cash_advances.credit.add', compact('cash_advance','users'));
    }

    /**
    * Add a cash advance credit.
    *
    */
    public function addCashAdvanceCredit(AddEditCashAdvanceRequest $request)
    {
        $return = CashAdvance::addEdit(new CashAdvance(), $request);
        
        if($return["success"]) {
            return redirect(route('cash_advances_credit'))->withSuccess('Cash advance was successfully added');
        } else {
            return redirect(route('add_cash_advance_credit'))->withDanger('Cash advance was unsuccessfully added');
        }
    }

    /**
    * Import the cash advances credit from an Excel file and save the data to the database.
    *
    */
    public static function importCaCreditFromExcel()
    {
        if(Input::hasFile('excel')){
            $path = Input::file('excel')->getRealPath();
            $data = Excel::selectSheetsByIndex(0)->load($path, function($reader) {
                $reader->formatDates(false);
            })->get();
            $return = CashAdvance::importLedger($data);

            if($return["success"]){
                return redirect(route('cash_advances_credit'))->withSuccess('Cash advances credit were successfully imported');
            } else {
                return redirect(route('cash_advances_credit'))->withDanger($return['message']);
            }
        } else {
            return redirect(route('cash_advances_credit'))->withDanger('No file selected');
        }
    }


    /**
    * Show the form for editing a salary rate.
    *
    */
    public function showEditCashAdvanceCredit(CashAdvance $cash_advance)
    {
        $users = CashAdvance::getUserForList($cash_advance);
        return view('developers.payroll.cash_advances.credit.edit', compact('cash_advance','users'));
    }

    /**
    * Add a cash advance.
    *
    */
    public function editCashAdvanceCredit(AddEditCashAdvanceRequest $request, CashAdvance $cash_advance)
    {
        $return = CashAdvance::addEdit($cash_advance, $request);
        
        if($return["success"]) {
            return redirect(route('cash_advances_credit'))->withSuccess('Cash advance was successfully edited');
        } else {
            return redirect(route('edit_cash_advance_credit'))->withDanger('Cash advance was unsuccessfully edited');
        }
    }

    /**
    * Delete a cash advance.
    *
    */
    public function deleteCashAdvanceCredit(CashAdvance $cash_advance, Request $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = CashAdvance::deleteCa($cash_advance);
            if($return["success"]) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        }  
    }

    ////////////////////////////////////// Cash Advances Payments /////////////////////////////////////////

    /**
    * Show all cash advance payments.
    *
    */ 
    public function showCashAdvancesPayments()
    {
        $cash_advances = CashAdvancePayment::getAll();
        return view('developers.payroll.cash_advances.payments.all', compact('cash_advances'));
    }

    /**
    * Show the form for adding a cash advance payment.
    *
    */
    public function showAddCashAdvancePayment()
    {
        $cash_advance = new CashAdvancePayment();
        $users = User::getListAdminUsersOfDeveloper();
        return view('developers.payroll.cash_advances.payments.add', compact('cash_advance','users'));
    }

    /**
    * Add a cash advance payment. 
    *
    */
    public function addCashAdvancePayment(AddEditCashAdvanceRequest $request)
    {
        $return = CashAdvancePayment::addEdit(new CashAdvancePayment(), $request);
        
        if($return["success"]) {
            return redirect(route('cash_advances_payments'))->withSuccess('Cash advance payment was successfully added');
        } else {
            return redirect(route('cash_advances_payments'))->withDanger('Cash advance payment was unsuccessfully added');
        }
    }

    /**
    * Import the cash advances payments from an Excel file and save the data to the database.
    *
    */
    public static function importCaPaymentsFromExcel()
    {
        if(Input::hasFile('excel')){
            $path = Input::file('excel')->getRealPath();
            $data = Excel::selectSheetsByIndex(0)->load($path, function($reader) {
                $reader->formatDates(false);
            })->get();
            
            $return = CashAdvancePayment::importLedger($data);

            if($return["success"]){
                return redirect(route('cash_advances_payments'))->withSuccess('Cash advances payments were successfully imported');
            } else {
                return redirect(route('cash_advances_payments'))->withDanger($return['message']);
            }
        } else {
            return redirect(route('cash_advances_payments'))->withDanger('No file selected');
        }
    }


    /**
    * Show the form for editing a salary rate.
    *
    */
    public function showEditCashAdvancePayment(CashAdvancePayment $cash_advance)
    {
        $users = CashAdvance::getUserForList($cash_advance);
        return view('developers.payroll.cash_advances.credit.edit', compact('cash_advance','users'));
    }

    /**
    * Add a cash advance payment.
    *
    */
    public function editCashAdvancePayment(AddEditCashAdvanceRequest $request, CashAdvancePayment $cash_advance)
    {
        $return = CashAdvancePayment::addEdit($cash_advance, $request);
        
        if($return["success"]) {
            return redirect(route('cash_advances_payments'))->withSuccess('Cash advance payment was successfully edited');
        } else {
            return redirect(route('edit_cash_advance_payment'))->withDanger('Cash advance advance was unsuccessfully edited');
        }
    }

    /**
    * Delete a cash advance.
    *
    */
    public function deleteCashAdvance(CashAdvance $cash_advance, Request $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = CashAdvance::deleteCa($cash_advance);
            if($return["success"]) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        }  
    }

    ////////////////////////////////////// Deductions ////////////////////////////////////////////

    /**
    * Show all deductions.
    *
    */ 
    public function showDeductions()
    {
        $deductions = PayrollDeduction::getAll();
        return view('developers.payroll.deductions.all', compact('deductions'));
    }

    /**
    * Show the form for adding a deduction.
    *
    */
    public function showAddDeduction()
    {
        $deduction = new PayrollDeduction();
        $users = User::getListAdminUsersOfDeveloper();
        $deduction_types = PayrollDeductionType::getAll();
        return view('developers.payroll.deductions.add', compact('deduction','users','deduction_types'));
    }

    /**
    * Add a deduction.
    *
    */
    public function addDeduction(AddEditPayrollDeductionRequest $request)
    {
        $return = PayrollDeduction::addEdit(new PayrollDeduction(), $request);
        
        if($return["success"]) {
            return redirect(route('payroll_deductions'))->withSuccess('Payroll deduction was successfully added');
        } else {
            return redirect(route('add_payroll_deduction'))->withDanger('Payroll deduction was unsuccessfully added');
        }
    }

    /**
    * Import the deductions from an Excel file and save the data to the database.
    *
    */
    public static function importDeductionsFromExcel()
    {
        if(Input::hasFile('excel')){
            $path = Input::file('excel')->getRealPath();
            $data = Excel::selectSheetsByIndex(0)->load($path, function($reader) {
                $reader->formatDates(false);
            })->get();
            $return = PayrollDeduction::importLedger($data);

            if($return["success"]){
                return redirect(route('payroll_deductions'))->withSuccess('Payroll deductions were successfully imported');
            } else {
                return redirect(route('payroll_deductions'))->withDanger($return['message']);
            }
        } else {
            return redirect(route('payroll_deductions'))->withDanger('No file selected');
        }
    }


    /**
    * Show the form for editing a deduction.
    *
    */
    public function showEditDeduction(PayrollDeduction $deduction)
    {
        $users = User::getListAdminUsersOfDeveloper();
        $deduction_types = PayrollDeductionType::getAll();
        return view('developers.payroll.deductions.edit', compact('deduction','users','deduction_types'));
    }

    /**
    * Add a deduction.
    *
    */
    public function editDeduction(AddEditPayrollDeductionRequest $request, PayrollDeduction $deduction)
    {
        $return = PayrollDeduction::addEdit($deduction, $request);
        
        if($return["success"]) {
            return redirect(route('payroll_deductions'))->withSuccess('Payroll deduction was successfully edited');
        } else {
            return redirect(route('edit_payroll_deduction'))->withDanger('Payroll deduction was unsuccessfully edited');
        }
    }

    /**
    * Delete a deduction.
    *
    */
    public function deleteDeduction(PayrollDeduction $deduction, Request $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = PayrollDeduction::deleteDeduction($deduction);
            if($return["success"]) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        }  
    }

    ////////////////////////////////////// Additions ////////////////////////////////////////////

    /**
    * Show all additions.
    *
    */ 
    public function showAdditions()
    {
        $additions = PayrollAddition::getAll();
        return view('developers.payroll.additions.all', compact('additions'));
    }

    /**
    * Show the form for adding a addition.
    *
    */
    public function showAddAddition()
    {
        $addition = new PayrollAddition();
        $users = User::getListAdminUsersOfDeveloper();
        $addition_types = PayrollAdditionType::getAll();
        return view('developers.payroll.additions.add', compact('addition','users','addition_types'));
    }

    /**
    * Add an addition.
    *
    */
    public function addAddition(AddEditPayrollAdditionRequest $request)
    {
        $return = PayrollAddition::addEdit(new PayrollAddition(), $request);
        
        if($return["success"]) {
            return redirect(route('payroll_additions'))->withSuccess('Payroll addition was successfully added');
        } else {
            return redirect(route('add_payroll_addition'))->withDanger('Payroll addition was unsuccessfully added');
        }
    }

    /**
    * Import the additions from an Excel file and save the data to the database.
    *
    */
    public static function importAdditionsFromExcel()
    {
        if(Input::hasFile('excel')){
            $path = Input::file('excel')->getRealPath();
            $data = Excel::selectSheetsByIndex(0)->load($path, function($reader) {
                $reader->formatDates(false);
            })->get();
            $return = PayrollAddition::importLedger($data);

            if($return["success"]){
                return redirect(route('payroll_additions'))->withSuccess('Payroll additions were successfully imported');
            } else {
                return redirect(route('payroll_additions'))->withDanger($return['message']);
            }
        } else {
            return redirect(route('payroll_additions'))->withDanger('No file selected');
        }
    }


    /**
    * Show the form for editing an addition.
    *
    */
    public function showEditAddition(PayrollAddition $addition)
    {
        $users = User::getListAdminUsersOfDeveloper();
        $addition_types = PayrollAdditionType::getAll();
        return view('developers.payroll.additions.edit', compact('addition','users','addition_types'));
    }

    /**
    * Edit an addition.
    *
    */
    public function editAddition(AddEditPayrollAdditionRequest $request, PayrollAddition $addition)
    {
        $return = PayrollAddition::addEdit($addition, $request);
        
        if($return["success"]) {
            return redirect(route('payroll_additions'))->withSuccess('Payroll addition was successfully edited');
        } else {
            return redirect(route('edit_payroll_addition'))->withDanger('Payroll addition was unsuccessfully edited');
        }
    }

    /**
    * Delete an addition.
    *
    */
    public function deleteAddition(PayrollAddition $addition, Request $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = PayrollAddition::deleteAddition($addition);
            if($return["success"]) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        }  
    }

    ////////////////////////////////////// Holidays ////////////////////////////////////////////

    /**
    * Show all holidays.
    *
    */ 
    public function showHolidays()
    {
        $holidays = Holiday::getAll();
        return view('developers.payroll.holidays.all', compact('holidays'));
    }

    /**
    * Show the form for adding a holiday.
    *
    */
    public function showAddHoliday()
    {
        $holiday = new Holiday();
        return view('developers.payroll.holidays.add', compact('holiday'));
    }

    /**
    * Add a holiday.
    *
    */
    public function addHoliday(AddEditHolidayRequest $request)
    {
        $return = Holiday::addEditHoliday(new Holiday(), $request);
        
        if($return["success"]) {
            return redirect(route('holidays'))->withSuccess('Holiday was successfully added');
        } else {
            return redirect(route('add_holiday'))->withDanger('Holiday was unsuccessfully added');
        }
    }

    /**
    * Show the form for editing a holiday.
    *
    */
    public function showEditHoliday(Holiday $holiday)
    {
        return view('developers.payroll.holidays.edit', compact('holiday'));
    }

    /**
    * Add a holiday.
    *
    */
    public function editHoliday(AddEditHolidayRequest $request, Holiday $holiday)
    {
        $return = Holiday::addEditHoliday($holiday, $request);
        
        if($return["success"]) {
            return redirect(route('holidays'))->withSuccess('Holiday was successfully edited');
        } else {
            return redirect(route('edit_holiday'))->withDanger('Holiday was unsuccessfully edited');
        }
    }

    /**
    * Delete a holiday.
    *
    */
    public function deleteHoliday(Holiday $holiday, Request $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = Holiday::deleteHoliday($holiday);
            if($return["success"]) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        }  
    }

    /**
    * Sync holidays. Call API from Google to get the list of holidays and delete holidays of the
    * developer then save the data retrieved from Google.
    *
    */
    public function syncHolidays(Request $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $json = file_get_contents("https://www.googleapis.com/calendar/v3/calendars/en.philippines%23holiday%40group.v.calendar.google.com/events?key=".config('constants.API_KEY_GOOGLE_CALENDAR')); // this WILL do an http request for you
            $data = json_decode($json); 
            $return = Holiday::syncHolidays($data);
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
