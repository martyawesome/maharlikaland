<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Auth;
use File;
use Session;
use Validator;
use Response;
use Input;

class PromotionalMaterial extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promotional_materials';

    /**
	* Get all of the promotional images of a developer regardless of the project. 
	*
	*/
    public static function getAllPromotionalImages()
    {
    	return PromotionalMaterial::selectRaw(DB::raw('projects.name as project_name, promotional_materials.*'))
    	->leftJoin('projects','projects.id','=','promotional_materials.project_id')
    	->whereRaw('promotional_materials.media_type_id = '.config('constants.MEDIA_TYPE_IMAGE'))
    	->get();
    }

    /**
    * Upload promotional images of a project.
    *
    */
    public static function uploadPromotionalImages(Project $project, $input)
    {
    	DB::beginTransaction();

    	$rules = array(
            'file' => 'image'
        );
 
        $validation = Validator::make($input, $rules);
 
        if ($validation->fails()) {
            return Response::make($validation->errors->first(), 400);
        }
 
        $destinationPath = public_path() .'/'. config('constants.PROMOTIONAL_IMAGES_PATH').$project->slug.'/'; // upload path
        $extension = Input::file('file')->getClientOriginalExtension(); // getting file extension
        $fileName = rand(11111, 99999) . '.' . $extension; // re-nameing image
        $upload_success = Input::file('file')->move($destinationPath, $fileName); // uploading file to given path
 
 		if ($upload_success) {
            $promotional_material = new PromotionalMaterial();
            $promotional_material->project_id = $project->id;
            $promotional_material->file_path = config('constants.PROMOTIONAL_IMAGES_PATH').$project->slug.'/'.$fileName;
            $promotional_material->media_type_id = config('constants.MEDIA_TYPE_IMAGE');
            $promotional_material->extension = $extension;

            if($promotional_material->touch()) {
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

    /**
    * Delete promotional images.
    *
    */
    public static function deletePromotionalImages(array $promotional_images)
    {
    	$counter = 0;
    	$promotional_images_count = count($promotional_images);

    	DB::beginTransaction();

    	foreach($promotional_images as $promotional_image) {
            File::delete(public_path().'/'.$promotional_image->file_path);

            $file_deleted = !File::exists(public_path().'/'.$promotional_image->file_path);
            $promotional_image_deleted = $promotional_image->delete();

			if($file_deleted and $promotional_image_deleted) {
            	$counter++;
            }
        }

        if($counter == $promotional_images_count) {
        	DB::commit();
        	$return["success"] = true;
        } else {
        	DB::rollback();
        	$return["success"] = false;
        }

        return $return;
    }

    /////////////////////////////////////// Promotional Videos ////////////////////////////////////

    /**
	* Get all of the promotional videos of a developer regardless of the project. 
	*
	*/
    public static function getAllPromotionalVideos()
    {
    	return PromotionalMaterial::selectRaw(DB::raw('projects.name as project_name, promotional_materials.*'))
    	->leftJoin('projects','projects.id','=','promotional_materials.project_id')
    	->whereRaw('promotional_materials.media_type_id = '.config('constants.MEDIA_TYPE_VIDEO'))
    	->get();
    }

    /**
    * Upload promotional images of a project.
    *
    */
    public static function uploadPromotionalVideos(Project $project, $input)
    {
    	DB::beginTransaction();

    	$rules = array(
            'file' => 'mimes:mp4,x-flv,x-mpegURL,MP2T,3gpp,quicktime,x-msvideo,x-ms-wmv,mkv,flv'
        );
 
        $validation = Validator::make($input, $rules);
 
        if ($validation->fails()) {
            return Response::make($validation->errors->first(), 400);
        }
 
        $destinationPath = public_path() .'/'. config('constants.PROMOTIONAL_VIDEOS_PATH').$project->slug.'/'; // upload path
        $extension = Input::file('file')->getClientOriginalExtension(); // getting file extension
        $fileName = rand(11111, 99999) . '.' . $extension; // re-nameing image
        $upload_success = Input::file('file')->move($destinationPath, $fileName); // uploading file to given path
 
 		if ($upload_success) {
            $promotional_material = new PromotionalMaterial();
            $promotional_material->project_id = $project->id;
            $promotional_material->file_path = config('constants.PROMOTIONAL_VIDEOS_PATH').$project->slug.'/'.$fileName;
            $promotional_material->media_type_id = config('constants.MEDIA_TYPE_VIDEO');
            $promotional_material->extension = $extension;

            if($promotional_material->touch()) {
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

    /**
    * Delete promotional images.
    *
    */
    public static function deletePromotionalVideos(array $promotional_videos)
    {
    	$counter = 0;
    	$promotional_videos_count = count($promotional_videos);

    	DB::beginTransaction();

    	foreach($promotional_videos as $promotional_video) {
            File::delete(public_path().'/'.$promotional_video->file_path);

            $file_deleted = !File::exists(public_path().'/'.$promotional_video->file_path);
            $promotional_video_deleted = $promotional_video->delete();

			if($file_deleted and $promotional_video_deleted) {
            	$counter++;
            }
        }

        if($counter == $promotional_videos_count) {
        	DB::commit();
        	$return["success"] = true;
        } else {
        	DB::rollback();
        	$return["success"] = false;
        }

        return $return;
    }

}
