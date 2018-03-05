<?php

namespace App;

use Illuminate\Http\Request;
use App\Http\Requests\EditProjectIncentiveRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

class Incentive extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'incentives';

    /**
    * Get all amenities of a specific project
    *
    */
    public static function getFromProject(Project $project)
    {
        return Incentive::whereProjectId($project->id)->get();
    }

    /**
    * Create amenites of a project.
    *
    */
    public static function addIncentives(Project $project, Request $request)
    {
        DB::beginTransaction();

        $incentives_to_be_added = $request->get("current_incentives");
        $counter = 0;
        $real_counter = 0;

        for($i=0;$i<count($incentives_to_be_added);$i++){
            if($incentives_to_be_added[$i] != "") {
                $incentive = new Incentive();
                $incentive->project_id = $project->id;
                $incentive->incentive = $incentives_to_be_added[$i];
                $incentive->slug = Str::slug($incentive->incentive);
                
                if($incentive->touch()){
                    $counter++;
                }
                $real_counter++;
            }
        }

        if($counter == $real_counter) {
            DB::commit();
            $return["success"] = true;
        } else {
            DB::rollback();
            $return["success"] = false;
        }


        return $return;
    }

    /**
    * Edit an amenity of a specific project.
    *
    */
    public static function editIncentive(Incentive $incentive, EditProjectIncentiveRequest $request)
    {
        DB::beginTransaction();
       
        $incentive->incentive = $request->get("incentive");
        $incentive->slug = Str::slug($incentive->incentive);
        
        if($incentive->touch()) {
            DB::commit();
            $return["success"] = true;
        } else {
            DB::rollback();
            $return["success"] = false;
        }


        return $return;
    }

    /**
    * Delete an amenity.
    *
    */
    public static function deleteIncentive(Incentive $incentive)
    {
        DB::beginTransaction();

        $return["success"] = $incentive->delete();

        if($return["success"]){
            DB::commit();
        } else {
            DB::rollback();
        }

        return $return;
    }

    /**
    * Delete the incentives of the project
    * 
    */
    public static function deleteByProject(Project $project)
    {
        $incentives = Incentive::whereProjectId($project->id)->get();
        if($incentives != null and count($incentives) > 0){
            if(count($incentives) == Incentive::whereProjectId($project->id)->delete()){
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

}
