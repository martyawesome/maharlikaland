<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class PropertyGallery extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'property_gallery';

    /**
    * Get the photos of a property.
    */
    public static function getByProperty(Property $property)
    {
    	return PropertyGallery::wherePropertyId($property->id)->get();
    }

    /**
    * Delete the gallery of the properties of the project
    * 
    */
    public static function deleteByProject(Project $project)
    {
        $property_gallery = PropertyGallery::leftJoin('properties','properties.id','=','property_gallery.property_id')
        ->where(DB::raw('properties.project_id = '.$project->id))
        ->get();
        
        if(count($property_gallery) > 0){
            $counter = 0;
            for($i = 0; $i < count($property_gallery); $i++) {
                if(File::delete(public_path().'/'.$property_gallery[$i]->image_path))
                {
                    $counter++;
                }
            }

            if($counter == count($property_gallery)) {
                if($count($property_gallery) == PropertyGallery::leftJoin('properties','properties.id','=','property_gallery.property_id')
                ->where(DB::raw('properties.project_id = '.$project->id))
                ->delete()){
                    return true;
                } else {
                    return false;
                }   
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
    * Delete the property's gallery.
    *
    */
    public static function deleteProperty(Property $property)
    {
        if(PropertyGallery::wherePropertyId($property->id)->count() == 0) {
            return true;
        } else {
            return PropertyGallery::wherePropertyId($property->id)->delete() 
            and File::delete(public_path().'/img/properties/'.$property->slug);
        }
    }
}
