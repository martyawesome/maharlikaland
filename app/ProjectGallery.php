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

class ProjectGallery extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_gallery';

    /**
    * Get the photos of a property.
    */
    public static function getAllById(Project $project)
    {
    	return ProjectGallery::whereProjectId($project->id)->get();
    }

    /**
    * Get the photos of a property.
    */
    public static function getByLastSixById(Project $project)
    {
    	return ProjectGallery::whereProjectId($project->id)->limit(6)->get();
    }

    /**
    * Upload the images of the project in the file directory and in the database.
    *
    */
    public static function uploadImages(Project $project, $input)
    {
    	DB::beginTransaction();

    	$rules = array(
            'file' => 'image'
        );
 
        $validation = Validator::make($input, $rules);
 
        if ($validation->fails()) {
            return Response::make($validation->errors->first(), 400);
        }
 
        $destinationPath = public_path() .'/'. config('constants.PROJECTS_IMAGES_PATH').$project->slug.'/'.config('constants.PROJECTS_SUBD_GALLERY_PATH'); // upload path
        $extension = Input::file('file')->getClientOriginalExtension(); // getting file extension
        $fileName = rand(11111, 99999) . '.' . $extension; // re-nameing image
        $upload_success = Input::file('file')->move($destinationPath, $fileName); // uploading file to given path
 
 		if ($upload_success) {
            $project_gallery = new ProjectGallery();
            $project_gallery->project_id = $project->id;
            $project_gallery->image_path = config('constants.PROJECTS_IMAGES_PATH').$project->slug.'/'.config('constants.PROJECTS_SUBD_GALLERY_PATH').$fileName;
            
            if($project_gallery->touch()) {
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

        return $return;
    }
}
