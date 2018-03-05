<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Http\Requests\AddProjectRequest;
use App\Http\Requests\EditProjectSourcesRequest;
use App\Project;

class WaterSource extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'water_sources';

    /**
    * Create and save the details of the water source of a newly created project.
    *
    */
    public static function createSource(Project $project, AddProjectRequest $request){
    	$water_source = new WaterSource();
    	$water_source->project_id = $project->id;
    	$water_source->water_source = $request->get('water_source');
    	$return["success"] = $water_source->touch();

        return $return;
    }

    /**
    * Edit and save the details of the water source of an existing project.
    *
    */
    public static function editSource(Project $project, EditProjectSourcesRequest $request){
        $water_source = WaterSource::getByProject($project);
        $water_source->project_id = $project->id;
        $water_source->water_source = $request->get('water_source');
        $return["success"] = $water_source->touch();

        return $return;
    }

    /*
    * Get the water source of a specfic project.
    *
    */
    public static function getByProject(Project $project)
    {
        return WaterSource::whereProjectId($project->id)->first();
    }

    /**
    * Delete the project location/s of the project
    * 
    */
    public static function deleteByProject(Project $project)
    {
        if(WaterSource::whereProjectId($project->id)->count() > 0){
            return WaterSource::whereProjectId($project->id)->delete();
        } else {
            return true;
        }
    }
}
