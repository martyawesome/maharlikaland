<?php

namespace App\Http\Controllers\Developers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use App\Http\Requests\AddProjectRequest;
use App\Http\Requests\EditProjectBasicInfoRequest;
use App\Http\Requests\EditProjectLocationRequest;
use App\Http\Requests\EditProjectJointVentureRequest;
use App\Http\Requests\EditProjectAmenityRequest;
use App\Http\Requests\EditProjectSourcesRequest;
use App\Http\Requests\EditProjectNearbyEstablishmentRequest;
use App\Http\Requests\EditProjectIncentiveRequest;
use App\Http\Requests\EditProjectLotRequest;
use App\Http\Controllers\Controller;

use App\User;
use App\Agent;
use App\Project;
use App\ProjectType;
use App\ProjectLocation;
use App\Property;
use App\PropertyType;
use App\PropertyStatus;
use App\NumberOfBathroom;
use App\NumberOfBedroom;
use App\ParkingAvailability;
use App\PropertyLocation;
use App\Floor;
use App\FloorArea;
use App\Province;
use App\CityMunicipality;
use App\PropertyGallery;
use App\WaterSource;
use App\ElectricitySource;
use App\JointVenture;
use App\Amenity;
use App\NearbyEstablishment;
use App\Incentive;
use App\Developer;
use App\VicinityMapProject;
use App\SubdivisionPlan;
use App\ProjectGallery;

use Auth;
use File;
use DB;
use Session;
use Hash;
use Validator;
use Response;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAddProject()
    {
        $project = new Project();
        $project_location = new PropertyLocation();
        $provinces = Province::get();
        $provinces_list = Province::lists('name','id');
        $cities_municipalities = CityMunicipality::get();
        $project_type_list = ProjectType::lists('project_type', 'id');
        $electricity_source = new ElectricitySource();
        $water_source = new WaterSource();
        return view('developers.projects.add',compact('project','project_location','provinces','provinces_list',
            'cities_municipalities', 'project_type_list','electricity_source','water_source'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addProject(AddProjectRequest $request)
    {
        DB::beginTransaction();

        $project = Project::addProject($request);

        if($project["success"]) {
            $project_location = ProjectLocation::createProjectLocation($project["object"], $request);
            if($project_location["success"]){
                $electricity_source = ElectricitySource::createSource($project["object"], $request);
                if($electricity_source["success"]) {
                    $water_source = WaterSource::createSource($project["object"], $request);
                    if($water_source["success"]) {
                        $properties = Property::createProjectProperties($project["object"], $request);
                        if($properties["success"]) {
                            DB::commit();
                            return redirect(route('developer_dashboard'))->withSuccess('<i>'.$project["object"]->name.'</i> successfully created.');
                        }
                    } 
                } 
            }
        } 

        DB::rollback();
        return redirect(route('add_project'))->withDanger('<i>'.$request->get('name').'</i> unsuccessfully created.');   
    }

    /**
    * Show all projects of the current developer
    *
    */ 
    public function showAllProjects()
    {
        $projects = Project::getAll();
        return view('developers.projects.all',compact('projects'));
    }

    /**
    * Show the details of a specific project
    *
    */ 
    public function viewProject(Project $project)
    {
        $project = Project::getSingle($project);
        $properties = Property::getFromProjectGroupedByBlocks($project);
        $joint_ventures = JointVenture::getFromProject($project);
        $amenities = Amenity::getFromProject($project);
        $nearby_establishments = NearbyEstablishment::getFromProject($project);
        $incentives = Incentive::getFromProject($project);
        $developer = Developer::getCurrentDeveloper();
        $vicinity_map_project = VicinityMapProject::getById($project);
        $subd_plan = SubdivisionPlan::getById($project);
        $gallery = ProjectGallery::getByLastSixById($project);
        return view('developers.projects.view',compact('project','properties','joint_ventures','amenities',
            'nearby_establishments','incentives','developer','vicinity_map_project','subd_plan','gallery'));
    }

    /**
    * Show the form for editing the basic info of the project
    *
    */
    public function showEditBasicInfo(Project $project)
    {
        $project_type_list = ProjectType::whereId($project->project_type_id)->lists('project_type', 'id');
        return view('developers.projects.edit.basic_info', compact('project','project_type_list'));
    }

    /**
    * Edit the basic info of a specific project
    *
    */
    public function editBasicInfo(Project $project, EditProjectBasicInfoRequest $request)
    {
        $return = Project::editBasicInfo($project, $request);
        
        if($return["success"]) {
            return redirect(route('project', $return["object"]->slug))->withSuccess('<i>'.$return["object"]->name.'</i>\'s basic info successfully edited.');
        } else {
            return redirect(route('project', $project->slug))->withDanger('<i>'.$project->name.'</i>\'s basic info unsuccessfully edited.');
        }
    }

    /**
    * Show the form for editing an amenity of a project
    *
    */
    public function showEditJointVentures(Project $project)
    {
        $current_joint_ventures = JointVenture::getFromProject($project);
        return view('developers.projects.edit.joint_ventures', compact('project','current_joint_ventures'));
    }

    /**
    * Edit the amenities of an existing project.
    *
    */ 
    public function editJointVentures(Project $project, Request $request)
    {
        $return = JointVenture::addJointVentures($project, $request);

        if($return["success"]) {
            return redirect(route('project', $project->slug))->withSuccess('<i>'.$project->name.'</i>\'s joint ventures successfully edited.');
        } else {
            return redirect(route('project', $project->slug))->withDanger('<i>'.$project->name.'</i>\'s joint ventures unsuccessfully edited.');
        }
    }

    /**
    * Show the form for editing the joint ventures of a project
    *
    */
    public function showEditJointVenture(Project $project, JointVenture $joint_venture)
    {
        $developer = Developer::getCurrentDeveloper();
        return view('developers.projects.edit.joint_venture', compact('project','joint_venture','developer'));
    }

    /**
    * Edit an joint_venture of a project
    *
    */
    public function editJointVenture(Project $project, JointVenture $joint_venture, EditProjectJointVentureRequest $request)
    {
        $return = JointVenture::editJointVenture($joint_venture, $request);

        if($return["success"]) {
            return redirect(route('project', $project->slug))->withSuccess('<i>'.$project->name.'</i>\'s joint venture successfully edited.');
        } else {
            return redirect(route('project', $project->slug))->withDanger('<i>'.$project->name.'</i>\'s joint venture unsuccessfully edited.');
        }
    }

    /**
    * Show the amenities of the project
    *
    */
    public function showAmenities(Project $project)
    {
        $current_amenities = Amenity::getFromProject($project);
        return view('developers.projects.amenities', compact('project','current_amenities'));
    }

    /**
    * Show the form for editing an amenity of a project
    *
    */
    public function showEditAmenities(Project $project)
    {
        $current_amenities = Amenity::getFromProject($project);
        return view('developers.projects.edit.amenities', compact('project','current_amenities'));
    }

    /**
    * Edit the amenities of an existing project.
    *
    */ 
    public function editAmenities(Project $project, Request $request)
    {
        $return = Amenity::addAmenities($project, $request);
        
        if($return["success"]) {
            return redirect(route('project', $project->slug))->withSuccess('<i>'.$project->name.'</i>\'s amenities successfully edited.');
        } else {
            return redirect(route('project', $project->slug))->withDanger('<i>'.$project->name.'</i>\'s amenities successfully edited.');
        }
    }

    /**
    * Show the form for editing the amenities of a project
    *
    */
    public function showEditAmenity(Project $project, Amenity $amenity)
    {
        return view('developers.projects.edit.amenity', compact('project','amenity'));
    }

    /**
    * Edit an amenity of a project
    *
    */
    public function editAmenity(Project $project, Amenity $amenity, EditProjectAmenityRequest $request)
    {
        $return = Amenity::editAmenity($amenity, $request);

        if($return["success"]) {
            return redirect(route('project', $project->slug))->withSuccess('<i>'.$project->name.'</i>\'s amenity successfully edited.');
        } else {
            return redirect(route('project', $project->slug))->withDanger('<i>'.$project->name.'</i>\'s amenity successfully edited.');
        }
    }

    /**
    * Show the form for editing the incentives of the project
    *
    */
    public function showEditIncentives(Project $project)
    {
        $current_incentives = Incentive::getFromProject($project);
        return view('developers.projects.edit.incentives', compact('project','current_incentives'));
    }

    /**
    * Show the form for editing the nearby establishments of the project
    *
    */
    public function editIncentives(Project $project, Request $request)
    {
        $return = Incentive::addIncentives($project, $request);
        
        if($return["success"]) {
            return redirect(route('project', $project->slug))->withSuccess('<i>'.$project->name.'</i>\'s incentives successfully edited.');
        } else {
            return redirect(route('project', $project->slug))->withDanger('<i>'.$project->name.'</i>\'s incentives successfully edited.');
        }
    }

    /**
    * Show the form for editing the nearby establishments of the project
    *
    */
    public function showEditIncentive(Project $project, Incentive $incentive)
    {
        return view('developers.projects.edit.incentive', compact('project','incentive'));
    }

    /**
    * Show the form for editing the nearby establishments of the project
    *
    */
    public function editIncentive(Project $project, Incentive $incentive, EditProjectIncentiveRequest $request)
    {
        $return = Incentive::editIncentive($incentive, $request);
        
        if($return["success"]) {
            return redirect(route('project', $project->slug))->withSuccess('<i>'.$project->name.'</i>\'s incentive successfully edited.');
        } else {
            return redirect(route('project', $project->slug))->withDanger('<i>'.$project->name.'</i>\'s incentive successfully edited.');
        }
    }

    /**
    * Show the form for editing the location of the project
    *
    */
    public function showEditLocation(Project $project)
    {
        $project_location = ProjectLocation::getByProject($project);
        $provinces = Province::get();
        $provinces_list = Province::lists('name','id');
        $cities_municipalities = CityMunicipality::get();
        return view('developers.projects.edit.location', compact('project','project_location','provinces','provinces_list','cities_municipalities'));
    }

    public function editLocation(Project $project, EditProjectLocationRequest $request)
    {
        $return = ProjectLocation::updateProjectLocation($project, $request);
        
        if($return["success"]) {
            return redirect(route('project', $project->slug))->withSuccess('<i>'.$project->name.'</i>\'s location successfully edited.');
        } else {
            return redirect(route('project', $project->slug))->withDanger('<i>'.$project->name.'</i>\'s location successfully edited.');
        }
    }

    /**
    * View the lots of a specific lot of a project.
    *
    */
    public function viewProjectLots(Project $project, Property $property)
    {
        $project = Project::getSingle($project);
        $properties = Property::getLotsByBlock($project, $property);
        return view('developers.projects.lots', compact('project','properties'));
    }

    /**
    * Edit the lot number of a project.
    *
    */
    public function editProjectLot(Project $project, Property $property, EditProjectLotRequest $request)
    {
        $return = Project::editProjectLots($project, $property, $request);
        
        if($return["success"])
            return redirect(route('project_lot', array($project->slug,$property->lot_number)))->withSuccess('<i>'.$project->name.'</i>\'s lot was successfully edited.');
        else
            return redirect(route('project_lot', array($project->slug,$property->lot_number)))->withDanger('<i>'.$project->name.'</i>\'s lot was unsuccessfully to edited.');
        
    }

    /**
    * Show the form for editing the nearby establishments of the project
    *
    */
    public function showEditNearbyEstablishments(Project $project)
    {
        $nearby_establishments = NearbyEstablishment::getFromProject($project);
        return view('developers.projects.edit.nearby_establishments', compact('project','nearby_establishments'));
    }

    /**
    * Show the form for editing the nearby establishments of the project
    *
    */
    public function editNearbyEstablishments(Project $project, Request $request)
    {
        $return = NearbyEstablishment::addNearbyEstablishments($project, $request);
        
        if($return["success"]) {
            return redirect(route('project', $project->slug))->withSuccess('<i>'.$project->name.'</i>\'s nearby establishments essuccessfully edited.');
        } else {
            return redirect(route('project', $project->slug))->withDanger('<i>'.$project->name.'</i>\'s nearby establishments successfully edited.');
        }
    }

    /**
    * Show the form for editing the nearby establishments of the project
    *
    */
    public function showEditNearbyEstablishment(Project $project, NearbyEstablishment $nearby_establishment)
    {
        return view('developers.projects.edit.nearby_establishment', compact('project','nearby_establishment'));
    }

    /**
    * Show the form for editing the nearby establishments of the project
    *
    */
    public function editNearbyEstablishment(Project $project, NearbyEstablishment $nearby_establishment, EditProjectNearbyEstablishmentRequest $request)
    {
        $return = NearbyEstablishment::editNearbyEstablishment($nearby_establishment, $request);
        
        if($return["success"]) {
            return redirect(route('project', $project->slug))->withSuccess('<i>'.$project->name.'</i>\'s nearby establishment essuccessfully edited.');
        } else {
            return redirect(route('project', $project->slug))->withDanger('<i>'.$project->name.'</i>\'s nearby establishment successfully edited.');
        }
    }

    /**
    * Show the form for editing the sources of the project
    *
    */
    public function showEditSources(Project $project)
    {
        $electricity_source = ElectricitySource::getByProject($project);
        $water_source = WaterSource::getByProject($project);
        return view('developers.projects.edit.sources', compact('project','electricity_source','water_source'));
    }

    /**
    * Edit the sources of an existing project.
    *
    */
    public function editSources(Project $project, EditProjectSourcesRequest $request)
    {
        $electricity_return = ElectricitySource::editSource($project, $request);
        $water_return = WaterSource::editSource($project, $request);
        
        if($electricity_return["success"] and $water_return["success"]) {
            return redirect(route('project', $project->slug))->withSuccess('<i>'.$project->name.'</i>\'s sources was successfully edited.');
        } else {
            return redirect(route('project', $project->slug))->withDanger('<i>'.$project->name.'</i>\'s sources was unsuccessfully edited.');
        }
    }

    /**
    * Show the form for editing the subdivision plan of the project
    *
    */
    public function showEditSubdPlan(Project $project)
    {
        $subd_plan = SubdivisionPlan::getById($project);
        return view('developers.projects.edit.subdivision_plan', compact('project','subd_plan'));
    }

    /**
    * Update the subdivision plan of a project
    *
    */
    public function editSubdPlan(Project $project)
    {
        $return = SubdivisionPlan::uploadSubdPlan($project, Input::all());
        if($return["success"]) {
            return redirect(route('project', $project->slug))->withSuccess('<i>'.$project->name.'</i>\'s subdivision plan was successfully edited.');
        } else {
            return redirect(route('project_edit_subd_plan', $project->slug))->withDanger('<i>'.$project->name.'</i>\'s subdivision plan was unsuccessfully edited.');
        }
    }

    /**
    * Show the form for editing the vicinity map of the project
    *
    */
    public function showEditVicinityMap(Project $project)
    {
        $vicnity_map_project = VicinityMapProject::getById($project);
        return view('developers.projects.edit.vicinity_map', compact('project','vicnity_map_project'));
    }

    /**
    * Update the vicinity map of a project.
    *
    */
    public function editVicinityMap(Request $request, Project $project)
    {
        $return = VicinityMapProject::uploadVicinityMap($project, Input::all());
        if($return["success"]) {
            return redirect(route('project', $project->slug))->withSuccess('<i>'.$project->name.'</i>\'s vicinity map was successfully edited.');
        } else {
            return redirect(route('project_edit_vicinity_map', $project->slug))->withDanger('<i>'.$project->name.'</i>\'s vicinity map was unsuccessfully edited.');
        }
    }

    /**
    * Show the gallery for the project.
    *
    */
    public function showProjectGallery(Project $project)
    {
        $gallery = ProjectGallery::getAllById($project);
        return view('developers.projects.gallery.view', compact('project','gallery'));
    }

    /**
    * Show the gallery for the project.
    *
    */
    public function showUploadImages(Project $project)
    {
        return view('developers.projects.gallery.upload', compact('project'));
    }

    /**
    * Upload the images property.
    *
    */
    public function uploadImages(Project $project) {
        $return = ProjectGallery::uploadImages($project, Input::all());
        if($return["success"]) {
            return Response::json('success', 200);
        } else {
            return Response::json('error', 400);
        }
    }

    /**
    * Show the webpage for deleting the project's photos.
    *
    */
    public function showDeleteProjectImages(Project $project)
    {
        $gallery = ProjectGallery::getAllById($project);
        if(count($gallery) == 0) {
            return redirect(route('project_gallery', array($project->slug)))->withModal('No photos found for '.$property->name);      
        } else {
            return view('developers.projects.gallery.delete', compact('project','gallery'));
        }
    }

    /**
    * Delete the photos of a property.
    *
    */
    public function deleteProjectImages(Project $project, array $project_images) {
        foreach($project_images as $project_image) {
            $project_image->delete();
            File::delete(public_path().'/'.$project_image->image_path);
        }
        return redirect(route('project_gallery', array($project->slug)))->withSuccess('Photos for '.$project->name. ' were successfully deleted');    
    }

    /**
    * Delete a project and its details.
    *
    */
    public function deleteProject(Project $project, Request $request) {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = Project::deleteProject($project);
            if($return["success"]) {
                return 1;
            } else {
                return 2;
            }
            //return $return;
        } else {
            return 0;
        }
    }

    /**
    * Get security code from AJAX post and check whether the security code inputed is correct from the database,
    * and delete the object if true.
    *
    */
    public function deleteJointVenture(Project $project, JointVenture $joint_venture, Request $request) {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = JointVenture::deleteJointVenture($joint_venture);
            if($return["success"]) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        }
    }

    /**
    * Get security code from AJAX post and check whether the security code inputed is correct from the database,
    * and delete the object if true.
    *
    */
    public function deleteAmenity(Project $project, Amenity $amenity, Request $request) {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = Amenity::deleteAmenity($amenity);
            if($return["success"]) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        }
    }

    /**
    * Get security code from AJAX post and check whether the security code inputed is correct from the database,
    * and delete the object if true.
    *
    */
    public function deleteIncentive(Project $project, Incentive $incentive, Request $request) {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = Incentive::deleteIncentive($incentive);
            if($return["success"]) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        }
    }

    /**
    * Get security code from AJAX post and check whether the security code inputed is correct from the database,
    * and delete the object if true.
    *
    */
    public function deleteNearbyEstablishment(Project $project, NearbyEstablishment $nearby_establishment, Request $request) {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = NearbyEstablishment::deleteNearbyEstablishment($nearby_establishment);
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
