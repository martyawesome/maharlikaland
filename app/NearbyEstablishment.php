<?php

namespace App;

use Illuminate\Http\Request;
use App\Http\Requests\EditProjectNearbyEstablishmentRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use DB;

class NearbyEstablishment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nearby_establishments';

    /**
    * Get all amenities of a specific project
    *
    */
    public static function getFromProject(Project $project)
    {
        return NearbyEstablishment::whereProjectId($project->id)->get();
    }

    /**
    * Create amenites of a project.
    *
    */
    public static function addNearbyEstablishments(Project $project, Request $request)
    {   
        DB::beginTransaction();

        $nearby_establishments_to_be_added = $request->get("current_nearby_establishments");
        $counter = 0;
        $real_counter = 0;
        for($i=0;$i<count($nearby_establishments_to_be_added);$i++){
            if($nearby_establishments_to_be_added[$i] != "") {
                $nearby_establishment = new NearbyEstablishment();
                $nearby_establishment->project_id = $project->id;
                $nearby_establishment->nearby_establishment = $nearby_establishments_to_be_added[$i];
                $nearby_establishment->slug = Str::slug($nearby_establishment->nearby_establishment);
                
                if($nearby_establishment->touch()){
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

        return $return;
    }

    /**
    * Edit an amenity of a specific project.
    *
    */
    public static function editNearbyEstablishment(NearbyEstablishment $nearby_establishment, EditProjectNearbyEstablishmentRequest $request)
    {
        DB::beginTransaction();

        $nearby_establishment->nearby_establishment = $request->get("nearby_establishment");
        $nearby_establishment->slug = Str::slug($nearby_establishment->nearby_establishment);
        
        if($nearby_establishment->touch()) {
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
    public static function deleteNearbyEstablishment(NearbyEstablishment $nearby_establishment)
    {
        DB::beginTransaction();

        $return["success"] = $nearby_establishment->delete();

        if($return["success"]){
            DB::commit();
        } else {
            DB::rollback();
        }

        return $return;
    }

    /**
    * Delete the project location/s of the project
    * 
    */
    public static function deleteByProject(Project $project)
    {
        $nearby_establishments = NearbyEstablishment::whereProjectId($project->id)->get();
        if($nearby_establishments != null and count($nearby_establishments) > 0){
            if(count($nearby_establishments) == NearbyEstablishment::whereProjectId($project->id)->delete()){
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

}
