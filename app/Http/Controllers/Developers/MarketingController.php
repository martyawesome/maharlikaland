<?php

namespace App\Http\Controllers\Developers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\PromotionalMaterial;
use App\Project;
use App\Developer;

use Auth;
use File;
use DB;
use Session;
use Hash;
use Validator;
use Response;

class MarketingController extends Controller
{
    ////////////////////////////////// Promotional Images //////////////////////////////////////////

    /**
     * Show all promotional images.
     *
     */
    public function showPromotionalImages()
    {
        $promotional_images = PromotionalMaterial::getAllPromotionalImages();
        return view('developers.marketing.promotional_images.all', compact('promotional_images'));
    }

    /**
    * Select which project to upload the promotional images.
    *
    */
    public function uploadPromotionalImagesSelectProject()
    {
        $projects = Project::getAll();
        return view('developers.marketing.promotional_images.projects', compact('projects'));
    }

    /**
    * Show the form for uploading promotional images to a specific project.
    *
    */
    public function showUploadPromotionalImages(Project $project)
    {
        return view('developers.marketing.promotional_images.upload', compact('project'));
    }

    /**
    * Upload promotional images to a specific project.
    *
    */
    public function uploadPromotionalImages(Project $project)
    {
        $return = PromotionalMaterial::uploadPromotionalImages($project, Input::all());
        if($return["success"]) {
            return Response::json('success', 200);
        } else {
            return Response::json('error', 400);
        }   
    }

    /**
    * Show the webpage for deleting the promotional images.
    *
    */
    public function showDeletePromotionalImages(Project $project)
    {
        $promotional_images = PromotionalMaterial::getAllPromotionalImages();
        if(count($promotional_images) == 0) {
            return redirect(route('promotional_images', array($project->slug)))->withModal('No promotional images found');      
        } else {
            return view('developers.marketing.promotional_images.delete', compact('project','promotional_images'));
        }
    }

    /**
    * Delete the photos of a property.
    *
    */
    public function deletePromotionalImages(Request $request, array $images) {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = PromotionalMaterial::deletePromotionalImages($images);
            if($return["success"]) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        } 
    }

    ////////////////////////////////// Promotional Videos //////////////////////////////////////////

    /**
     * Show all promotional videos.
     *
     */
    public function showPromotionalVideos()
    {
        $promotional_videos = PromotionalMaterial::getAllPromotionalVideos();
        return view('developers.marketing.promotional_videos.all', compact('promotional_videos'));
    }

    /**
    * Select which project to upload the promotional videos.
    *
    */
    public function uploadPromotionalVideosSelectProject()
    {
        $projects = Project::getAll();
        return view('developers.marketing.promotional_videos.projects', compact('projects'));
    }

    /**
    * Show the form for uploading promotional videos to a specific project.
    *
    */
    public function showUploadPromotionalVideos(Project $project)
    {
        return view('developers.marketing.promotional_videos.upload', compact('project'));
    }

    /**
    * Upload promotional images to a specific project.
    *
    */
    public function uploadPromotionalVideos(Project $project)
    {
        $return = PromotionalMaterial::uploadPromotionalVideos($project, Input::all());
        if($return["success"]) {
            return Response::json('success', 200);
        } else {
            return Response::json('error', 400);
        }   
    }

    /**
    * Show the webpage for deleting the promotional images.
    *
    */
    public function showDeletePromotionalVideos(Project $project)
    {
        $promotional_videos = PromotionalMaterial::getAllPromotionalVideos();
        if(count($promotional_videos) == 0) {
            return redirect(route('promotional_videos', array($project->slug)))->withModal('No promotional videos found');      
        } else {
            return view('developers.marketing.promotional_videos.delete', compact('project','promotional_videos'));
        }
    }

    /**
    * Delete the photos of a property.
    *
    */
    public function deletePromotionalVideos(Request $request, array $images) {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = PromotionalMaterial::deletePromotionalVideos($images);
            if($return["success"]) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        } 
    }

}
