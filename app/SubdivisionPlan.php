<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;
use File;
use DB;
use Session;
use Hash;
use Validator;
use Response;
use Input;

class SubdivisionPlan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subdivision_plans';

    /**
    * Get the subd plan of a project using its slug.
    *
    */
    public static function getById(Project $project)
    {
    	return SubdivisionPlan::whereProjectId($project->id)->first();
    }

    /**
    * Delete.
    *
    */
    public static function deleteById(Project $project)
    {
        $subd_plan = SubdivisionPlan::getById($project);

        DB::beginTransaction();

        if($subd_plan) {
            $subd_plan_db_deleted = $subd_plan->delete();
            $subd_plan_file_deleted = File::delete(public_path().'/'.$subd_plan->image_path);
        } else {
            $subd_plan_db_deleted = true;
            $subd_plan_file_deleted = true;
        }

        if($subd_plan_db_deleted and $subd_plan_file_deleted) {
            DB::commit();
            $return["success"] = true;
        } else {
            DB::rollback();
            $return["success"] = false;
        }

        return $return;
    }

    /**
    * Delete current vicinity map and upload the new file.
    *
    */
    public static function uploadSubdPlan(Project $project, $input)
    {
        $return = SubdivisionPlan::deleteById($project);

        DB::beginTransaction();
        if($return) {
            $rules = array(
                'file' => 'image'
            );
     
            $validation = Validator::make($input, $rules);
     
            if ($validation->fails()) {
                return Response::make($validation->errors->first(), 400);
            }
     
            $destinationPath = public_path() .'/'. config('constants.PROJECTS_IMAGES_PATH').$project->slug.'/'.config('constants.PROJECTS_SUBD_PLAN_PATH'); // upload path
            $extension = Input::file('file')->getClientOriginalExtension(); // getting file extension
            $fileName = rand(11111, 99999) . '.' . $extension; // re-nameing image
            $upload_success = Input::file('file')->move($destinationPath, $fileName); // uploading file to given path

            if ($upload_success) {
                $new_subd_plan = new SubdivisionPlan();
                $new_subd_plan->project_id = $project->id;
                $new_subd_plan->image_path = config('constants.PROJECTS_IMAGES_PATH').$project->slug.'/'.config('constants.PROJECTS_SUBD_PLAN_PATH').$fileName;
                if($new_subd_plan->touch()){
                    DB::commit();
                    $return["success"] = true;
                } else {
                    DB::rollback();
                    $return["success"] = false;
                }
            } else {
                DB::rollback();
                $return["success"] = false;
            }
        } else {
            DB::rollback();
            $return["success"] = false;
        }

        return $return;
    }
}
