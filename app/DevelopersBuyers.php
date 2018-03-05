<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Developer;
use App\User;

use Auth;
use DB;

class DevelopersBuyers extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'developers_buyers';

    /**
    * Create a relationship between buyer and developer.
    *
    *
    */
    public static function linkBuyerToDeveloper(Buyer $buyer)
    {
    	DB::beginTransaction();

    	try {
	    	$developer = Developer::getCurrentDeveloper();
	    	$developers_buyers = new DevelopersBuyers();
	    	$developers_buyers->developer_id = $developer->id;
	    	$developers_buyers->buyer_id = $buyer->id;

	    	$return["success"] = $developers_buyers->touch();
	    	if ($return["success"]) {
	    		DB::commit();
	    	} else {
	    		DB::rollback();
	    	}
    	} catch(Exception $e) {
    		$return["success"] = false;
    		DB::rollback();
    	}

    	return $return;
    }

}
