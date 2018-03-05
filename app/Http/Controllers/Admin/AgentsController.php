<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\CreateAgentAccountRequest;
use App\Http\Requests\EditAgentAccountRequest;
use App\Http\Controllers\Controller;

use Validator;

use App\User;
use App\Agent;

class AgentsController extends Controller
{
    /**
     * Show the form for creating a agent.
     *
     * @return \Illuminate\Http\Response
     */
    public function showNonAgents()
    {
        $users = User::getAllNonAgents();
        return view('admin.agents.non_agents', compact('users'));
    }

    /**
     * Show the form for creating a agent.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCreateAccount(User $user)
    {
        $agent = new Agent();
        return view('admin.agents.create_account', compact('user','agent'));
    }

    /**
     * Display form for creating an account
     *
     * @return \Illuminate\Http\Response
     */
    public function createAccount(User $user, Agent $agent, CreateAgentAccountRequest $request)
    {
        $return = $agent->createUser($user, $request);
        if($return["success"]) {
            return redirect(route('admin_all_accounts_agent'))->withSuccess('Agent account for <i>'.$request->get('username').'</i> was successfully created');
        } else {
            return redirect(route('admin_all_accounts_agent'))->withDanger('Agent account for <i>'.$request->get('username').'</i> was unsuccessfully created');
        }
    }

    /**
     * Display form for creating an account
     *
     * @return \Illuminate\Http\Response
     */
    public function showAllAccounts(User $user)
    {
        $users = User::getAllBrokers();
        return view('admin.agents.all_accounts', compact('users'));
    }

    /**
     * Display form for editing an account
     *
     * @return \Illuminate\Http\Response
     */
    public function showEditAccount(User $user)
    {
    	$agent = Agent::find($user->agent_id);
        if(!$agent)
            $agent = new Agent();
        return view('admin.agents.edit_account', compact('user','agent'));
    }

    /**
     * Display form for creating an account
     *
     * @return \Illuminate\Http\Response
     */
    public function editAccount(User $user, CreateEditAgentAccountRequest $request)
    {
    	$agent = Agent::whereId($user->agent_id)->first();
        if($request->get('password') == "" && $request->get('password_confirmation') == "") {
            $return = $agent->updateUser($user, $agent, $request);
            if($return["success"]) {
                return redirect(route('admin_edit_account_agent', $user->username))->withSuccess('Account for <i>' . $user->username. '</i> was successfully edited');
            } else {
                return redirect(route('admin_edit_account_agent', $user->username))->withDanger('Account for <i>' . $user->username. '</i> was successfully edited');
            }
        } else {
            $validator = Validator::make($request->all(), [
                'password' => 'required|confirmed|min:5',
                'password_confirmation' => 'required'
            ]);

            if (!$validator->fails()) {
                $return = $agent->updateUser($user, $agent, $request);
                if($return["success"]) {
                    return redirect(route('admin_edit_account_agent', $user->username))->withSuccess('Account for <i>' . $user->username. '</i> was successfully edited');
                } else {
                    return redirect(route('admin_edit_account_agent', $user->username))->withDanger('Account for <i>' . $user->username. '</i> was successfully edited');
                }       
            } else {
                return redirect(route('admin_edit_account_agent', $user->username))
                            ->withErrors($validator)
                            ->withInput();
            }
        }
    }

    /**
    * Permanently delete an admin account.
    *
    */
    public function deleteAccount(User $user) {
    	$agent = Agent::whereId($user->agent_id)->first();
    	$agent->delete();
        $user->delete();
        return redirect(route('admin_all_accounts_agent', $user->username))->withSuccess('Account for <i>' . $user->username. '</i> was successfully deleted');
    }

}
