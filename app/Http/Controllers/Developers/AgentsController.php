<?php

namespace App\Http\Controllers\Developers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\DeveloperAgent;
use App\Developer;

use Hash;
use DB;

class AgentsController extends Controller
{
    /**
     * Search the name of the agent and send it back to display in the search form.
     *
     */
    public function showAddAgent()
    {
        return view('developers.agents.add');
    }

    /**
    * Search the first and last name of the agent display it in the form via AJAX call.
    * 
    */
    public function searchAgent(Request $request)
    {
        if($request['first_name'] != '' and $request['last_name'] == '') {
            if($request['email'] != '') {
                return User::selectRaw(DB::raw('users.first_name, users.last_name, users.id as user_id, users.address, users.contact_number, users.email, user_types.user_type'))
                ->leftJoin('user_types','user_types.id','=','users.user_type_id')
                ->whereRaw(DB::raw('(users.first_name like \'%'.$request['first_name']).'%\' 
                    and ((users.user_type_id =' . config('constants.USER_TYPE_BROKER').' 
                        or users.user_type_id = '.config('constants.USER_TYPE_SALESPERSON').') or users.agent_id != 0)
                         or email like "%'.$request['email'].'%"')
                ->get();
            } else {
                return User::selectRaw(DB::raw('users.first_name, users.last_name, users.id as user_id, users.address, users.contact_number, users.email, user_types.user_type'))
                ->leftJoin('user_types','user_types.id','=','users.user_type_id')
                ->whereRaw(DB::raw('users.first_name like \'%'.$request['first_name']).'%\' 
                    and ((users.user_type_id =' . config('constants.USER_TYPE_BROKER').' 
                        or users.user_type_id = '.config('constants.USER_TYPE_SALESPERSON').') or users.agent_id != 0)')
                ->get();
            }
        } else if ($request['first_name'] == '' and $request['last_name'] != '') {
            if($request['email'] != '') { 
                return User::selectRaw(DB::raw('users.first_name, users.last_name, users.id as user_id, users.address, users.contact_number, users.email, user_types.user_type'))
                ->leftJoin('user_types','user_types.id','=','users.user_type_id')
                ->whereRaw(DB::raw('(users.last_name like \'%'.$request['last_name']).'%\' 
                    and ((users.user_type_id =' . config('constants.USER_TYPE_BROKER').' 
                        or users.user_type_id = '.config('constants.USER_TYPE_SALESPERSON').') or users.agent_id != 0) 
                            or email like "%'.$request['email'].'%"')
                ->get();
            } else {
                return User::selectRaw(DB::raw('users.first_name, users.last_name, users.id as user_id, users.address, users.contact_number, users.email, user_types.user_type'))
                ->leftJoin('user_types','user_types.id','=','users.user_type_id')
                ->whereRaw(DB::raw('users.last_name like \'%'.$request['last_name']).'%\' 
                    and ((users.user_type_id =' . config('constants.USER_TYPE_BROKER').' 
                        or users.user_type_id = '.config('constants.USER_TYPE_SALESPERSON').') or users.agent_id != 0)')
                ->get();
            }
        } else if ($request['first_name'] != '' and $request['last_name'] != ''){
            if($request['email'] != '') { 
                return User::selectRaw(DB::raw('users.first_name, users.last_name, users.id as user_id, users.address, users.contact_number, users.email, user_types.user_type'))
                ->leftJoin('user_types','user_types.id','=','users.user_type_id')
                ->whereRaw(DB::raw('(users.first_name like \'%'.$request['first_name']).'%\' 
                or users.last_name like \'%'.$request['last_name'].'%\' 
                    and ((users.user_type_id =' . config('constants.USER_TYPE_BROKER').' 
                        or users.user_type_id = '.config('constants.USER_TYPE_SALESPERSON').')) or users.agent_id != 0) 
                        or email like "%'.$request['email'].'%"')
                ->get();
            } else {
                return User::selectRaw(DB::raw('users.first_name, users.last_name, users.id as user_id, users.address, users.contact_number, users.email, user_types.user_type'))
                ->leftJoin('user_types','user_types.id','=','users.user_type_id')
                ->whereRaw(DB::raw('users.first_name like \'%'.$request['first_name']).'%\' 
                or users.last_name like \'%'.$request['last_name'].'%\' 
                    and ((users.user_type_id =' . config('constants.USER_TYPE_BROKER').' 
                        or users.user_type_id = '.config('constants.USER_TYPE_SALESPERSON').') or users.agent_id != 0)')
                ->get();
            }
            
        } else  if ($request['email'] != ''){
            return User::selectRaw(DB::raw('users.first_name, users.last_name, users.id as user_id, users.address, users.contact_number, users.email, user_types.user_type'))
                ->leftJoin('user_types','user_types.id','=','users.user_type_id')
                ->whereRaw(DB::raw('users.email like \'%'.$request['email']).'%\' 
                    and ((users.user_type_id =' . config('constants.USER_TYPE_BROKER').' 
                        or users.user_type_id = '.config('constants.USER_TYPE_SALESPERSON').') or users.agent_id != 0)')
                ->get();
        } else {
            return null;
        }
    }

    /**
    * Delete and agent.
    *
    */
    public function addAgent(Request $request)
    {   
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = DeveloperAgent::addAgentToDeveloper($developer->id, $request['user_id']);
            if($return["success"] == 1) {
                return 1;
            } else if($return["success"] == 3) {
                return 3;
            } else {
                return 2;
            }
        } else {
            return 0;
        }
    }

    /**
    * Show the agents of the developers
    *
    */
    public function showDevelopersAgent()
    {
        $agents = DeveloperAgent::getAllDevelopersAgents();
        return view('developers.agents.all',compact('agents'));
    }


    /**
    * Remove an agent from the developer's list.
    *
    */
    public function removeDeveloperAgent(DeveloperAgent $developer_agent, Request $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = DeveloperAgent::removeDeveloperAgent($developer_agent);
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
