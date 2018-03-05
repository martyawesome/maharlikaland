<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Http\Requests\AddProjectRequest;
use App\Http\Requests\EditProjectSourcesRequest;
use App\Project;
use DB;

class ElectricitySource extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'electricity_sources';

    /**
    * Create and save the details of the electricity source of a newly created project.
    *
    */
    public static function createSource(Project $project, AddProjectRequest $request){
        $electricity_source = new ElectricitySource();
    	$electricity_source->project_id = $project->id;
    	$electricity_source->electricity_source = $request->get('electricity_source');
    	$return["success"] = $electricity_source->touch();

        return $return;
    }

    /**
    * Edit and save the details of the electricity source of an existing project.
    *
    */
    public static function editSource(Project $project, EditProjectSourcesRequest $request){
        $electricity_source = ElectricitySource::getByProject($project);
        $electricity_source->project_id = $project->id;
        $electricity_source->electricity_source = $request->get('electricity_source');
        $return["success"] = $electricity_source->touch();

        return $return;
    }

    /*
    * Get the electricity source of a specfic project.
    *
    */
    public static function getByProject(Project $project)
    {
        return ElectricitySource::whereProjectId($project->id)->first();
    }

    /**
    * Delete the electricity source of the project
    * 
    */
    public static function deleteByProject(Project $project)
    {
        if(ElectricitySource::whereProjectId($project->id)->count() > 0){
            return ElectricitySource::whereProjectId($project->id)->delete();
        } else {
            return true;
        }
    }
}
