<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;

use Validator;

use App\User;

class AdminController extends Controller
{

	public function __construct() {
	}

    /**
     * Display dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	return view('admin.dashboard');
    }

    /**
     * Display form for creating an account
     *
     * @return \Illuminate\Http\Response
     */
    public function showCreateAccount()
    {
        $user = new User();
        return view('admin.admin.create_account', compact('user'));
    }

    /**
     * Display form for creating an account
     *
     * @return \Illuminate\Http\Response
     */
    public function createAccount(User $user, CreateUserRequest $request)
    {
        $return = $user->createAdmin($request);
        if($return["success"]){
            return redirect(route('admin_all_accounts_admin'))->withSuccess('Account for <i>' . $request->get('username'). '</i> was successfully created');
        } else {
            return redirect(route('admin_all_accounts_admin'))->withDanger('Account for <i>' . $request->get('username'). '</i> was successfully created');
        }
    }


    /**
     * Display form for creating an account
     *
     * @return \Illuminate\Http\Response
     */
    public function showAllAccounts(User $user)
    {
        $users = $user->getAllAdmin();
        return view('admin.admin.all_accounts', compact('users'));
    }

    /**
     * Display form for editing an account
     *
     * @return \Illuminate\Http\Response
     */
    public function showEditAccount(User $user)
    {
        return view('admin.admin.edit_account', compact('user'));
    }

    /**
     * Display form for creating an account
     *
     * @return \Illuminate\Http\Response
     */
    public function editAccount(User $user, EditUserRequest $request)
    {
        if($request->get('password') == "" && $request->get('password_confirmation') == "") {
            $return = $user->updateUser($user, $request);
            if($return["success"]) {
                return redirect(route('admin_all_accounts_admin'))->withSuccess('Account for <i>' . $return["object"]->username. '</i> was successfully edited');
            } else {
                return redirect(route('admin_edit_account_admin', $return["object"]->id))->withSuccess('Account for <i>' . $return["object"]->username. '</i> was unsuccessfully edited');
            }
        } else {
            $validator = Validator::make($request->all(), [
                'password' => 'required|confirmed|min:5',
                'password_confirmation' => 'required'
            ]);

            if (!$validator->fails()) {
                $return = $user->updateUser($user, $request);
                if($return["success"]) {
                    return redirect(route('admin_all_accounts_admin'))->withSuccess('Account for <i>' . $return["object"]->username. '</i> was successfully edited');
                } else {
                    return redirect(route('admin_edit_account_admin', $return["object"]->id))->withSuccess('Account for <i>' . $return["object"]->username. '</i> was unsuccessfully edited');
                }
            } else {
                return redirect(route('admin_edit_account_admin', $user->id))
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
        $user->delete();
        return redirect(route('admin_all_accounts_admin', $user->username))->withSuccess('Account for <i>' . $user->username. '</i> was successfully deleted');
    }


}
