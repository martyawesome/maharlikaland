<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class FloorArea extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'floor_areas';

    /**
    * Get all properties including their provinces and cities_municipalities.
    */
    public static function getByProperty(Property $property) {
        return FloorArea::leftJoin('floors','floors.id','=','floor_areas.floor_id')
        ->select(DB::raw('floor_areas.*, floors.floor'))
        ->whereRaw('floor_areas.property_id = '.$property->id)
        ->get();
    }

    /**
    * Delete the floor areas of the properties of the project
    * 
    */
    public static function deleteByProject(Project $project)
    {
        $floor_areas = FloorArea::leftJoin('properties','properties.id','=','floor_areas.property_id')
        ->where(DB::raw('properties.project_id = '.$project->id))
        ->get();
        
        if($floor_areas != null and count($floor_areas) > 0){
            if(count($floor_areas) == FloorArea::leftJoin('properties','properties.project_id','=','projects.id')->delete()) {
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
        if(FloorArea::wherePropertyId($property->id)->count() == 0) {
            return true;
        } else {
            return FloorArea::wherePropertyId($property->id)->delete();
        }
    }
    
}
