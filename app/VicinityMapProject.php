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

class VicinityMapProject extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vicinity_maps_projects';

    /**
    * Get the vicnity map of a project using its slug.
    *
    */
    public static function getById(Project $project)
    {
    	return VicinityMapProject::whereProjectId($project->id)->first();
    }

    /**
    * Delete.
    *
    */
    public static function deleteById(Project $project)
    {
        $vicinity_map = VicinityMapProject::getById($project);

        DB::beginTransaction();

        if($vicinity_map) {
            $vicinity_map_db_deleted = $vicinity_map->delete();
            $vicinity_map_file_deleted = File::delete(public_path().'/'.$vicinity_map->image_path);
        } else {
            $vicinity_map_db_deleted = true;
            $vicinity_map_file_deleted = true;
        }

        if($vicinity_map_db_deleted and $vicinity_map_file_deleted) {
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
    public static function uploadVicinityMap(Project $project, $input)
    {
        $return = VicinityMapProject::deleteById($project);

        DB::beginTransaction();
        if($return) {
            $rules = array(
                'file' => 'image'
            );
     
            $validation = Validator::make($input, $rules);
     
            if ($validation->fails()) {
                return Response::make($validation->errors->first(), 400);
            }
     
            $destinationPath = public_path() .'/'. config('constants.PROJECTS_IMAGES_PATH').$project->slug.'/'.config('constants.PROJECTS_VICINITY_MAP_PATH'); // upload path
            $extension = Input::file('file')->getClientOriginalExtension(); // getting file extension
            $fileName = rand(11111, 99999) . '.' . $extension; // re-nameing image
            $upload_success = Input::file('file')->move($destinationPath, $fileName); // uploading file to given path

            if ($upload_success) {
                $new_vicinity_map = new VicinityMapProject();
                $new_vicinity_map->project_id = $project->id;
                $new_vicinity_map->image_path = config('constants.PROJECTS_IMAGES_PATH').$project->slug.'/'.config('constants.PROJECTS_VICINITY_MAP_PATH').$fileName;
                if($new_vicinity_map->touch()){
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

    /**
    * Delete vicinity map of the project
    * 
    */
    public static function deleteByProject(Project $project)
    {
        $vicinity_maps = VicinityMapProject::whereProjectId($project->id)->get();
        if($vicinity_maps != null and count($vicinity_maps) > 0){
            if(count($vicinity_maps) == VicinityMapProject::whereProjectId($project->id)->delete()){
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

}
