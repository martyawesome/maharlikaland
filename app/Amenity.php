<?php

namespace App;

use App\Http\Requests\EditProjectAmenityRequest;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use DB;

class Amenity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'amenities';

    /**
    * Get all amenities of a specific project
    *
    */
    public static function getFromProject(Project $project)
    {
        return Amenity::whereProjectId($project->id)->get();
    }

    /**
    * Create amenites of a project.
    *
    */
    public static function addAmenities(Project $project, Request $request)
    {
        DB::beginTransaction();
        
        $amenities_to_be_added = $request->get("current_amenities");
        $counter = 0;
        $real_amenities = 0;

        for($i=0;$i<count($amenities_to_be_added);$i++){
            if($amenities_to_be_added[$i] != "") {
                $amenity = new Amenity();
                $amenity->project_id = $project->id;
                $amenity->amenity = $amenities_to_be_added[$i];
                $amenity->slug = Str::slug($amenity->amenity);

                if($amenity->touch()){
                    $counter++;
                }
                $real_amenities++;
            }
        }

        if($counter == $real_amenities){
            $return["success"] = true;
            DB::commit();
        } else {
            $return["success"] = false;
            DB::rollback();
        }

        return $return;
    }

    /**
    * Edit an amenity of a specific project.
    *
    */
    public static function editAmenity(Amenity $amenity, EditProjectAmenityRequest $request)
    {
        DB::beginTransaction();

        $amenity->amenity = $request->get("amenity");
        $amenity->slug = Str::slug($amenity->amenity);

        if($amenity->touch()){
            $return["success"] = true;
            DB::commit();
        } else {
            $return["success"] = false;
            DB::rollback();
        }

        return $return;
    }

    /**
    * Delete an amenity.
    *
    */
    public static function deleteAmenity(Amenity $amenity)
    {
        DB::beginTransaction();

        $return["success"] = $amenity->delete();

        if($return["success"]){
            DB::commit();
        } else {
            DB::rollback();
        }

        return $return;
    }

    /**
    * Delete vicinity map of the project
    * 
    */
    public static function deleteByProject(Project $project)
    {
        $amenities = Amenity::whereProjectId($project->id)->get();
        if($amenities != null and count($amenities) > 0){
            if(count($amenities) == Amenity::whereProjectId($project->id)->delete()){
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
    
}
