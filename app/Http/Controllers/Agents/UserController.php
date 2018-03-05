<?php

namespace App\Http\Controllers\Agents;

use Illuminate\Http\Request;
use App\Http\Requests;
use Response;
use App\Http\Requests\AddEditAboutMeRequest;
use App\Http\Requests\EditAgentAccountRequest;
use App\Http\Controllers\Controller;
use App\User;
use App\Agent;
use App\AboutMe;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAboutMe()
    {
        $user = Auth::user();
        $agent = Agent::whereId($user->agent_id)->first();
        $about_me = AboutMe::whereAgentId($agent->id)->first();
        if(!$about_me) {
            $about_me = new AboutMe();
        }
        return view('agents.about_me',compact('user','agent','about_me'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editAboutMe(AboutMe $about_me, AddEditAboutMeRequest $request)
    {
        $return = AboutMe::updateAboutMe($about_me, $request);

        if($return["success"]) {
            return redirect(route('agent_dashboard'))->withSuccess('About Me was successfully updated'); 
        } else {
            return redirect(route('about_me'))->withDanger('About Me was unsuccessfully updated');
        }
    }

    /**
     * Display MyAccount.
     *
     * @return \Illuminate\Http\Response
     */
    public function showMyAccount(User $user)
    {
        $currentUser = Auth::user();
        if($user->email == $currentUser->email) {
            $agent = Agent::whereId($user->agent_id)->first();
            return view('agents.my_account',compact('user','agent'));
        } else {
           return Response::view('errors.404');
        }
    }

    /**
     * Edit MyAccount
     *
     * @return \Illuminate\Http\Response
     */
    public function editMyAccount(User $user, EditAgentAccountRequest $request)
    {
        $agent = Agent::whereId($user->agent_id)->first();
        $user = $agent->editUser($user, $agent, $request);
        return redirect(route('my_account', $user->email))->withSuccess('Account for <i>' . $user->email. '</i> was successfully edited');            
    }

    
}
