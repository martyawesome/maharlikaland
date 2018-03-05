<?php

namespace App\Http\Controllers\Agents;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
        return view('agents.dashboard');
    }

    /**
     * Display login page.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLogin()
    {
        return view('agents.login');
    }

    public function login(Request $request)
    {
        /*$user = User::find(19);
        $user->password  = Hash::make('12345');
        $user->touch();*/
        $field = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$field => $request->input('login')]);

        if (Auth::attempt($request->only($field, 'password'))){
            return redirect(route('agent_dashboard'));
        } else{
            return redirect(route('agent_login'))->withDanger('Invalid Credentials');
        }
    }

    public function logout() 
    {
        Auth::logout();
        return redirect(route('agent_login'));
    }
}
