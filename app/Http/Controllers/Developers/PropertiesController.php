<?php

namespace App\Http\Controllers\Developers;

use Illuminate\Http\Request;
use App\Http\Requests\AddPropertyRequest;
use App\Http\Requests\EditPropertyRequest;
use App\Http\Requests\SplitPropertyRequest;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

use Validator;
use Response;

use App\Developer;
use App\User;
use App\Agent;
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
use App\Project;
use App\Buyer;
use App\JointVenture;
use App\InstallmentAccountLedger;
use Auth;
use File;
use Hash;

class PropertiesController extends Controller
{

    /**
    * View a property.
    *
    */
    public function viewProperty(Project $project, Property $property)
    {
        $developer = Developer::getCurrentDeveloper();
        $property = Property::getSingle($property);
        $floor_areas = FloorArea::getByProperty($property);
        $has_ledger = InstallmentAccountLedger::getLedgerOfProperty($property) != null ? true : false;
        $property_location = PropertyLocation::getPropertyLocationOfProperty($property);

        return view('developers.properties.view', compact('project','property','floor_areas','has_ledger','property_location'));
    }

    /**
    * Show the form for editing a property.
    *
    */
    public function showEditProperty(Project $project, Property $property)
    {
        $developer = Developer::find(Auth::user()->developer_id);
        $property_location = PropertyLocation::wherePropertyId($property->id)->first();
        $floor_areas = FloorArea::getByProperty($property);
        $property_types = PropertyType::lists('property_type', 'id');
        $property_statuses = PropertyStatus::lists('property_status', 'id');
        $number_of_bathrooms = NumberOfBathroom::lists('bathrooms', 'id');
        $number_of_bedrooms = NumberOfBedroom::lists('bedrooms', 'id');
        $parking_availability = ParkingAvailability::lists('parking_availability', 'id');
        $floors = Floor::lists('floor', 'id');
        $provinces = Province::get();
        $provinces_list = Province::lists('name','id');
        $cities_municipalities = CityMunicipality::get();
        $buyers = Buyer::getBuyersForForm($property);
        $buyers->prepend('None', 0);
        $agents = User::getAllForForm();
        $agents->prepend('None', 0);
        $joint_ventures = JointVenture::getListForProject($project);
        $joint_ventures->prepend('None', 0);
        return view('developers.properties.edit', compact('project','property','property_types','property_statuses','number_of_bathrooms',
            'number_of_bedrooms','parking_availability','property_location','floor_areas','floors','provinces','provinces_list',
            'cities_municipalities','buyers','joint_ventures','agents'));
    }

    /**
    * Edit a property.
    *
    */
    public function editProperty(Project $project, Property $property, AddPropertyRequest $request)
    {
        $return = Property::editProperty($property,$request);

        if($return["success"]) {
            return redirect(route('property',array($project->slug,$property->slug)))->withSuccess('Property for <i>' . $request->get('name'). '</i> was successfully edited');
        } else {
            return redirect(route('property',array($project->slug,$property->slug)))->withDanger('Property for <i>' . $request->get('name'). '</i> was unsuccessfully edited');
        }
    }

    /**
    * Show the gallery of a property.
    *
    */
    public function showGallery(Project $project, Property $property) {
        $gallery = PropertyGallery::getByProperty($property);
        return view('developers.properties.gallery.gallery', compact('project','property','gallery'));
    }

    /**
    * Show the form for uploading the photos of a property.
    *
    */
    public function showUploadImages(Project $project, Property $property) {
        return view('developers.properties.gallery.upload_photos', compact('project','property','property_gallery'));
    }

    
    /**
    * Upload the images property.
    *
    */
    public function uploadImages(Project $project, Property $property) {

        $input = Input::all();
 
        $rules = array(
            'file' => 'image'
        );
 
        $validation = Validator::make($input, $rules);
 
        if ($validation->fails()) {
            return Response::make($validation->errors->first(), 400);
        }
 
        $destinationPath = public_path() .'/'. config('constants.PROPERTIES_IMAGES_PATH').$property->slug.'/'; // upload path
        $extension = Input::file('file')->getClientOriginalExtension(); // getting file extension
        $fileName = rand(11111, 99999) . '.' . $extension; // re-nameing image
        $upload_success = Input::file('file')->move($destinationPath, $fileName); // uploading file to given path
 
        if($property->main_picture_path == config('constants.PROPERTIES_DEFAULT_IMAGE_PATH')) {
            $property->main_picture_path = config('constants.PROPERTIES_IMAGES_PATH').$property->slug.'/'.$fileName;
            $property->touch();
        }

        if ($upload_success) {
            $property_gallery = new PropertyGallery();
            $property_gallery->property_id = $property->id;
            $property_gallery->image_path = config('constants.PROPERTIES_IMAGES_PATH').$property->slug.'/'.$fileName;
            $property_gallery->touch();
            return Response::json('success', 200);
        } else {
            return Response::json('error', 400);
        }
    }

    /**
    * Show the form for choosing the main photo of a property.
    *
    */
    public function showChooseMainPhoto(Project $project, Property $property) {
        $gallery = PropertyGallery::wherePropertyId($property->id)->get();
        if(count($gallery) == 0) {
            return redirect(route('property_gallery', array($project->slug,$property->slug)))->withModal('No photos found for '.$property->name);    
        } else {
            return view('developers.properties.gallery.main_photo', compact('project','property','gallery'));
        }
    }

    /**
    * Choose the main photo of a property.
    *
    */
    public function chooseMainPhoto(Project $project, Property $property, PropertyGallery $property_gallery) {
        $property->main_picture_path = $property_gallery->image_path;
        $property->touch();

        return redirect(route('property_gallery', array($project->slug,$property->slug)))->withSuccess('Main photo for '.$property->name. ' was successfully selected');    
    }

    /**
    * Show the form for deleting the photos of a property.
    *
    */
    public function showDeletePropertyPhotos(Project $project, Property $property) {
        $gallery = PropertyGallery::wherePropertyId($property->id)->get();
        if(count($gallery) == 0) {
            return redirect(route('property_gallery', array($project->slug,$property->slug)))->withModal('No photos found for '.$property->name);      
        } else {
            return view('developers.properties.gallery.delete_photos', compact('project','property','gallery'));
        }
    }

    /**
    * Delete the photos of a property.
    *
    */
    public function deletePropertyPhotos(Project $project, Property $property, array $property_galleries) {
        foreach($property_galleries as $property_gallery) {
            $property_gallery->delete();
            File::delete(public_path().'/'.$property_gallery->image_path);
            if($property->main_picture_path == $property_gallery->image_path) {
                $property->main_picture_path = config('constants.PROPERTIES_DEFAULT_IMAGE_PATH');
                $property->touch();
            }
        }
        return redirect(route('property_gallery', array($project->slug,$property->slug)))->withSuccess('Photos for '.$property->name. ' was successfully deleted');    
    }

    /**
    * Split a lot.
    *
    */
    public function showSplitProperty(Project $project, Property $property)
    {
        $property_location = PropertyLocation::getPropertyLocationOfProperty($property);
        return view('developers.properties.split', compact('project','property','property_location'));
    }

    /**
    * Split a lot.
    *
    */
    public function splitPropertyValidateSecurityCode(Project $project, Property $property, Request $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'], $developer->security_code)) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
    * Split a lot.
    *
    */
    public function splitProperty(Project $project, Property $property, SplitPropertyRequest $request)
    {
        $property_location = PropertyLocation::getPropertyLocationOfProperty($property);
        $return = Property::splitProperty($project, $property, $request);
        
        if($return["success"]) {
            return redirect(route('property', array($project->slug, $property->slug)))->withSuccess('<i> Block '.$property_location->block_number.' Lot '.$property_location->lot_number.' was successfully split');
        } else {
            return redirect(route('property_show_split', array($project->slug, $property->slug)))->withDanger('<i> Block '.$property_location->block_number.' Lot '.$property_location->lot_number.' was unsuccessfully split');
        }
    }

    /**
    * Get security code from AJAX post and check whether the security code inputed is correct from the database,
    * and delete the object if true.
    *
    */
    public function deleteProperty(Project $project, Property $property, Request $request) {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = Property::deleteProperty($property);
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
