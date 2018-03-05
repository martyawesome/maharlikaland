<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\AddEditAccountTitleRequest;

use Illuminate\Support\Str;

use App\Voucher;
use App\VoucherDetail;
use Auth;
use DB;

class AccountTitle extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'account_titles';

    /**
    * Get all of the developer's account titles.
    *
    */
    public static function getAll()
    {
    	return AccountTitle::whereDeveloperId(Auth::user()->developer_id)->get();
    }

    /**
    * Get all account titles for form.
    *
    */
    public static function getAllForForm()
    {
        return AccountTitle::whereDeveloperId(Auth::user()->developer_id)->lists('title','id');
    }

    /**
    * Update an account title.
    *
	*/
    public static function updateAccountTitle(AccountTitle $account_title, AddEditAccountTitleRequest $request)
    {
    	DB::beginTransaction();

    	$account_title->developer_id = Auth::user()->developer_id;
    	$account_title->title = $request->get('account_title');
    	$account_title->slug = Str::slug($request->get('account_title'));

    	if($account_title->touch()) {
    		$return["success"] = true;
    		DB::commit();
    	} else {
    		$return["success"] = false;
    		DB::rollback();
    	}

    	return $return;
    }

}
