<?php

namespace App;

use App\Http\Requests\AddProjectRequest;
use App\Http\Requests\EditProjectBasicInfoRequest;
use App\Http\Requests\EditProjectLotRequest;

use App\ProjectLocation;
use App\ElectricitySource;
use App\WaterSource;
use App\NearbyEstablishment;
use App\PropertyLocation;
use App\FloorArea;
use App\PropertyGallery;
use App\InstallmentAccountLedger;
use App\Property;
use App\VicinityMapProject;
use App\JointVenture;
use App\Incentive;
use App\Amenity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Project extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'projects';

    /**
    * Get all properties including their provinces and cities_municipalities.
    */
    public static function getAll() {
        return Project::join('project_locations','project_locations.project_id','=','projects.id')
        ->join('provinces','project_locations.province_id','=','provinces.id')
        ->join('cities_municipalities','project_locations.city_municipality_id','=','cities_municipalities.id')
        ->select(DB::raw('projects.*, provinces.name as province, cities_municipalities.name as city_municipality'))
        ->whereDeveloperId(Developer::whereId(Auth::user()->developer_id)->first()->id)
        ->get();
    }

    /**
    * Get all properties including their provinces and cities_municipalities.
    */
    public static function getSingle(Project $project) {
        return Project::join('project_locations','project_locations.project_id','=','projects.id')
        ->join('provinces','project_locations.province_id','=','provinces.id')
        ->join('cities_municipalities','project_locations.city_municipality_id','=','cities_municipalities.id')
        ->join('project_types','project_types.id','=','projects.project_type_id')
        ->join('electricity_sources','electricity_sources.project_id','=','projects.id')
        ->join('water_sources','water_sources.project_id','=','projects.id')
        ->select(DB::raw('projects.*, project_locations.barangay, project_locations.street,
         project_locations.remarks, project_locations.coordinates, provinces.name as province,
          cities_municipalities.name as city_municipality, project_types.project_type,
          electricity_sources.electricity_source, water_sources.water_source '))
        ->whereRaw('projects.id = '.$project->id)
        ->first();
    }

    /**
    * Add a new project.
    * 
    */
    public static function addProject(AddProjectRequest $request) {
        $project = new Project();
    	$project->name = $request->get('name');
    	$project->slug = Str::slug($request->get('name'));
    	$project->project_type_id = $request->get('project_type');
    	$project->overview = $request->get('overview');
    	$project->opening_date = $request->get('opening_date');
    	$project->development_date = $request->get('development_date');
    	
        if($request->get('is_preselling') and $request->get('is_preselling') == "yes")
            $project->is_preselling = true;
        else
            $project->is_preselling = false;
      
        if($request->get('is_active') and $request->get('is_active') == "yes")
            $project->is_active = true;
        else
            $project->is_active = false;
    	
        $project->agent_id = Auth::user()->agent_id;
    	$project->developer_id = Auth::user()->developer_id;
        
        if($request->file('logo')) {
            $imagePath = public_path() .'/'.config('constants.PROJECTS_IMAGES_PATH').Str::slug($request->get('name')).'/main/';
            $imageName = 'logo.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move($imagePath, $imageName);
            
            $new_path = config('constants.PROJECTS_IMAGES_PATH').Str::slug($request->get('name')).'/main/'.$imageName;
            if($project->logo_path != $new_path) {
                File::delete(public_path() .'/'.$project->logo_path);
            }
            $project->logo_path = $new_path;
        } else {
            if($project->logo_path == "") {
                $project->logo_path = config('constants.PROJECTS_DEFAULT_IMAGE_PATH');
            }
        }

        if($request->file('banner')) {
            $imagePath = public_path() .'/'.config('constants.PROJECTS_IMAGES_PATH').Str::slug($request->get('name')).'/main/';
            $imageName = 'banner.' . $request->file('banner')->getClientOriginalExtension();
            $request->file('banner')->move($imagePath, $imageName);
            
            $new_path = config('constants.PROJECTS_IMAGES_PATH').Str::slug($request->get('name')).'/main/'.$imageName;
            if($project->banner_path != $new_path) {
                File::delete(public_path() .'/'.$project->banner_path);
            }
            $project->banner_path = $new_path;
        } else {
            if($project->banner_path == "") {
                $project->banner_path = config('constants.PROJECTS_DEFAULT_IMAGE_PATH');
            }
        }
        
    	$return["success"] = $project->touch();

        if($return["success"]) {
            $return["object"] = $project;
        } 

    	return $return;
    }
 
    /**
    * Edit the basic info of a specific project.
    *
    */
    public static function editBasicInfo(Project $project, EditProjectBasicInfoRequest $request)
    {
        DB::beginTransaction();

        $project->name = $request->get('name');
        $project->slug = Str::slug($request->get('name'));
        $project->project_type_id = $request->get('project_type');
        $project->overview = $request->get('overview');
        $project->opening_date = $request->get('opening_date');
        $project->development_date = $request->get('development_date');
        
        if($request->get('is_preselling') and $request->get('is_preselling') == "yes")
            $project->is_preselling = true;
        else
            $project->is_preselling = false;

        if($request->get('is_active') and $request->get('is_active') == "yes")
            $project->is_active = true;
        else
            $project->is_active = false;

        if($request->file('logo')) {
            $imagePath = public_path() .'/'.config('constants.PROJECTS_IMAGES_PATH').Str::slug($request->get('name')).'/main/';
            $imageName = 'logo.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move($imagePath, $imageName);
            
            $new_path = config('constants.PROJECTS_IMAGES_PATH').Str::slug($request->get('name')).'/main/'.$imageName;
            if($project->logo_path != $new_path) {
                File::delete(public_path() .'/'.$project->logo_path);
            }
            $project->logo_path = $new_path;
        } else {
            if($project->logo_path == "") {
                $project->logo_path = config('constants.PROJECTS_DEFAULT_IMAGE_PATH');
            }
        }

        if($request->file('banner')) {
            $imagePath = public_path() .'/'.config('constants.PROJECTS_IMAGES_PATH').Str::slug($request->get('name')).'/main/';
            $imageName = 'banner.' . $request->file('banner')->getClientOriginalExtension();
            $request->file('banner')->move($imagePath, $imageName);
            
            $new_path = config('constants.PROJECTS_IMAGES_PATH').Str::slug($request->get('name')).'/main/'.$imageName;
            if($project->banner_path != $new_path) {
                File::delete(public_path() .'/'.$project->banner_path);
            }
            $project->banner_path = $new_path;
        } else {
            if($project->banner_path == "") {
                $project->banner_path = config('constants.PROJECTS_DEFAULT_IMAGE_PATH');
            }
        }

        $return["success"] = $project->touch();
        $return["object"] = $project;

        if($return["success"]) {
            DB::commit();
        } else {
            DB::rollback();
        }

        return $return;
    }

    /**
    * Edit the lot number of the properties of a project.
    *
    */
    public static function editProjectLots(Project $project, Property $property, EditProjectLotRequest $request)
    {
        $properties = PropertyLocation::getPropertiesByLot($project, $property);
        $properties_count = count($properties);
        $counter = 0;
        
        DB::beginTransaction();
        foreach($properties as $property){
            $property->lot_number = $request->get("lot");
            $property->touch();
            ++$counter;
        }
        if($counter == $properties_count){
            DB::commit();
            $return["success"] = true;
        } else {
            DB::rollback();
            $return["success"] = false;
        }

        return $return;
    }

    /**
    * Delete all of the models the project.
    *
    */
    public static function deleteProject(Project $project)
    {
        DB::beginTransaction();

        $project_locations_deleted = ProjectLocation::deleteByProject($project);
        $electricity_source_deleted = ElectricitySource::deleteByProject($project);
        $water_source_deleted = WaterSource::deleteByProject($project);
        $nearby_establishments_deleted = NearbyEstablishment::deleteByProject($project);
        $property_locations_deleted = PropertyLocation::deleteByProject($project);
        $floor_areas_deleted = FloorArea::deleteByProject($project);
        $property_gallery_deleted = PropertyGallery::deleteByProject($project);
        $installment_account_ledger_deleted = InstallmentAccountLedger::deleteByProject($project);
        $properties_deleted = Property::deleteByProject($project);
        $vicinity_map_deleted = VicinityMapProject::deleteByProject($project);
        $joint_ventures_deleted = JointVenture::deleteByProject($project);
        $incentives_deleted = Incentive::deleteByProject($project);
        $amenities_deleted = Amenity::deleteByProject($project);
        $project_deleted = $project->delete();

        /*return 'project_location: '.$project_locations_deleted.' \n '.
            'electricity_source_deleted: '.$electricity_source_deleted.' \n '.
            'water_source_deleted: '.$water_source_deleted.' \n '.
            'nearby_establishments_deleted: '.$nearby_establishments_deleted.' \n '.
            'property_locations_deleted: '.$property_locations_deleted.' \n '.
            'floor_areas_deleted: '.$floor_areas_deleted.' \n '.
            'property_gallery_deleted: '.$property_gallery_deleted.' \n '.
            'installment_account_ledger_deleted: '.$installment_account_ledger_deleted.' \n '.
            'properties_deleted: '.$properties_deleted.' \n '.
            'vicinity_map_deleted: '.$vicinity_map_deleted.' \n '.
            'joint_ventures_deleted: '.$joint_ventures_deleted.' \n '.
            'incentives_deleted: '.$incentives_deleted.' \n '.
            'amenities_deleted: '.$amenities_deleted;*/

        if($project_locations_deleted and $electricity_source_deleted and $water_source_deleted
            and $nearby_establishments_deleted and $property_locations_deleted and $floor_areas_deleted 
            and $property_gallery_deleted and $installment_account_ledger_deleted and $properties_deleted
            and $vicinity_map_deleted and $joint_ventures_deleted and $incentives_deleted 
            and $amenities_deleted and $project_deleted) {
            DB::commit();
            $return["success"] = true;
        } else {
            DB::rollback();
            $return["success"] = false;
        }

        return $return;
    }

}
