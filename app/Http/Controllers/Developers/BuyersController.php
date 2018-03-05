<?php

namespace App\Http\Controllers\Developers;

use Illuminate\Http\Request;
use App\Http\Requests\AddEditBuyerRequest;
use App\Http\Requests\CreateUserRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Property;
use App\Developer;
use App\Buyer;
use App\User;
use App\DevelopersBuyers;

use Auth;
use Hash;
use Input;
use Excel;

class BuyersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAddBuyer()
    {
    	$buyer = new Buyer();
        return view('developers.buyers.add', compact('buyer','property','properties'));
    }

    /**
    * Add the new buyer
    *
    */
    public function addBuyer(AddEditBuyerRequest $request)
    {
    	$new_buyer = new Buyer();
    	$new_buyer->developer_id = Auth::user()->developer_id;
    	$return = Buyer::updateBuyer($new_buyer, $request);
        if($return["success"]) {
    	   return redirect(route('buyers'))->withSuccess('Buyer <i>'.$request->get('first_name').' '.$request->get('last_name').' </i> was successfully added');
        } else {
            return redirect(back())->withDanger('Buyer <i>'.$request->get('first_name').' '.$request->get('last_name').' </i> was unsuccessfully added');
        }
    }

    /**
    * Show all the buyers of the current developer.
    *
    */
    public function showBuyers()
    {
    	$buyers = Buyer::getByDeveloper();
        return view('developers.buyers.all', compact('buyers'));
    }

    /**
    * Show all the buyers of the current developer.
    *
    */
    public function showBuyer(Buyer $buyer)
    {
        $properties = Property::getPropertiesOfBuyer($buyer);
        $user = User::whereBuyerId($buyer->id)->first();
        return view('developers.buyers.view', compact('buyer','properties','user'));
    }

    /**
    * Create a user account for a buyer.
    *
    */
    public function showCreateBuyerUserAccount(Buyer $buyer)
    {
        $user = new User();
        $user->first_name = $buyer->first_name;
        $user->middle_name = $buyer->middle_name;
        $user->last_name = $buyer->last_name;
        $user->email = $buyer->email;
        $user->contact_number = $buyer->contact_number_mobile;
        $user->sex = $buyer->sex;
        $user->address = $buyer->home_address;
        return view('developers.buyers.create_user_account', compact('buyer','user'));
    }

    /**
    * Add the new buyer
    *
    */
    public function createBuyerUserAccount(Buyer $buyer, CreateUserRequest $request)
    {
        $user = new User();
        $user->first_name = $buyer->first_name;
        $user->middle_name = $buyer->middle_name;
        $user->last_name = $buyer->last_name;
        $user->email = $buyer->email;
        $user->contact_number = $buyer->contact_number_mobile;
        $user->sex = $buyer->sex;
        $user->address = $buyer->home_address;
        $user->buyer_id = $buyer->id;
        $user->able_to_sell = false;
        $user->user_type_id = config('constants.USER_TYPE_BUYER');
        $return = User::createUser($user, $request);

        $linkDevBuyer = DevelopersBuyers::linkBuyerToDeveloper($buyer);

        if($return["success"] and $linkDevBuyer["success"]) {
            return redirect(route('buyer', array($buyer->id)))->withSuccess('User account for <i>'.$user->first_name.' '.$user->last_name .' </i> was successfully created');
        } else {
            return redirect(route('create_buyer_user_account',array($buyer->id)))->withDanger('User account for <i>'.$user->first_name.' '.$user->last_name .' </i> was unsuccessfully created');
        }
    }

    /**
     * Show the form for editing a buyer. 
     *
     */
    public function showEditBuyer(Buyer $buyer)
    {
    	return view('developers.buyers.edit', compact('buyer'));
    }

    /**
    * Add the new buyer
    *
    */
    public function editBuyer(Buyer $buyer, AddEditBuyerRequest $request)
    {
    	$return = Buyer::updateBuyer($buyer, $request);
        if($return["success"]) {
           return redirect(route('buyers'))->withSuccess('Buyer <i>'.$buyer->first_name.' '.$buyer->last_name.' </i> was successfully edited');
        } else {
            return redirect(back())->withDanger('Buyer <i>'.$buyer->first_name.' '.$buyer->last_name.' </i> was unsuccessfully edited');
        }
    }

    /**
    * Delete a buyer and his/her prospect properties.
    *
    */
    public function deleteBuyer(Buyer $buyer, Request $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = Buyer::deleteBuyer($buyer);
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
    * Import the users from an Excel file and save the data to the database.
    *
    */
    public static function importFromExcel()
    {
        if(Input::hasFile('excel')){
            $path = Input::file('excel')->getRealPath();
            $data = Excel::selectSheetsByIndex(0)->load($path, function($reader) {
                $reader->formatDates(false);
            })->get();
            $return = Buyer::importFromExcel($data);

            if($return["success"]){
                return redirect(route('buyers'))->withSuccess('Buyers were successfully imported');
            } else {
                return redirect(route('buyers'))->withDanger($return['message']);
            }
        } else {
            return redirect(route('buyers'))->withDanger('No file selected');
        }
    }
}
