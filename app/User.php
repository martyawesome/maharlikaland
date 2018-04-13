<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\DeveloperAgent;
use App\Agent;

use Auth;
use DB;
use File;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
    * Check if the user is logged-in or is an admin.
    *
    */
    public static function hasYadaAccess() {
        if(Auth::user()->is_admin_activated and Auth::user()->user_type_id == config('constants.USER_TYPE_ADMIN')) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Check if the user is logged-in or is an admin developer.
    *
    */
    public static function hasDeveloperAccess() {
        $user = Auth::user();
        if(Auth::user()->is_admin_activated and 
            (Auth::user()->user_type_id == config('constants.USER_TYPE_ADMIN')
                or config('constants.USER_TYPE_DEVELOPER_ADMIN')
                or config('constants.USER_TYPE_DEVELOPER_SECRETARY')
                or config('constants.USER_TYPE_DEVELOPER_ACCOUNTANT'))) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Check if the user is logged-in or is an admin agent.
    *
    */
    public static function hasAgentAccess() {
        $user = Auth::user();
        $agent = Agent::find($user->agent_id);
        if($user->is_admin_activated and ($user->user_type_id == config('constants.USER_TYPE_BROKER') or 
            $user->user_type_id  == config('constants.USER_TYPE_ADMIN')
            or $agent)) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Get all users that are admin.
    */ 
    public static function getAllAdmin() {
        return User::whereUserTypeId(config('constants.USER_TYPE_ADMIN'))
        ->get();
    }

    /**
    * Get all users that are brokers.
    */ 
    public static function getAllBrokers() {
        return User::whereRaw('users.agent_id != 0')->get();
    }

     /**
    * Get all users that are not agents.
    */ 
    public static function getAllNonAgents() {
        return User::select(DB::raw('users.*, user_types.user_type'))
        ->leftJoin('user_types','user_types.id','=','users.user_type_id')
        ->whereRaw('users.agent_id = 0')
        ->get();
    }

    /**
    * Get the users of a developer.
    *
    */
    public static function getUsersOfDeveloper($user_id)
    {
        return User::selectRaw(DB::raw('users.*, user_types.user_type'))
        ->leftJoin('user_types','user_types.id','=','users.user_type_id')
        ->whereRaw(DB::raw('users.id != '.$user_id))
        ->get();
    }

    /**
    * Get the users of a developer.
    *
    */
    public static function getAllForForm()
    {
        return User::selectRaw(DB::raw("CONCAT(last_name,', ',first_name) as full_name, users.id"))
        ->orderBy('full_name','asc')
        ->lists('full_name','id');
    }

    /**
    * Get the admin users of a developer.
    *
    */
    public static function getAdminUsersOfDeveloper()
    {
        return User::whereRaw(DB::raw('users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_ADMIN').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_SECRETARY').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_ACCOUNTANT').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_EMPLOYEE')))
        ->get();
    }

    /**
    * Get the Admin- Developer accounts of a developer.
    *
    */
    public static function getAdminsOfDeveloper()
    {
        return User::whereRaw(DB::raw('users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_ADMIN')))
        ->get();
    }

    /**
    * Get the list of admin users of a developer for dropdown elements.
    *
    */
    public static function getListAdminUsersOfDeveloper()
    {
        return User::selectRaw(DB::raw("CONCAT(last_name,', ',first_name) as full_name, id"))
        ->whereRaw(DB::raw('users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_ADMIN').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_SECRETARY').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_ACCOUNTANT').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_EMPLOYEE').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_CONSTRUCTION').
            ' or users.user_type_id = '.config('constants.USER_TYPE_DEVELOPER_GUARD')))
        ->orderBy('full_name','asc')
        ->lists('full_name','id');
    }

    /**
    * Create an admin user.
    *
    */
    public function createAdmin(CreateUserRequest $request)
    {
        $user = new User();        
        $user->user_type_id = config('constants.USER_TYPE_ADMIN');
        if($request->file('image')) {
            $user->profile_picture_path = 'img/users/admin/'. User::saveProfilePicture($request, config('constants.USER_TYPE_ADMIN'));
        } else {
            if($user->profile_picture_path == "") {
                $user->profile_picture_path = 'img/defaults/icon-user-default.png';
            }
        }

        return User::createUser($user, $request);
    }

    /**
    * Save new user to the database.
    *
    */
    public static function createUser(User $user, CreateUserRequest $request) {
        DB::beginTransaction();

        $user->password = Hash::make($request->get('password'));
        $user->email = $request->get('email');
        $user->username = $request->get('username');
        if($request->get('user_type'))
            $user->user_type_id = $request->get('user_type');
        $user->first_name = $request->get('first_name');
        $user->middle_name = $request->get('middle_name');
        $user->last_name = $request->get('last_name');
        $user->nickname = $request->get('nickname');
        $user->sex = $request->get('sex');
        $user->birthdate = $request->get('birthdate');
        $user->address = $request->get('address');
        $user->contact_number = $request->get('contact_number');
        $user->is_admin_activated = $request->get('is_admin_activated') == "on" ? true : false;
        $user->is_mobile_activated = $request->get('is_mobile_activated') == "on" ? true : false;
        $user->able_to_sell = false;
        if($request->file('image')) {
            $user->profile_picture_path = User::saveProfilePicture($request);
        } else {
            if($user->profile_picture_path == "") {
                $user->profile_picture_path = 'img/defaults/icon-user-default.png';
            }
        }

        $return["success"] = $user->touch();        
        
        if($return["success"]){
            if($request->get('user_type') == config('constants.USER_TYPE_BROKER')
                or $request->get('user_type') == config('constants.USER_TYPE_SALESPERSON')) {
                if($return["success"]) {
                    DB::commit();
                } else {
                    DB::rollback();
                }
            } else {
                $return["object"] = $user;
                DB::commit();
            }
        } else {
            DB::rollback();
        }

        return $return;
    }
    

    /**
    * Edit user.
    *
    */
    public static function updateUser(User $user, EditUserRequest $request) {
        DB::beginTransaction();

        $user->password = Hash::make($request->get('password'));
        $user->username = $request->get('username');
        $user->email = $request->get('email');
        if($request->get('user_type'))
            $user->user_type_id = $request->get('user_type');
        $user->first_name = $request->get('first_name');
        $user->middle_name = $request->get('middle_name');
        $user->last_name = $request->get('last_name');
        $user->nickname = $request->get('nickname');
        $user->sex = $request->get('sex');
        $user->birthdate = $request->get('birthdate');
        $user->address = $request->get('address');
        $user->contact_number = $request->get('contact_number');
        $user->is_admin_activated = $request->get('is_admin_activated') == "on" ? true : false;
        $user->is_mobile_activated = $request->get('is_mobile_activated') == "on" ? true : false;
        if($request->file('image')) {
            $user->profile_picture_path = User::updateProfilePicture($request, $user);
        } else {
            if($user->profile_picture_path == "") {
                $user->profile_picture_path = 'img/defaults/icon-user-default.png';
            }
        }
        
        $return["success"] = $user->touch();
        
        if($return["success"]){
            if($user->user_type_id == config('constants.USER_TYPE_BUYER')) {
                $buyer = Buyer::whereId($user->buyer_id)->first();
                
                $buyer->first_name = $user->first_name;
                $buyer->middle_name = $user->middle_name;
                $buyer->last_name = $user->last_name;

                $buyer->email = $user->email;
                $buyer->contact_number_mobile = $user->contact_number;
                $buyer->sex = $user->sex;
                $buyer->home_address = $user->address;

                $return["success"] = $buyer->touch();

                if($return["success"]) {
                    $return["object"] = $user;
                    DB::commit();
                } else {
                    DB::rollback();
                }
            } else {
                $return["object"] = $user;
                DB::commit();
            }
        } else {
            DB::rollback();
        }

        return $return;
    }

    /**
    * Save the profile picture of a user and save it to the local directory with
    * the username as the file name.
    *
    */
    public static function saveProfilePicture(EditUserRequest $request, $user_type) {
        if($request->file('image')) {

            switch($user_type){
                case config('constants.USER_TYPE_ADMIN'):
                    $imagePath = '/img/users/admin';
                    break;
                case config('constants.USER_TYPE_BROKER'):
                     $imagePath = '/img/users/brokers';
                    break;
                case config('constants.USER_TYPE_SALESPERSON'):
                     $imagePath = '/img/users/salespersons';
                    break;
                case config('constants.USER_TYPE_PROSPECT_BUYER'):
                     $imagePath = '/img/users/prospect_buyers';
                    break;
                case config('constants.USER_TYPE_BUYER'):
                     $imagePath = '/img/users/buyers';
                    break;
                case config('constants.USER_TYPE_DEVELOPER'):;
                case config('constants.USER_TYPE_DEVELOPER_ADMIN');
                    $imagePath = '/img/users/developers';
                    break;
            }

            $file_prefix = "";
            if($request->get('email') != "") {
                $file_prefix = str_replace('.','',str_replace('@','',$request->get('email')));
            } else {
                $file_prefix = $request->get('username');
            }

            $imageName = $file_prefix . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path().$imagePath, $imageName);
            return $imagePath. '/' . $imageName;
        } 
        return "";
    }

    /**
    * Update the profile picture of a user and save it to the local directory with
    * the username as the file name.
    *
    */
    public static function updateProfilePicture(EditUserRequest $request, $user) {
        if($request->file('image')) {

            switch($user->user_type_id){
                case config('constants.USER_TYPE_ADMIN'):
                    $imagePath = '/img/users/admin';
                    break;
                case config('constants.USER_TYPE_BROKER'):
                     $imagePath = '/img/users/brokers';
                    break;
                case config('constants.USER_TYPE_SALESPERSON'):
                     $imagePath = '/img/users/salespersons';
                    break;
                case config('constants.USER_TYPE_PROSPECT_BUYER'):
                     $imagePath = '/img/users/prospect_buyers';
                    break;
                case config('constants.USER_TYPE_BUYER'):
                     $imagePath = '/img/users/buyers';
                    break;
                case config('constants.USER_TYPE_DEVELOPER'):;
                case config('constants.USER_TYPE_DEVELOPER_ADMIN');
                    $imagePath = '/img/users/developers';
                    break;
            }

            $file_prefix = "";
            if($request->get('email') != "") {
                $file_prefix = str_replace('.','',str_replace('@','',$request->get('email')));
            } else if($request->get('username') != "") {
                $file_prefix = $request->get('username');
            } else {
                $file_prefix = $user->id;
            }

            File::delete($user->profile_picture_path);
            $imageName = $file_prefix . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path().$imagePath, $imageName);
            return $imagePath . '/' . $imageName;
        } 
        return "";
    }

    
    /**
    * Get all users who have birthdays today.
    *
    */
    public static function getBirthdaysForTheMonth()
    {
        $developer = Developer::getCurrentDeveloper();
        return User::selectRaw(DB::raw('users.last_name, users.first_name, users.birthdate,
         extract(day from birthdate) as birthday, user_types.user_type'))
        ->leftJoin('user_types','user_types.id','=','users.user_type_id')
            ->whereRaw('extract(month from birthdate) = ?', [(int)(date('m'))])
            ->orderBy('birthday','asc')
            ->get();
    }

    /**
    * Find user by whole name.
    *
    */
    public static function getByWholeName($first_name, $middle_name, $last_name)
    {
        if($middle_name != null) {
            return User::whereRaw("first_name like '%".$first_name."%' and middle_name like
             '%".$middle_name."%' and last_name like '%".$last_name."%'")->first();
        } else {
            return User::whereRaw("first_name like '%".$first_name."%' and
             last_name like '%".$last_name."%'")->first();
        }
    }

    /**
    * Save the users imported from an excel file.
    *
    */
    public static function importFromExcel($data)
    {
        DB::beginTransaction();

        $counter = true;
        $row_counter = 1;
        $developer = Developer::getCurrentDeveloper();
        
        foreach($data as $datum) {
            if($datum->last_name != null and $datum->first_name != null and $datum->user_type != null) {
                $user = User::getByWholeName($datum->first_name, $datum->middle_name, $datum->last_name);
                if(!$user and $datum->email)
                    $user = User::whereEmail($datum->email)->first();

                if(!$user){
                    $user = new User();

                    $user->first_name = ucwords(str_replace('Ñ', 'ñ', strtolower($datum->first_name)));
                    if($datum->middle_name)
                        $user->middle_name = ucwords(str_replace('Ñ', 'ñ', strtolower($datum->middle_name)));
                    $user->last_name = ucwords(str_replace('Ñ', 'ñ', strtolower($datum->last_name)));
                    $user->sex = $datum->sex;
                    $user->birthdate = $datum->birthdate;
                    $user->email = $datum->email;
                    $user->contact_number = $datum->contact_number;
                    $user->email = $datum->email;
                    $user->user_type_id = $datum->user_type;
                    $id = User::orderBy('id','desc')->first()->id + 1;
                    $user->username = strtolower(trim(str_replace(' ', '', str_replace('Ñ', 'n', str_replace('ñ', 'n', strtolower($datum->last_name)))))).$id;
                    $user->profile_picture_path = 'img/defaults/icon-user-default.png';
                    $user->password = Hash::make('12345');
                    $user->is_admin_activated = true;
                    $user->is_mobile_activated = true;

                    if($user->touch()){
                        $counter = true;
                    } else{
                        $counter = false;
                        break;
                    }
                }
                
            } else {
                // Skip the headers
                if($row_counter > 1){
                    break;
                    DB::rollback();
                    $return['success'] = false;
                    $return['message'] = "Data missing in row ".$row_counter;
                }
            }
            $row_counter++;
        }

        if($counter){
            DB::commit();
            $return['success'] = true;
        } else {
            DB::rollback();
            $return['success'] = false;
            if(!$return['message']) {
                $return['message'] = "Users were unsuccessfully imported";
            }
        }

        return $return;
    }

    /**
    * Delete a user.
    *
    */
    public static function deleteUser(User $user)
    {
        DB::beginTransaction();

        try {
            $profile_picture_deleted = false;
            if($user->profile_picture_path != 'img/defaults/icon-user-default.png') {
                $profile_picture_deleted = File::delete($user->profile_picture_path);   
            } else {
                $profile_picture_deleted = true;
            }

            if($profile_picture_deleted) {
                $return['success'] = $user->delete();

                if($return['success']) {
                    DB::commit();
                } else {
                    DB::rollback();
                    $return['success'] = false;
                }
            } else {
                DB::rollback();
                $return['success'] = false;
            }
            
        } catch(Exception $e) {
            DB::rollback();
            $return['success'] = false;
        }

        return $return;
    }

}
