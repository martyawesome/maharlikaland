<?php

namespace App\Http\Controllers\Developers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\EditDeveloperAccountRequest;
use App\Http\Requests\AddEditBrokerRequest;
use App\Http\Controllers\Controller;

use App\Developer;
use App\User;
use App\UserType;
use App\Agent;

use Excel;
use Input;
use Validator;
use Auth;
use Hash;

class UsersController extends Controller
{

    /**
    * View the current user's profile.
    *
    */
    public function showMyAccount(User $user)
    {
        $developer = Developer::getCurrentDeveloper();
        $user_type = UserType::find($user->user_type_id);
        $agent = Agent::find($user->agent_id);
        return view('developers.users.my_account', compact('user','developer','user_type','agent'));
    }

    /**
    * View the current user's profile.
    *
    */
    public function showMyAdminAccount(User $user)
    {
        $developer = Developer::getCurrentDeveloper();
        $user_type = UserType::find($user->user_type_id);
        $agent = Agent::find($user->agent_id);
        return view('developers.users.my_account', compact('user','developer','user_type','agent'));
    }

    /**
    * Edit My Account
    *
    */
    public function editMyAccount(User $user, EditUserRequest $request)
    {
        $return = User::updateUser($user, $request);
        
        if($return["success"]) {
            return redirect(route('user', array($user->id)))->withSuccess('User was successfully edited');
        } else {
            return redirect(route('user', array($user->id)))->withDanger('User was unsuccessfully edited');
        }
    }

    /**
    * Edit my admin account.
    *
    */
    public function editMyAdminAccount(User $user, EditDeveloperAccountRequest $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if($request->get('password') == "" && $request->get('password_confirmation') == "") {
            $return = $developer->editDeveloper($user, $developer, $request);

            if($return["success"]) {
                if($user->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN')){
                    return redirect(route('my_admin_account', $user->id))->withSuccess('Developer account was successfully edited');
                } else {
                    return redirect(route('my_account', $user->id))->withSuccess('Developer was successfully edited');
                }
            } else {
                if($user->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN')){
                    return redirect(route('my_admin_account', $user->email))->withDanger('Developer account for was unsuccessfully edited');
                } else {
                    return redirect(route('my_account', $user->id))->withDanger('Developer account for was unsuccessfully edited');
                }
            }
        } else {
            $validator = Validator::make($request->all(), [
                'password' => 'required|confirmed|min:5',
                'password_confirmation' => 'required'
            ]);

            if (!$validator->fails()) {
                $return = $developer->editDeveloper($user, $developer, $request);
                
                if($return["success"]) {
                    if($user->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN')){
                        return redirect(route('my_admin_account', $user->id))->withSuccess('Developer account for was successfully edited');
                    } else {
                        return redirect(route('my_account', $user->id))->withSuccess('Developer account for was successfully edited');
                    }
                } else {
                    if($user->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN')){
                        return redirect(route('my_admin_account', $user->id))->withDanger('Developer account for was unsuccessfully edited');
                    } else {
                        return redirect(route('my_account', $user->id))->withDanger('Developer account for was unsuccessfully edited');
                    }
                }    
            } else {
                if($user->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN')){
                    return redirect(route('my_admin_account', $user->id))->withDanger('Developer account for was unsuccessfully edited');
                } else {
                    return redirect(route('my_account', $user->id))->withDanger('Developer account for was unsuccessfully edited');
                }
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAddUser()
    {
        $user = new User();
        $user_types = UserType::getCreateUsers();
        return view('developers.users.add', compact('user','user_types'));
    }

    /**
    * Add the new buyer
    *
    */
    public function addUser(CreateUserRequest $request)
    {
        $new_user = new User();
        $return = User::createUser($new_user, $request);
        
        if($return["success"]) {
            return redirect(route('users'))->withSuccess('User was successfully created');
        } else {
            return redirect(route('dashboard'))->withDanger('User was unsuccessfully created');
        }
    }

    /**
    * Show all the buyers of the current developer.
    *
    */
    public function showUsers()
    {
        $users = User::getUsersOfDeveloper(Auth::user()->id, Auth::user()->developer_id);
        return view('developers.users.all', compact('users'));
    }

    /**
    * Show all the buyers of the current developer.
    *
    */
    public function showUser(User $user)
    {
        $user_type = UserType::find($user->user_type_id);
        $agent = Agent::find($user->agent_id);
        return view('developers.users.view', compact('user','user_type','agent'));
    }

    /**
     * Show the form for editing a buyer. 
     *
     */
    public function showEditUser(User $user)
    {
        $developer = Developer::getCurrentDeveloper();
        $user_types = UserType::getCreateUsers();
        return view('developers.users.edit', compact('user', 'developer','user_types'));
    }

    /**
    * Add the new buyer
    *
    */
    public function editUser(User $user, EditUserRequest $request)
    {
        
        $return = User::updateUser($user, $request);
        
        if($return["success"]) {
            return redirect(route('user', array($user->id)))->withSuccess('User was successfully edited');
        } else {
            return redirect(route('user', array($user->id)))->withDanger('User was unsuccessfully edited');
        }
    }

    /**
     * Show the form for making a user a broker. 
     *
     */
    public function showNewBroker(User $user)
    {
        $agent = new Agent();
        $developer = Developer::getCurrentDeveloper();
        return view('developers.users.broker', compact('user', 'agent','developer'));
    }

    /**
     * Show the form for making a user a broker. 
     *
     */
    public function newBroker(User $user, AddEditBrokerRequest $request)
    {
        $return = Agent::addEditAgent($user, new Agent(), $request);
        if($return["success"]) {
            return redirect(route('user', array($user->id)))->withSuccess('User was successfully edited');
        } else {
            return redirect(route('user', array($user->id)))->withDanger('User was unsuccessfully edited');
        }
    }

    /**
     * Show the form for making a user a broker. 
     *
     */
    public function showEditBroker(User $user, Agent $agent)
    {
        $developer = Developer::getCurrentDeveloper();
        return view('developers.users.broker', compact('user', 'agent','developer'));
    }

    /**
     * Show the form for making a user a broker. 
     *
     */
    public function editBroker(User $user, Agent $agent, AddEditBrokerRequest $request)
    {
        $return = Agent::addEditAgent($user, $agent, $request);
        if($return["success"]) {
            return redirect(route('user', array($user->id)))->withSuccess('User was successfully edited');
        } else {
            return redirect(route('user', array($user->id)))->withDanger('User was unsuccessfully edited');
        }
    }

    /**
    * Import the users from an Excel file and save the data to the database.
    *
    */
    public static function importFromExcel()
    {
        if(Input::hasFile('excel')){
            $path = Input::file('excel')->getRealPath();
            $data = Excel::selectSheetsByIndex(0)->load($path, function($reader) {
                $reader->formatDates(false);
            })->get();
            $return = User::importFromExcel($data);

            if($return["success"]){
                return redirect(route('users'))->withSuccess('Users were successfully imported');
            } else {
                return redirect(route('users'))->withDanger($return['message']);
            }
        } else {
            return redirect(route('users'))->withDanger('No file selected');
        }
    }

    /**
    * Delete a user.
    *
    */
    public static function deleteUser(User $user, Request $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'], $developer->security_code)) {
            $return = User::deleteUser($user);
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
