<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;

use Hash;
use Auth;

class BaseController extends Controller
{

    public function __construct() {}

    /**
     * Display dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLogin()
    {
        /*$user = User::whereUsername('jmphernandez')->first();
        $user->password = Hash::make('Julio54067');
        $user->touch();*/
    	return view('admin.login');
    }

    public function login(Request $request)
    {
    	$remembered = $request->get('remember_me') == "on" ? true : false;
        $field = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$field => $request->input('login')]);

        if (Auth::attempt($request->only($field, 'password'), $remembered)){
            return redirect(route('admin_dashboard'));
        } else{
            return redirect(route('admin_login'))->withDanger('Invalid Credentials');
        }
    }

    public function logout() 
    {
    	Auth::logout();
    	return redirect(route('admin_login'));
    }

}
