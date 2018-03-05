<?php

namespace App;

use App\Http\Requests\CreateAgentAccountRequest;
use App\Http\Requests\EditAgentAccountRequest;
use App\Http\Requests\AddEditBrokerRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Developers;

use DB;

class Agent extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agents';

    /**
    * Save new user to the database.
    *
    */
    public static function createUser(User $user, CreateAgentAccountRequest $request) {
        DB::beginTransaction();

    	$agent = new Agent();
    	$agent->prc_license_number = $request->get('prc_license_number');
        $agent->facebook_url = $request->get('facebook_url');
        $agent->twitter_url = $request->get('twitter_url');
        $agent->linkdin_url = $request->get('linkdin_url');
        $return["success"] = $agent->touch();

        if(!$user->id){
            if($return["success"]) {
                $user->username = $request->get('username');
                $user->password = Hash::make($request->get('password'));
                $user->first_name = $request->get('first_name');
                $user->middle_name = $request->get('middle_name');
                $user->last_name = $request->get('last_name');
                $user->nickname = $request->get('nickname');
                $user->sex = $request->get('sex');
                $user->birthdate = $request->get('birthdate');
                $user->address = $request->get('address');
                $user->contact_number = $request->get('contact_number');
                $user->email = $request->get('email');
                $user->agent_id = $agent->id;
                $user->is_admin_activated = $request->get('is_admin_activated') == "on" ? true : false;
                $user->is_mobile_activated = $request->get('is_mobile_activated') == "on" ? true : false;
                $user->user_type_id = config('constants.USER_TYPE_BROKER');
                
                if($request->file('image')) {
                    $imagePath = public_path() . '/img/users/brokers';
                    $imageName = $request->get('username') . '.' . $request->file('image')->getClientOriginalExtension();
                    $request->file('image')->move($imagePath, $imageName);

                    $user->profile_picture_path = 'img/users/brokers/'. $imageName;
                } else {
                    if($user->profile_picture_path == "") {
                        $user->profile_picture_path = 'img/users/icon-user-default.png';
                    }
                }
                $return["success"] = $user->touch();

                if($return["success"]) {
                    DB::commit();
                } else {
                    DB::rollback();
                }
            } else {
                DB::rollback();
            }
        } else {
            $user->agent_id = $agent->id;
            $return["success"] = $user->touch();
            
            if($return["success"]) {
                DB::commit();
            } else {
                DB::rollback();
            }
        }

        return $return;
    }

    /**
    * Edit user.
    *
    */
    public static function updateUser(User $user, Agent $agent, EditAgentAccountRequest $request) {
        DB::beginTransaction();

        $agent->prc_license_number = $request->get('prc_license_number');
        $agent->facebook_url = $request->get('facebook_url');
        $agent->twitter_url = $request->get('twitter_url');
        $agent->twilinkdin_urltter_url = $request->get('linkdin_url');
        $return["success"] = $agent->touch();

        if($return["success"]) {
            $user->username = $request->get('username');
            $user->password = Hash::make($request->get('password'));
            $user->first_name = $request->get('first_name');
            $user->middle_name = $request->get('middle_name');
            $user->last_name = $request->get('last_name');
            $user->nickname = $request->get('nickname');
            $user->sex = $request->get('sex');
            $user->birthdate = $request->get('birthdate');
            $user->address = $request->get('address');
            $user->contact_number = $request->get('contact_number');
            $user->email = $request->get('email');
            $user->is_admin_activated = $request->get('is_admin_activated') == "on" ? true : false;
            $user->is_mobile_activated = $request->get('is_mobile_activated') == "on" ? true : false;
            if($request->file('image')) {
                $imagePath = public_path() . '/img/users/brokers';
                $imageName = $request->get('username') . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move($imagePath, $imageName);
             
                $user->profile_picture_path = 'img/users/brokers/'. $imageName;
            } else {
                if($user->profile_picture_path == "") {
                    $user->profile_picture_path = 'img/users/icon-user-default.png';
                }
            }
            $return["success"]  = $user->createUser($user, $user_request);

            if($return["success"]) {
                DB::commit();
            } else {
                DB::rollback();
            }
        } else {
            DB::rollback();
        }

        return $return;
    }
    
    /**
    * Create or edit an agent.
    *
    */
    public static function addEditAgent(User $user, Agent $agent, AddEditBrokerRequest $request)
    {
        DB::beginTransaction();

        try {
            $agent->prc_license_number = $request->get('prc_license_number');
            $agent->facebook_url = $request->get('facebook_url');
            $agent->twitter_url = $request->get('twitter_url');
            $agent->linkdin_url = $request->get('linkdin_url');
            $return["success"] = $agent->touch();

            if($return["success"]) {
                $user->agent_id = $agent->id;
                $return["success"] = $user->touch();

                if($return["success"]) {
                    DB::commit();
                } else {
                    DB::rollback();
                }
            } else {
                DB::rollback();
            }
        } catch(Exception $e) {
            DB::rollback();
        }
        
        return $return;
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
                    if($developer){
                        $user->developer_id = $developer->id;
                    }
                    $user->username = ucwords(trim(str_replace(' ', '', str_replace('Ñ', 'n', str_replace('ñ', 'n', strtolower($datum->last_name)))))).str_replace('-','', $datum->birthdate);
                    $user->profile_picture_path = 'img/defaults/icon-user-default.png';
                    $user->password = Hash::make('12345');

                    if($user->touch()){
                        $counter = true;
                    } else{
                        $counter = false;
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

}
