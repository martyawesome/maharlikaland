<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Agent;
use App\AboutMe;
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
use DB;

class ApiController extends Controller
{
    /**
     * Get the profile me of an agent.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAgentProfile(Request $request) 
    {
        $user_id = 19;
        //$user_id = $request->get('user_id')
        $user = User::whereId($user_id)->first();

        $response['status'] = 1;
        $response['results'] = User::join('agents','agents.id','=','users.agent_id')
        ->select(DB::raw('users.*, agents.prc_license_number as prc_license_number,
            agents.facebook_url as facebook_url,
            agents.twitter_url as twitter_url,
            agents.linkdin_url as linkdin_url'))
        ->where("users.id", $user_id)
        ->first();

        return $response;
    }

    /**
     * Get the about me of an agent.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAboutMe(Request $request) 
    {
        /*$user_id = $request->get('user_id');
        $user = User::whereId($user_id)->first();*/

        $response['status'] = 1;
        $response['results'] = AboutMe::select(DB::raw('about_me.header, about_me.content'))
        //->whereAgentId($user->agent_id)
        ->whereAgentId(5)
        ->first();

        return $response;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPropertiesOfAgent(Request $request)
    {
        /*if($request->get('device_id')) {
            $device = $this->device->whereDeviceId($request->get('device_id'))->first();
        }
        
        if($device == null and ($request->get('platform') and $request->get('device_id'))) {
            $device = new Device();
            $device->platform = $request->get('platform');
            $device->device_id = $request->get('device_id');
            $device->touch();
        }*/

        /*$user_id = $request->get('user_id');
        $user = User::whereId($user_id)->first();*/

        $response['status'] = 1;
        $response['results'] = Property::leftJoin('projects','projects.id','=','properties.project_id')
        ->leftJoin('property_locations','property_locations.property_id','=','properties.id')
        ->leftJoin('provinces','property_locations.province_id','=','provinces.id')
        ->leftJoin('cities_municipalities','property_locations.city_municipality_id','=','cities_municipalities.id')
        ->leftJoin('property_types','property_types.id','=','properties.property_type_id')
        ->leftJoin('floors','floors.id','=','properties.floor_id')
        ->leftJoin('number_of_bedrooms','number_of_bedrooms.id','=','properties.number_of_bedrooms_id')
        ->leftJoin('number_of_bathrooms','number_of_bathrooms.id','=','properties.number_of_bathrooms_id')
        ->leftJoin('property_statuses','property_statuses.id','=','properties.property_status_id')
        ->select(DB::raw('properties.name, properties.slug, properties.floor_area, properties.lot_area, properties.price,
         properties.price_per_sqm, properties.main_picture_path, provinces.name as province, cities_municipalities.name
         as city_municipality, property_locations.barangay, property_locations.street, property_locations.block_number,
         property_locations.lot_number, property_locations.unit_number, property_types.property_type as property_type,
         floors.floor as floors, number_of_bedrooms.bedrooms as number_of_bedrooms, number_of_bathrooms.bathrooms as number_of_bathrooms,
         property_statuses.property_status as property_status, projects.name as project_name'))
        //->whereRaw(DB::raw('properties.id = '.$property_id.' and properties.agent_id = '.$user->agent_id))
        ->whereRaw(DB::raw('properties.agent_id = 5'))
        ->first();

        return json_encode($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProperty(Request $request)
    {
        //$property_id = $request->get('property_id');
        $property_id = 129;
        
        $response['status'] = 1;
        $response['results'] = Property::leftJoin('projects','projects.id','=','properties.project_id')
        ->leftJoin('property_locations','property_locations.property_id','=','properties.id')
        ->leftJoin('provinces','property_locations.province_id','=','provinces.id')
        ->leftJoin('cities_municipalities','property_locations.city_municipality_id','=','cities_municipalities.id')
        ->leftJoin('property_types','property_types.id','=','properties.property_type_id')
        ->leftJoin('floors','floors.id','=','properties.floor_id')
        ->leftJoin('number_of_bedrooms','number_of_bedrooms.id','=','properties.number_of_bedrooms_id')
        ->leftJoin('number_of_bathrooms','number_of_bathrooms.id','=','properties.number_of_bathrooms_id')
        ->leftJoin('property_statuses','property_statuses.id','=','properties.property_status_id')
        ->select(DB::raw('properties.name, properties.slug, properties.floor_area, properties.lot_area, properties.price,
         properties.price_per_sqm, properties.main_picture_path, provinces.name as province, cities_municipalities.name
         as city_municipality, property_locations.barangay, property_locations.street, property_locations.block_number,
         property_locations.lot_number, property_locations.unit_number, property_types.property_type as property_type,
         floors.floor as floors, number_of_bedrooms.bedrooms as number_of_bedrooms, number_of_bathrooms.bathrooms as number_of_bathrooms,
         property_statuses.property_status as property_status, projects.name as project_name'))
        ->whereRaw(DB::raw('properties.id = '.$property_id))
        ->first();

        return json_encode($response);
    }


}
