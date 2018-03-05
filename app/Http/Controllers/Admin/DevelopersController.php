<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\CreateDeveloperAccountRequest;
use App\Http\Requests\EditDeveloperAccountRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Controllers\Controller;

use Validator;

use App\User;
use App\Developer;

class DevelopersController extends Controller
{
    /**
     * Show the form for creating a agent.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCreateAccount()
    {
        $user = new User();
        $developer = new Developer();
        return view('admin.developers.create_account', compact('user','developer'));
    }

    /**
     * Display form for creating an account
     *
     * @return \Illuminate\Http\Response
     */
    public function createAccount(Developer $developer, CreateDeveloperAccountRequest $request)
    {
        $return = $developer->createDeveloper($request);

        if($return["success"]) {
            return redirect(route('admin_all_developers'))->withSuccess('Developer account for <i>' . $request->get('username'). '</i> was successfully created');
        } else {
            return redirect(route('admin_all_developers'))->withDanger('Developer account for <i>' . $request->get('username'). '</i> was unsuccessfully created');
        }
    }

    /**
     * Display form for editing an account
     *
     * @return \Illuminate\Http\Response
     */
    public function showEditDeveloper(Developer $developer)
    {
        return view('admin.developers.edit_account', compact('developer'));
    }

    /**
     * Display form for creating an account
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        $developers = Developer::get();
        return view('admin.developers.all_accounts', compact('developers'));
    }

    /**
    * Show all admin accounts of the developer.
    *
    */
    public function showAdminAccounts(Developer $developer)
    {
        $users = User::getAdminsOfDeveloper($developer->id);
        return view('admin.developers.admins', compact('users','developer'));
    }

    /**
     * Display form for editing an account
     *
     * @return \Illuminate\Http\Response
     */
    public function showEditAccount(Developer $developer)
    {
        return view('admin.developers.edit_account', compact('developer'));
    }

    /**
     * Display form for creating an account
     *
     * @return \Illuminate\Http\Response
     */
    public function editAccount(User $user, EditDeveloperAccountRequest $request)
    {
        $developer = Developer::whereId($user->developer_id)->first();
        if($request->get('password') == "" && $request->get('password_confirmation') == "") {
            $return = $developer->editDeveloper($user, $developer, $request);
            if($return["success"]) {
                return redirect(route('admin_edit_account_developer', $user->username))->withSuccess('Developer account for <i>' . $user->username. '</i> was successfully edited');
            } else {
                return redirect(route('admin_edit_account_developer', $user->username))->withDanger('Developer account for <i>' . $user->username. '</i> was unsuccessfully edited');
            }
        } else {
            $validator = Validator::make($request->all(), [
                'password' => 'required|confirmed|min:5',
                'password_confirmation' => 'required'
            ]);

            if (!$validator->fails()) {
                $return = $developer->editDeveloper($user, $developer, $request);
                
                if($return["success"]) {
                return redirect(route('admin_edit_account_developer', $user->username))->withSuccess('Developer account for <i>' . $user->username. '</i> was successfully edited');
                } else {
                    return redirect(route('admin_edit_account_developer', $user->username))->withDanger('Developer account for <i>' . $user->username. '</i> was unsuccessfully edited');
                }    
            } else {
                return redirect(route('admin_edit_account_developer', $user->username))
                            ->withErrors($validator)
                            ->withInput();
            }
        }
    }

    /**
    * Permanently delete a developer account.
    *
    */
    public function deleteAccount(User $user) {
        $return = Developer::deleteDeveloper($user);

        if($return["success"]) {
            return redirect(route('admin_all_accounts_developer', $user->username))->withSuccess('Developer account for <i>'.$return["object"]->name.'</i> was successfully deleted');
        } else {
            return redirect(route('admin_all_accounts_developer', $user->username))->withDanger('Developer account for <i>'.$return["object"]->name.'</i> was unsuccessfully deleted');
        }
    }

    /**
     * Display form for editing an account
     *
     * @return \Illuminate\Http\Response
     */
    public function showEditAdminAccount(Developer $developer, User $user)
    {
        return view('admin.developers.edit_admin_account', compact('developer','user'));
    }

    /**
     * Display form for creating an account
     *
     * @return \Illuminate\Http\Response
     */
    public function editAdminAccount(Developer $developer, User $user, EditUserRequest $request)
    {
        if($request->get('password') == "" && $request->get('password_confirmation') == "") {
            $return = $user->updateUser($user, $request);
            if($return["success"]) {
                return redirect(route('admin_developer_accounts', $developer->id))->withSuccess('Account for <i>' . $return["object"]->username. '</i> was successfully edited');
            } else {
                return redirect(route('admin_edit_developer_account', array($developer->id, $user->id)))->withSuccess('Account for <i>' . $return["object"]->username. '</i> was unsuccessfully edited');
            }
        } else {
            $validator = Validator::make($request->all(), [
                'password' => 'required|confirmed|min:5',
                'password_confirmation' => 'required'
            ]);

            if (!$validator->fails()) {
                $return = $user->updateUser($user, $request);
                if($return["success"]) {
                    return redirect(route('admin_developer_accounts', $developer->id))->withSuccess('Account for <i>' . $return["object"]->username. '</i> was successfully edited');
                } else {
                    return redirect(route('admin_edit_developer_account', array($developer->id, $user->id)))->withSuccess('Account for <i>' . $return["object"]->username. '</i> was unsuccessfully edited');
                }
            } else {
                return redirect(route('admin_edit_account_admin', array($developer->id, $user->id)))
                            ->withErrors($validator)
                            ->withInput();
            }
        }
    }
}
