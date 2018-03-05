<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Http\Requests\AddProjectRequest;
use App\Project;
use DB;

class PropertyLocation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'property_locations';

    /**
    * Get the properties of a specific lot.
    *
    */
    public static function getPropertiesByBlock(Project $project, Property $property)
    {
    	return PropertyLocation::join('properties','properties.id','=','property_locations.property_id')
        ->select(DB::raw('properties.*'))
        ->whereRaw('properties.project_id = '.$project->id.' and property_locations.block_number = '.$property->block_number)
        ->get();
    }

    /**
    * Get the property location of a property.
    *
    */
    public static function getPropertyLocationOfProperty(Property $property)
    {
        return PropertyLocation::wherePropertyId($property->id)->first();
    }

    /**
    * Delete the project location/s of the project
    * 
    */
    public static function deleteByProject(Project $project)
    {
        $property_locations = PropertyLocation::leftJoin('properties','properties.id','=','property_locations.property_id')
        ->whereRaw(DB::raw('properties.project_id = '.$project->id))
        ->get();
        
        if($property_locations != null and count($property_locations) > 0){
            if(count($property_locations) == PropertyLocation::leftJoin('properties','properties.id','=','property_locations.property_id')
                ->whereRaw(DB::raw('properties.project_id = '.$project->id))->delete()) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
    * Delete a property location.
    *
    */
    public static function deleteProperty(Property $property)
    {
        if(PropertyLocation::wherePropertyId($property->id)->count() == 0) {
            return true;
        } else {
            return PropertyLocation::wherePropertyId($property->id)->delete();
        }
    }

}
