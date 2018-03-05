<?php

namespace App;

use Illuminate\Http\Request;
use App\Http\Requests\AddEditProspectBuyerRequest;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class ProspectProperty extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prospect_properties';

    /**
    * Find or create a prospect property for a prospect buyer.
    *
    */
    public static function getForProspectBuyer(ProspectBuyer $prospect_buyer)
    {
    	$prospect_property = ProspectProperty::leftJoin('properties','properties.id','=','prospect_properties.property_id')
        ->select(DB::raw('properties.*, properties.id as property_id, prospect_properties.*, prospect_properties.id as prospect_property_id'))
        ->whereRaw('prospect_buyer_id = '.$prospect_buyer->id)
        ->first();

    	if($prospect_property) {
    		return $prospect_property;
    	} else {
    		return new ProspectProperty();
    	}
    }

    
    /**
    * Update a prospect property.
    *
    */
    public static function updateProspectProperty(ProspectBuyer $prospect_buyer, AddEditProspectBuyerRequest $request)
    {
    	$prospect_property = ProspectProperty::getForProspectBuyer($prospect_buyer);
    	$prospect_property->property_id = $request->get('property');
    	$prospect_property->agent_id = Auth::user()->agent_id;
    	$prospect_property->prospect_buyer_id = $prospect_buyer->id;
    	$return["success"] = $prospect_property->touch();
    	return $return;
    }

    /**
    * Delete a prospect property.
    *
    */
    public static function deleteProspectProperty(Property $property)
    {
        if(ProspectProperty::wherePropertyId($property->id)->count() == 0)
            return true;
        else
            return ProspectProperty::wherePropertyId($property->id)->delete();
    }
}
