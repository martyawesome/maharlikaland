<?php

namespace App;

use Illuminate\Http\Request;
use App\Http\Requests\AddEditProspectBuyerRequest;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class ProspectBuyer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prospect_buyers';

    /**
    * Get all of the buyers according to the logged developer.
    *
    */
    public static function getByDeveloper()
    {
    	return ProspectBuyer::getCurrentDeveloper()->get();
    }

    /**
    * Update a prospect buyer's profile including a newly added prospect buyer.
    *
    */
    public static function updateProspectBuyer(ProspectBuyer $prospect_buyer, AddEditProspectBuyerRequest $request)
    {
    	DB::beginTransaction();
        try {
        	$prospect_buyer->first_name = $request->get('first_name');
        	$prospect_buyer->middle_name = $request->get('middle_name');
        	$prospect_buyer->last_name = $request->get('last_name');
            $prospect_buyer->sex = $request->get('sex');
        	$prospect_buyer->address = $request->get('address');
        	$prospect_buyer->contact_number = $request->get('contact_number');
        	$prospect_buyer->email = $request->get('email');
        	$prospect_buyer->agent_id = Auth::user()->agent_id;
        	$return["success"] = $prospect_buyer->touch();

            $return_prospect_property = ProspectProperty::updateProspectProperty($prospect_buyer, $request);

        	if($return["success"] && $return_prospect_property["success"]){
        		DB::commit();
        		$return["object"] = $prospect_buyer;
        	} else {
        		DB::rollback();
                $return["success"] = false;
        	}
        } catch(Exception $e) {
            DB::rollback();
            $return["success"] = false;
        }
        return $return;
    }

    /**
    * Delete a prospect buyer and his/her prospect properties.
    *
    */
    public static function deleteProspectBuyer(ProspectBuyer $prospect_buyer)
    {
        DB::beginTransaction();

        $return["success"] = ProspectProperty::whereProspectBuyerId($prospect_buyer->id)->delete();

        if($return["success"]) {
            $return["success"] = $prospect_buyer->delete();
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

}
