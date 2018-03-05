<?php

namespace App;

use App\Http\Requests\AddPropertyRequest;
use App\Http\Requests\EditPropertyRequest;
use App\Http\Requests\AddProjectRequest;
use App\Http\Requests\SplitPropertyRequest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use App\InstallmentAccountLedger;
use App\User;
use App\Agent;
use App\Developer;
use App\Project;
use Auth;
use DB;

class Property extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'properties';

    /**
    * Get all properties including their provinces and cities_municipalities.
    */
    public static function getAll() {
        return Property::join('property_locations','property_locations.property_id','=','properties.id')
        ->join('provinces','property_locations.province_id','=','provinces.id')
        ->join('cities_municipalities','property_locations.city_municipality_id','=','cities_municipalities.id')
        ->select(DB::raw('properties.*, provinces.name as province, cities_municipalities.name as city_municipality'))
        ->whereAgentId(Agent::whereId(Auth::user()->agent_id)->first()->id)
        ->get();
    }

    /**
    * Get the blocks of a specific lot.
    *
    */
    public static function getLotsByBlock(Project $project, Property $property)
    {
        return Property::join('property_locations','properties.id','=','property_locations.property_id')
        ->join('property_statuses','property_statuses.id','=','properties.property_status_id')
        ->select(DB::raw('properties.*, property_statuses.property_status, property_locations.lot_number, property_locations.block_number'))
        ->whereRaw('properties.project_id = '.$project->id.' and property_locations.block_number = '.$property->block_number)
        ->orderByRaw('cast(lot_number as SIGNED) asc')
        ->get();
    }

    /**
    * Get all properties including their provinces and cities_municipalities.
    */
    public static function getSingle(Property $property) {
        return Property::leftJoin('property_types','property_types.id','=','properties.property_type_id')
        ->leftJoin('property_statuses','property_statuses.id','=','properties.property_status_id')
        ->leftJoin('number_of_bathrooms','number_of_bathrooms.id','=','properties.number_of_bathrooms_id')
        ->leftJoin('number_of_bedrooms','number_of_bedrooms.id','=','properties.number_of_bedrooms_id')
        ->leftJoin('parking_availability','parking_availability.id','=','properties.parking_availability_id')
        ->leftJoin('floors','floors.id','=','properties.floor_id')
        ->leftJoin('property_locations','property_locations.property_id','=','properties.id')
        ->leftJoin('provinces','property_locations.province_id','=','provinces.id')
        ->leftJoin('cities_municipalities','property_locations.city_municipality_id','=','cities_municipalities.id')       
        ->leftJoin('buyers','buyers.id','=','properties.buyer_id')
        ->leftJoin('joint_ventures','joint_ventures.id','=','properties.joint_venture_id')
        ->select(DB::raw("properties.*, property_types.property_type,
            property_statuses.property_status, number_of_bathrooms.bathrooms, number_of_bedrooms.bedrooms,
            parking_availability.parking_availability, floors.floor, property_locations.coordinates, property_locations.barangay, 
            property_locations.street, property_locations.lot_number, property_locations.block_number, property_locations.unit_number,
            property_locations.remarks, provinces.name as province, cities_municipalities.name as city_municipality, 
            concat(buyers.first_name,' ', buyers.middle_name,' ',buyers.last_name) AS buyer_name,
            joint_ventures.name as joint_venture"))
        ->whereRaw('properties.id = '.$property->id)
        ->first();
    }

    /**
    * Save property, property locations and floor areas.
    */
    public static function saveProperty(Project $project, AddPropertyRequest $request) {
        $property = new Property();
        $property_location = new PropertyLocation();

        if($project != null) {
            $property->project_id = $project->id;
        }

        $property->developer_id = Auth::user()->developer_id;
        $property->agent_id = Auth::user()->agent_id;
        
    	$property->name = $request->get('name');
    	$property->slug = Str::slug($request->get('name'));

        if($request->get('is_active') and $request->get('is_active') == "yes")
            $property->is_active = true;
        else
            $property->is_active = false;

    	$property->property_type_id = $request->get('property_type');
    	$property->floor_id = $request->get('floors');
    	$property->number_of_bedrooms_id = $request->get('number_of_bedrooms');
    	$property->number_of_bathrooms_id = $request->get('number_of_bathrooms');
    	if($request->get('floor_area'))
    		$property->floor_area = str_replace(',','',$request->get('floor_area'));
    	if($request->get('lot_area'))
    		$property->lot_area = str_replace(',','',$request->get('lot_area'));
    	if($request->get('furnished') and $request->get('furnished') == "yes")
    		$property->is_furnished = true;
    	else
    		$property->is_furnished = false;
    	$property->parking_availability_id = $request->get('parking_availability');
    	$property->price = str_replace(',','',$request->get('price'));
    	$property->price_per_sqm = str_replace(',','',$request->get('price_per_sqm'));
    	$property->property_status_id = $request->get('property_status');
    	$property->main_picture_path = config('constants.PROPERTIES_DEFAULT_IMAGE_PATH');
        if($request->get('buyer'))
            $property->buyer_id = $request->get('buyer');

        if($property->touch()) {
        	$property_location->property_id = $property->id;
        	if($request->get('coordinates'))
        		$property_location->coordinates = $request->get('coordinates');
        	$property_location->province_id = $request->get('province');
        	$property_location->city_municipality_id = $request->get('city_municipality');
        	if($request->get('barangay'))
        		$property_location->barangay = $request->get('barangay');
        	if($request->get('street'))
        		$property_location->street = $request->get('street');
        	if($request->get('lot_number'))
        		$property_location->lot_number = $request->get('lot_number');
        	if($request->get('block_number'))
        		$property_location->block_number = $request->get('block_number');
        	if($request->get('unit_number'))
        		$property_location->unit_number = $request->get('unit_number');
        	if($request->get('remarks'))
        		$property_location->remarks = $request->get('remarks');
        	
            if($property_location->touch()){
                $counter = 0;
                $real_counter = 0;
                $floor_area_per_floor = $request->get('floor_area_per_floor');
            	for($i = 1; $i <= $request->get('floors');$i++){
                    if($floor_area_per_floor[$i]){
        	    		$floor_area_per_floor = new FloorArea();
        	    		$floor_area_per_floor->property_id = $property->id;
        	    		$floor_area_per_floor->floor_id = $i;
        	    		if(strpos($floor_area_per_floor[$i],',') == true) {
                            $floor_area_per_floor[$i] = str_replace(',','',$floor_area_per_floor[$i]);
                        }
                        $floor_area_per_floor->floor_area = (double) $floor_area_per_floor[$i];
        				
                        if($floor_area_per_floor->touch()) {
                            $counter++;
                        }
                        $real_counter++;
            		}
            	}

                if($counter == $real_counter) {
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
        } else {
            $return["success"] = false;
            DB::rollback();
        }

        return $return;
    }

    public static function editProperty(Property $property, AddPropertyRequest $request) {
        $property_location = PropertyLocation::wherePropertyId($property->id)->first();

        DB::beginTransaction();

        $property->name = $request->get('name');
        $property->slug = Str::slug($request->get('name'));

        if($request->get('is_active') and $request->get('is_active') == "yes")
            $property->is_active = true;
        else
            $property->is_active = false;

        $property->property_type_id = $request->get('property_type');
        $property->floor_id = $request->get('floors');
        $property->number_of_bedrooms_id = $request->get('number_of_bedrooms');
        $property->number_of_bathrooms_id = $request->get('number_of_bathrooms');
        if($request->get('floor_area'))
            $property->floor_area = str_replace(',','',$request->get('floor_area'));
        if($request->get('lot_area'))
            $property->lot_area = str_replace(',','',$request->get('lot_area'));
        if($request->get('furnished') and $request->get('furnished') == "yes")
            $property->is_furnished = true;
        else
            $property->is_furnished = false;
        $property->parking_availability_id = $request->get('parking_availability');
        $property->price = str_replace(',','',$request->get('price'));
        $property->price_per_sqm = str_replace(',','',$request->get('price_per_sqm'));
        $property->property_status_id = $request->get('property_status');

        if($request->get('buyer'))
            $property->buyer_id = $request->get('buyer');
        if($request->get('agent'))
            $property->agent_id = $request->get('agent');
        if($request->get('joint_venture'))
            $property->joint_venture_id = $request->get('joint_venture');

        if($property->property_status_id == config('constants.PROPERTY_STATUS_SOLD')) {
            if(count(ProspectProperty::wherePropertyId($property->id)->get()) > 0) {
                $prospect_properties_deleted = ProspectProperty::wherePropertyId($property->id)->delete();
            } else {
                $prospect_properties_deleted = true;
            }
        } else {
            $prospect_properties_deleted = true;
        }

        if($property->touch() and $prospect_properties_deleted) {
            $property_location->property_id = $property->id;
            if($request->get('coordinates'))
                $property_location->coordinates = $request->get('coordinates');
            $property_location->province_id = $request->get('province');
            $property_location->city_municipality_id = $request->get('city_municipality');
            if($request->get('barangay'))
                $property_location->barangay = $request->get('barangay');
            if($request->get('street'))
                $property_location->street = $request->get('street');
            if($request->get('lot_number'))
                $property_location->lot_number = $request->get('lot_number');
            if($request->get('block_number'))
                $property_location->block_number = $request->get('block_number');
            if($request->get('unit_number'))
                $property_location->unit_number = $request->get('unit_number');
            if($request->get('remarks'))
                $property_location->remarks = $request->get('remarks');
            
            if($property_location->touch()) {
                if(count(FloorArea::wherePropertyId($property->id)->get()) > 0) {
                    $floor_area_delete = FloorArea::wherePropertyId($property->id)->delete();
                } else {
                    $floor_area_delete = true;
                }

                $counter = 0;
                $real_counter = 0;
                $floor_area_per_floor = $request->get('floor_area_per_floor');
                for($i = 1; $i <= $request->get('floors') ;$i++){
                    if(strlen($floor_area_per_floor[$i]) > 0){
                        $new_floor_area_per_floor = new FloorArea();
                        $new_floor_area_per_floor->property_id = $property->id;
                        $new_floor_area_per_floor->floor_id = $i;

                        if(strpos($floor_area_per_floor[$i], ",")) {
                            $floor_area_per_floor[$i] = str_replace(",", "", $floor_area_per_floor[$i]);
                        }

                        $new_floor_area_per_floor->floor_area = (double) $floor_area_per_floor[$i];
                        
                        if($new_floor_area_per_floor->touch()) {
                            $counter++;
                        }
                        $real_counter++;
                    }
                }

                if($floor_area_delete and $counter == $real_counter) {
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
        } else {
            $return["success"] = false;
            DB::rollback();
        }
        
        return $return;
    }

    /**
    * Create the project's properties
    *
    */
    public static function createProjectProperties(Project $project, AddProjectRequest $request) {
        $lots_blocks_checker = false;

        for($i=0;$i<intval($request->get('blocks'));$i++) {
            $lots_blocks = intval($request->get('lots_blocks')[$i]);
            $lots_blocks_counter = 0;

            for($j=0;$j<$lots_blocks;$j++) {
                $property = new Property();
                $property->project_id = $project->id;
                $property->property_status_id = config('constants.PROPERTY_STATUS_FOR_SALE');
                $property->name = $request->get('name') . " Block " . ($i+1) . " Lot " . ($j+1);
                $property->slug = Str::slug($property->name);
                $property->developer_id = Auth::user()->developer_id;
                $property->main_picture_path = config('constants.PROPERTIES_DEFAULT_IMAGE_PATH');
                
                if($property->touch()) {
                    $property_location = new PropertyLocation();
                    $property_location->property_id = $property->id;
                    $property_location->province_id = $request->get('province'); 
                    $property_location->city_municipality_id = $request->get('city_municipality'); 
                    $property_location->barangay = $request->get('barangay');
                    $property_location->block_number = $i+1;
                    $property_location->lot_number = $j+1;
                    
                    if($property_location->touch()){
                        $lots_blocks_counter++;
                    }
                }
            }

            if($lots_blocks_counter == $lots_blocks) {
                $lots_blocks_checker = true;
            } else {
                $lots_blocks_checker = false;
            }
        }

        if($lots_blocks_checker) {
            $return["success"] = true;
        } else {
            $return["success"] = false;
        }

        return $return;
    }

    /**
    * Get all properties from a specific project
    *
    */
    public static function getFromProjectGroupedByBlocks(Project $project)
    {
        return Property::join('property_locations','property_locations.property_id','=','properties.id')
        ->join('property_statuses','property_statuses.id','=','properties.property_status_id')
        ->select(DB::raw('properties.*, property_locations.lot_number, property_locations.block_number,
         property_locations.unit_number, property_locations.remarks as location_remarks,
          property_locations.coordinates, property_statuses.property_status'))
        ->whereRaw('properties.project_id = '. $project->id)->groupBy('block_number')
        ->get();
    }

    /**
    * Get all properties from a specific project
    *
    */
    public static function getFromProject(Project $project)
    {
        return Property::join('property_locations','property_locations.property_id','=','properties.id')
        ->join('property_statuses','property_statuses.id','=','properties.property_status_id')
        ->select(DB::raw('properties.*, property_locations.lot_number as lot_number, property_locations.block_number
         as block_number, property_locations.unit_number as unit_number, property_locations.remarks as location_remarks,
          property_locations.coordinates as coordinates, property_statuses.property_status as property_status'))
        ->whereRaw('properties.project_id = '. $project->id)
        ->orderByRaw('block_number asc')
        ->orderByRaw('cast(lot_number as SIGNED) asc')
        ->get();
    }

    /**
    * Get the property of a buyer.
    *
    */
    public static function getPropertyFromLedger(InstallmentAccountLedger $ledger)
    {
        return Property::find($ledger->property_id);
    }

    /**
    * Get the properties of a buyer.
    *
    */
    public static function getPropertiesOfBuyer(Buyer $buyer)
    {
        return Property::whereBuyerId($buyer->id)->get();
    }

    /**
    * Get the properties of a buyer.
    *
    */
    public static function getPropertiesOfBuyerForForm(Buyer $buyer)
    {
        return Property::whereBuyerId($buyer->id)->lists('name','id');
    }

    /**
    * Get the properties of a buyer.
    *
    */
    public static function getPropertiesOfBuyerForNewLedger(Buyer $buyer)
    {
        return Property::whereNotExists(function($query) use ($buyer)
            {
                $query->select(DB::raw(1))
                      ->from('installment_account_ledger')
                      ->whereRaw('installment_account_ledger.property_id = properties.id and installment_account_ledger.buyer_id = '.$buyer->id);
            })->whereRaw("buyer_id = 0")->lists('name','id');
    }


    /**
    * Get the list of properties to be including when adding or editing a buyer.
    *
    */
    public static function getForBuyers(Buyer $buyer)
    {
        if($buyer->id != null) {
            return Property::whereRaw('properties.developer_id = '.Auth::user()->developer_id.' and
                (properties.buyer_id = '.$buyer->id.' or properties.buyer_id IS NULL)')
            ->lists('name', 'id');
        } else {
            return Property::whereRaw('properties.developer_id = '.Auth::user()->developer_id.' and 
                properties.buyer_id IS NULL or properties.buyer_id = 0')
            ->lists('name', 'id');
        }
    }

    /**
    * Get the list of properties to be including when adding or editing a prospect buyer.
    *
    */
    public static function getForProspectBuyers(ProspectBuyer $buyer)
    {
        return Property::whereRaw('properties.developer_id = '.Auth::user()->developer_id.' and 
            properties.buyer_id IS NULL')->lists('name', 'id');
    }

    /**
    * Get the list of properties to be including when adding or editing a prospect buyer.
    *
    */
    public static function getForProspectBuyer(ProspectBuyer $buyer)
    {
        return Property::leftJoin('prospect_properties','prospect_properties.property_id','=','properties.id')->
        whereRaw('prospect_properties.prospect_buyer_id = '.$buyer->id)->first();
    }

    /**
    * Split a property.
    *
    */
    public static function splitProperty(Project $project, Property $property, SplitPropertyRequest $request)
    {
        DB::beginTransaction();
        $property_location = PropertyLocation::getPropertyLocationOfProperty($property);
        
        $previous_lot_number = $property_location->lot_number;
        $lots_lot_area = $request->get('lots_lot_area');

        $property_location->lot_number = $previous_lot_number."-A";
        $current_alphabet = 'B';

        $counter = 0;
        $property->name = $project->name.' Block '.$property_location->block_number.' Lot '.$property_location->lot_number;
        $property->slug = Str::slug($property->name);
        $property->lot_area = $lots_lot_area[0];
        if($property->touch() and $property_location->touch()) {
            $counter++;
        }

        for($i=1;$i<$request->get('lots');$i++){
            $new_property = new Property();
            $new_property->property_type_id = $property->property_type_id;
            $new_property->project_id = $project->id;
            $new_property->is_active = true;
            $new_property->lot_area = $lots_lot_area[$i];
            $new_property->property_status_id = $property->property_status_id;
            $new_property->developer_id = Auth::user()->developer_id;
            $new_property->main_picture_path = config('constants.PROPERTIES_DEFAULT_IMAGE_PATH');
            $new_property->joint_venture_id = $property->joint_venture_id;
            $new_property->buyer_id = $property->buyer_id;

            $new_property_success = $new_property->touch();

            $new_property_location = new PropertyLocation();
            $new_property_location->property_id = $new_property->id;
            $new_property_location->province_id = $property_location->province_id;
            $new_property_location->city_municipality_id = $property_location->city_municipality_id;
            $new_property_location->barangay = $property_location->barangay;
            $new_property_location->street = $property_location->street;
            $new_property_location->block_number = $property_location->block_number;
            $new_property_location->lot_number = $previous_lot_number.'-'.$current_alphabet++;

            $new_property->name = $project->name.' Block '.$new_property_location->block_number.' Lot '.$new_property_location->lot_number;
            $new_property->slug = Str::slug($new_property->name);

            $new_property_success = $new_property->touch();
            $new_property_location_success = $new_property_location->touch();

            if($new_property_success and $new_property_location_success) {
                $counter++;
            }
        }

        if($counter == $request->get('lots')) {
            $return["success"] = true;
            DB::commit();
        } else {
            $return["success"] = false;
            DB::rollback();
        }

        return $return;
    }

    /**
    * Delete a project's properties
    *
    */
    public static function deleteByProject(Project $project)
    {
        $properties = Property::whereProjectId($project->id)->get();
        if($properties != null and count($properties) > 0){
            if(count($properties) == Property::whereProjectId($project->id)->delete()){
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
    * Delete a property.
    *
    */
    public static function deleteProperty(Property $property)
    {
        DB::beginTransaction();

        // prospect properties
        $prospect_properties_deleted = ProspectProperty::deleteProspectProperty($property);
        
        // property gallery
        // property gallery directory
        $gallery_deleted = PropertyGallery::deleteProperty($property);
        
        // property location
        $location_deleted = PropertyLocation::deleteProperty($property);
        
        // floor areas
        $floor_area_deleted = FloorArea::deleteProperty($property);

        // installment account ledger
        // installment account ledger details
        $installment_account_ledger_deleted = InstallmentAccountLedger::deleteProperty($property);

        // properties table
        $property_deleted = $property->delete();
        
        if($prospect_properties_deleted and $gallery_deleted and $location_deleted and $floor_area_deleted
            and $installment_account_ledger_deleted and $property_deleted){
            DB::commit();
            $return["success"] = true;
        } else {
            $return["success"] = false;
            DB::rollback();
        }

        return $return;
    }

}
