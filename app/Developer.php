<?php

namespace App;

use App\Http\Requests\CreateDeveloperAccountRequest;
use App\Http\Requests\EditDeveloperAccountRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;

class Developer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'developers';

    /**
    * Get the current developer.
    *
    */
    public static function getCurrentDeveloper() {
        return Developer::whereId(Auth::user()->developer_id)->first();
    }

    /**
    * Create developer and main user account.
    *
    */
    public static function createDeveloper(CreateDeveloperAccountRequest $request) {
        DB::beginTransaction();

    	$developer = new Developer();
    	$developer->name = $request->get('developer_name');
    	$developer->slug = Str::slug($request->get('developer_name'));
        $developer->security_code = Hash::make($request->get('security_code'));
    	$developer->overview = $request->get('overview');
    	$developer->mission = $request->get('mission');
    	$developer->vision = $request->get('vision');
    	$developer->address = $request->get('developer_address');
    	$developer->coordinates = $request->get('coordinates');
    	$developer->email = $request->get('developer_email');
    	$developer->contact_number = $request->get('developer_contact_number');
    	$developer->facebook_url = $request->get('facebook_url');
    	$developer->twitter_url = $request->get('twitter_url');
    	$developer->linkedin_url = $request->get('linkedin_url');
        $developer->is_activated = $request->get('is_developer_activated') == "on" ? true : false;
    	if($request->file('logo')) {
            $developerImagePath = public_path() . '/img/developers';
            $developerImageName = $request->get('developer_name') . '.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move($developerImagePath, $developerImageName);

            $developer->logo_path = 'img/developers/'. $developerImageName;
        } else {
            if($developer->logo_path== "") {
                $developer->logo_path= 'img/defaults/icon-developer-default.png';
            }
        }
    	$return["success"] = $developer->touch();

        if($return["success"]) {
            $user = new User();
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
            $user->username = $request->get('username');
            $user->developer_id = $developer->id;

            $user->user_type_id = config('constants.USER_TYPE_DEVELOPER_ADMIN');
            $user->is_admin_activated = $request->get('is_admin_activated') == "on" ? true : false;
            $user->is_mobile_activated = $request->get('is_mobile_activated') == "on" ? true : false;
            if($request->file('image')) {
                $imagePath = public_path() . '/img/users/developers';
                $imageName = $request->get('username') . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move($imagePath, $imageName);
            
                $user->profile_picture_path = 'img/users/developers/'. $imageName;
            } else {
                if($user->profile_picture_path == "") {
                    $user->profile_picture_path = 'img/defaults/icon-user-default.png';
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

        return $return;
    }

    /**
    * Edit user.
    *
    */
    public static function editDeveloper(User $user, Developer $developer, EditDeveloperAccountRequest $request) {
        DB::beginTransaction();

    	$developer->name = $request->get('developer_name');
    	$developer->slug = Str::slug($request->get('developer_name'));
        if($request->get('security_code') != "")
            $developer->security_code = Hash::make($request->get('security_code'));
        $developer->overview = $request->get('overview');
    	$developer->mission = $request->get('mission');
    	$developer->vision = $request->get('vision');
    	$developer->address = $request->get('developer_address');
    	$developer->coordinates = $request->get('coordinates');
    	$developer->email = $request->get('developer_email');
    	$developer->contact_number = $request->get('developer_contact_number');
    	$developer->facebook_url = $request->get('facebook_url');
    	$developer->twitter_url = $request->get('twitter_url');
    	$developer->linkdin_url = $request->get('linkdin_url');
    	if($request->file('logo')) {
            $imagePath = public_path() . '/img/developers/'.$request->get('developer_name');
            $imageName = 'logo.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move($imagePath, $imageName);

            $developer->logo_path = '/img/developers/'.$request->get('developer_name').'/'. $imageName;
        } else {
            if($developer->logo_path== "") {
                $developer->logo_path= '/img/defaults/icon-developer-default.png';
            }
        }

        if($request->file('header_image')) {
            $imagePath = public_path() . '/img/developers/'.$request->get('developer_name');
            $imageName = 'header_image.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move($imagePath, $imageName);

            $developer->header_image_path = '/img/developers/'.$request->get('developer_name').'/'. $imageName;
        } else {
            if($developer->header_image_path== "") {
                $developer->header_image_path= '/img/defaults/icon-developer-default.png';
            }
        }

        if($request->file('banner')) {
            $imagePath = public_path() . '/img/developers/'.$request->get('developer_name');
            $imageName = 'banner.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move($imagePath, $imageName);

            $developer->banner_path = '/img/developers/'.$request->get('developer_name').'/'. $imageName;
        } else {
            if($developer->banner_path== "") {
                $developer->banner_path= '/img/defaults/icon-developer-default.png';
            }
        }

        $developer->is_activated = $request->get('is_developer_activated') == "on" ? true : false;
    	$return["success"] = $developer->touch();

        if($return["success"]) {
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
            $user->username = $request->get('username');
            $user->is_admin_activated = $request->get('is_admin_activated') == "on" ? true : false;
            $user->is_mobile_activated = $request->get('is_mobile_activated') == "on" ? true : false;
            if($request->file('image')) {
                $imagePath = public_path() . '/img/users/developers';
                $imageName = $request->get('username') . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move($imagePath, $imageName);

                $user->profile_picture_path = 'img/users/developers/'. $imageName;
            } else {
                if($user->profile_picture_path == "") {
                    $user->profile_picture_path = 'img/defaults/icon-user-default.png';
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

        return $return;
    }

    /**
    * Delete the developer profile and all its projects and properties.
    *
    */
    public static function deleteDeveloper(User $user)
    {
        DB::beginTransaction();
        $developer = Developer::whereId($user->developer_id)->first();
        $return["object"] = $developer;
        if($developer->delete()){
            if($user->delete()) {
                $return["success"] = true;
                DB::commit();
            } else {
                $return["success"] = false;
                DB::rollback();
            }
        } else {
            $return["success"] = false;
            DB::rollback();
        }

        return $return;
    }

}
