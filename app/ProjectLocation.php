<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Http\Requests\AddProjectRequest;
use App\Http\Requests\EditProjectLocationRequest;
use App\Project;
use DB;

class ProjectLocation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_locations';

    /**
    * Create and save the details of the project locations of a newly created project.
    *
    */
    public static function createProjectLocation(Project $project, AddProjectRequest $request){
        $project_location = new ProjectLocation();
    	$project_location->project_id = $project->id;
    	$project_location->coordinates = $request->get('coordinates');
    	$project_location->province_id = $request->get('province');
    	$project_location->city_municipality_id = $request->get('city_municipality');
    	$project_location->barangay = $request->get('barangay');
    	$project_location->street = $request->get('street');
    	$project_location->remarks = $request->get('remarks');
    	$return["success"] = $project_location->touch();

        return $return;
    }

    /**
    * Create and save the details of the project locations of a newly created project.
    *
    */
    public static function updateProjectLocation(Project $project, EditProjectLocationRequest $request){
        $project_location = ProjectLocation::getByProject($project);
        $project_location->coordinates = $request->get('coordinates');
        $project_location->province_id = $request->get('province');
        $project_location->city_municipality_id = $request->get('city_municipality');
        $project_location->barangay = $request->get('barangay');
        $project_location->street = $request->get('street');
        $project_location->remarks = $request->get('remarks');
        $return["success"] = $project_location->touch();

        return $return;
    }

    /*
    * Get the location model of a specfic project.
    *
    */
    public static function getByProject(Project $project)
    {
        return ProjectLocation::whereProjectId($project->id)->first();
    }

    /**
    * Delete the project location/s of the project
    * 
    */
    public static function deleteByProject(Project $project)
    {
        if(ProjectLocation::whereProjectId($project->id)->count() > 0){
            return ProjectLocation::whereProjectId($project->id)->delete();
        } else {
            return true;
        }
    }

}
