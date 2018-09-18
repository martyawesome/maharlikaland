<?php

namespace App\Http\Controllers\Developers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Developer;
use App\InstallmentAccountLedger;
use App\InstallmentAccountLedgerDetail;
use App\VoucherDetail;
use App\User;

use Auth;
use Hash;


class BaseController extends Controller
{
    
    /**
     * Display dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        //$ma = 11520;
        $ma = 11815;
        $compound_counter = 52;
        $next_penalty_base = $ma + $ma * config('constants.PENALTY_PERCENTAGE');
        $penalties[0] = $ma * config('constants.PENALTY_PERCENTAGE');
        for($i=1;$i<=$compound_counter;$i++) {
            $next_penalty = $next_penalty_base * config('constants.PENALTY_PERCENTAGE'); 
            $next_penalty_base += $next_penalty;
            //$penalties[$i] = $penalties[$i-1] + $next_penalty;
            $penalties[$i] = $next_penalty;
        }   
        //dd($penalties);
        $ledgers_due_date_today = InstallmentAccountLedger::getCurrentDueDates();
        $voucher_details = VoucherDetail::getLastTenVouchersDetails();
        $ledger_details = InstallmentAccountLedgerDetail::getLastTenLedgerDetails();
        $birthdays = User::getBirthdaysForTheMonth();

        /*$user = User::whereUsername('admin')->first();
        $user->password = Hash::make('12345');
        $user->touch();*/
        
        $developer = Developer::getCurrentDeveloper();
        //$developer->security_code = Hash::make('123maharlika123');
        //$developer->touch();
        return view('developers.dashboard', compact('developer','ledgers_due_date_today','voucher_details','ledger_details',
            'birthdays'));
    }

    /**
     * Display login page.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLogin()
    {
       /* $user = User::whereUsername('louphernandez')->first();
        $user->password  = Hash::make('bridgestone');
        $user->touch();*/
        return view('developers.login');
    }

    /**
    * Login the user.
    *
    */
    public function login(LoginRequest $request)
    {
        /*$remembered = $request->get('remember_me') == "on" ? true : false;
        if (Auth::attempt(array('email' => $request->get('email'), 'password' => $request->get('password')), $remembered) or 
            Auth::attempt(array('username' => $request->get('username'), 'password' => $request->get('password')), $remembered)) 
        {
            return redirect(route('dashboard'));
        } else 
        {
            return redirect(route('developer_login'))->withDanger('Invalid Credentials');
        }*/

        $remembered = $request->get('remember_me') == "on" ? true : false;
        $field = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$field => $request->input('login')]);

        if (Auth::attempt($request->only($field, 'password'))){
            return redirect(route('developer_dashboard'));
        } else{
            return redirect(route('developer_login'))->withDanger('Invalid Credentials');
        }
    }

    /**
    * Logout the user.
    *
    */
    public function logout() 
    {
        Auth::logout();
        return redirect(route('developer_login'));
    }

    /**
    * Feature a page for unavailable features.
    *
    */
    public function unavailableFeature()
    {
        return view('errors.unavailable_feature');
    }
}
