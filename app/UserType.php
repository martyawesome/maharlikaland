<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class UserType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_types';

    /**
    *
    *
    */
    public static function getCreateUsers()
    {
    	return UserType::whereRaw('id != '.config('constants.USER_TYPE_ADMIN'))
        ->lists('user_type','id');
    }
}
