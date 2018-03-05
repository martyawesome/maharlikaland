<?php

namespace App\Http\Controllers\Agents;

use Illuminate\Http\Request;
use App\Http\Requests\AddPropertyRequest;
use App\Http\Requests\EditPropertyRequest;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

use Validator;
use Response;

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
use Auth;
use File;

class PropertiesController extends Controller
{
    /**
     * Show the form for creating a agent.
     *
     * @return \Illuminate\Http\Response
     */
    public function allProperties()
    {
        $properties = Property::getAll();
        return view('agents.properties.all', compact('properties'));
    }
   
    /**
     * Show the form for creating a agent.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAddProperty()
    {
        $property = new Property();
        $property->is_furnished = false;
        $property_location = new PropertyLocation();
        $property_types = PropertyType::lists('property_type', 'id');
        $property_statuses = PropertyStatus::lists('property_status', 'id');
        $number_of_bathrooms = NumberOfBathroom::lists('bathrooms', 'id');
        $number_of_bedrooms = NumberOfBedroom::lists('bedrooms', 'id');
        $parking_availability = ParkingAvailability::lists('parking_availability', 'id');
        $floors = Floor::lists('floor', 'id');
        $provinces = Province::get();
        $provinces_list = Province::lists('name','id');
        $cities_municipalities = CityMunicipality::get();
        $buyers = [];   
    	return view('agents.properties.add', compact('property','property_types','property_statuses','number_of_bathrooms',
            'number_of_bedrooms','parking_availability','property_location','floors','provinces','provinces_list',
            'cities_municipalities','buyers'));
    }

    public function addProperty(AddPropertyRequest $request)
    {
        $property = Property::saveProperty(null, $request);
        return redirect(route('agent_edit_property',array('property'=>$property->slug)))
        ->with('hasPropertySuccessfullyCreated',true)
        ->withSuccess('Property for <i>' . $property->name. '</i> was successfully created. Upload images now?');
    }

    public function viewProperty(Property $property)
    {
        $property = Property::getSingle($property);
        $floor_areas = FloorArea::getByProperty($property);
        return view('agents.properties.view', compact('property','floor_areas'));
    }

    public function showEditProperty(Property $property)
    {
        $property_location = PropertyLocation::wherePropertyId($property->id)->first();
        $floor_areas = FloorArea::wherePropertyId($property->id)->get();
        $property_types = PropertyType::lists('property_type', 'id');
        $property_statuses = PropertyStatus::lists('property_status', 'id');
        $number_of_bathrooms = NumberOfBathroom::lists('bathrooms', 'id');
        $number_of_bedrooms = NumberOfBedroom::lists('bedrooms', 'id');
        $parking_availability = ParkingAvailability::lists('parking_availability', 'id');
        $floors = Floor::lists('floor', 'id');
        $provinces = Province::get();
        $provinces_list = Province::lists('name','id');
        $cities_municipalities = CityMunicipality::get();
        return view('agents.properties.edit', compact('property','property_types','property_statuses','number_of_bathrooms',
            'number_of_bedrooms','parking_availability','property_location','floor_areas','floors','provinces','provinces_list',
            'cities_municipalities'));
    }

    public function editProperty(Property $property, AddPropertyRequest $request)
    {
        $new_property = Property::editProperty($property,$request);
        return redirect(route('agent_all_properties'))->withSuccess('Property for <i>' . $new_property->name. '</i> was successfully edited');
    }

    public function showUploadImages(Property $property) {
        $property_gallery = new PropertyGallery();
        return view('agents.properties.gallery.upload_photos', compact('property','property_gallery'));
    }

    public function showGallery(Property $property) {
        $gallery = PropertyGallery::wherePropertyId($property->id)->get();
        return view('agents.properties.gallery.gallery', compact('property','gallery'));
    }

    public function uploadImages(Property $property) {

        $input = Input::all();
 
        $rules = array(
            'file' => 'image',
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

    public function showChooseMainPhoto(Property $property) {
        $gallery = PropertyGallery::wherePropertyId($property->id)->get();
        return view('agents.properties.gallery.choose_main_photo', compact('property','gallery'));
    }

    public function chooseMainPhoto(Property $property, PropertyGallery $property_gallery) {
        $property->main_picture_path = $property_gallery->image_path;
        $property->touch();

        return redirect(route('property_gallery', $property->slug))->withSuccess('Main photo for '.$property->name. ' was successfully selected');    
    }

    public function showDeletePropertyPhotos(Property $property) {
        $gallery = PropertyGallery::wherePropertyId($property->id)->get();
        return view('agents.properties.gallery.delete_photos', compact('property','gallery'));
    }

    public function deletePropertyPhotos(Property $property, array $property_galleries) {
        foreach($property_galleries as $property_gallery) {
            $property_gallery->delete();
            File::delete(public_path().$property_gallery->image_path);
            if($property->main_picture_path == $property_gallery->image_path) {
                $property->main_picture_path = config('constants.PROPERTIES_DEFAULT_IMAGE_PATH');
                $property->touch();
            }
        }
        return redirect(route('property_gallery', $property->slug))->withSuccess('Photos for '.$property->name. ' was successfully deleted');    
    }

}
