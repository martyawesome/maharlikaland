<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use App\Http\Requests\EditProjectJointVentureRequest;
use Illuminate\Support\Str;

use DB;

class JointVenture extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'joint_ventures';

    /**
    * Get the lists of joint ventures of a project.
    *
    */
    public static function getFromProject(Project $project)
    {
    	return JointVenture::whereProjectId($project->id)->get();
    }

    /**
    * Get the lists of joint ventures of a project.
    *
    */
    public static function getListForProject(Project $project)
    {
    	return JointVenture::whereProjectId($project->id)->lists('name', 'id');
    }

    /**
    * Create joint ventures of a project.
    *
    */
    public static function addJointVentures(Project $project, Request $request)
    {
        DB::beginTransaction();

        $joint_ventures_to_be_added = $request->get("current_joint_ventures");
        $counter = 0; 
        $real_counter = 0;   

        for($i=0;$i<count($joint_ventures_to_be_added);$i++){
            if($joint_ventures_to_be_added[$i] != "") {
                $joint_venture = new JointVenture();
                $joint_venture->project_id = $project->id;
                $joint_venture->name = $joint_ventures_to_be_added[$i];
                $joint_venture->slug = Str::slug($joint_venture->name);
                if($joint_venture->touch()) {
                    $counter++;
                }
                $real_counter++;
            }
        }

        if($counter == $real_counter++) {
            $return["success"] = true;
            DB::commit();
        } else {
            $return["success"] = false;
            DB::rollback();
        }

        return $return;
    }

    /**
    * Edit an joint venture of a specific project.
    *
    */
    public static function editJointVenture(JointVenture $joint_venture, EditProjectJointVentureRequest $request)
    {
        DB::beginTransaction();

        $joint_venture->name = $request->get("joint_venture");
        $joint_venture->slug = Str::slug($joint_venture->name);
        
        if($joint_venture->touch()) {
            $return["success"] = true;
            DB::commit();
        } else {
            $return["success"] = false;
            DB::rollback();
        }

        return $return;
    }

    /**
    * Delete a joint venture.
    * 
    */
    public static function deleteJointVenture(JointVenture $joint_venture)
    {
        DB::beginTransaction();

        $return["success"] = $joint_venture->delete();

        if($return["success"]) {
            DB::commit();
        } else {
            DB::rollback();
        }

        return $return;
    }

    /**
    * Delete the joint ventures of the project
    * 
    */
    public static function deleteByProject(Project $project)
    {
        $joint_ventures = JointVenture::whereProjectId($project->id)->get();
        if($joint_ventures != null and count($joint_ventures) > 0){
            if(count($joint_ventures) == JointVenture::whereProjectId($project->id)->delete()){
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

}
