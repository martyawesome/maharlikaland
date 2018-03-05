<?php

namespace App\Http\Controllers\Developers;

use Illuminate\Http\Request;
use App\Http\Requests\AddEditProspectBuyerRequest;
use App\Http\Requests\AddEditBuyerRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ProspectProperty;
use App\Property;
use App\ProspectBuyer;
use App\Buyer;
use App\Developer;
use Auth;
use Hash;

class ProspectBuyersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAddProspectBuyer()
    {
        $prospect_buyer = new ProspectBuyer();
        $prospect_property = new ProspectProperty();
        $properties = Property::getForProspectBuyers($prospect_buyer);
        return view('developers.prospect_buyers.add', compact('prospect_buyer','prospect_property','properties'));
    }

    /**
    * Add the new buyer
    *
    */
    public function addProspectBuyer(AddEditProspectBuyerRequest $request)
    {
        $return = ProspectBuyer::updateProspectBuyer(new ProspectBuyer(), $request);
        if($return["success"]) {
            return redirect(route('prospect_buyers'))->withSuccess('Prospect Buyer <i>'.$return["object"]->first_name.' '.$return["object"]->last_name.' </i> was successfully added');
        } else {
            return redirect(route('prospect_buyers'))->withDanger('Prospect Buyer <i>'.$request->get('first_name').' '.$request->get('last_name').' </i> was unsuccessfully added');
        }
    }

    /**
    * Show all the buyers of the current developer.
    *
    */
    public function showProspectBuyers()
    {
        $prospect_buyers = ProspectBuyer::getByDeveloper();
        return view('developers.prospect_buyers.all', compact('prospect_buyers'));
    }

    /**
    * Show all the buyers of the current developer.
    *
    */
    public function showProspectBuyer(ProspectBuyer $prospect_buyer)
    {
        $prospect_property = ProspectProperty::getForProspectBuyer($prospect_buyer);
        return view('developers.prospect_buyers.view', compact('prospect_buyer','prospect_property'));
    }

    /**
     * Show the form for editing a buyer. 
     *
     */
    public function showEditProspectBuyer(ProspectBuyer $prospect_buyer)
    {
        $prospect_property = ProspectProperty::getForProspectBuyer($prospect_buyer);
        $properties = Property::getForProspectBuyers($prospect_buyer);
        return view('developers.prospect_buyers.edit', compact('prospect_buyer','prospect_property','properties'));
    }

    /**
    * Add the new buyer.
    *
    */
    public function editProspectBuyer(ProspectBuyer $prospect_buyer, AddEditProspectBuyerRequest $request)
    {
        $return = ProspectBuyer::updateProspectBuyer($prospect_buyer, $request);
        if($return["success"]) {
            return redirect(route('prospect_buyer', array($prospect_buyer->id)))->withSuccess('Prospect Buyer <i>'.$return["object"]->first_name.' '.$return["object"]->last_name.' </i> was successfully added');
        } else {
            return redirect(route('prospect_buyer', array($prospect_buyer->id)))->withDanger('Prospect Buyer <i>'.$request->get('first_name').' '.$request->get('last_name').' </i> was unsuccessfully added');
        }
    }

    /**
    * Upgrade a prospect buyer to a buyer; meaning he/she will surely buy the property.
    *
    *
    */
    public function showUpgradeProspectBuyer(ProspectBuyer $buyer)
    {
        $buyer->contact_number_mobile = $buyer->contact_number;
        $buyer->home_address = $buyer->address;

        $property = Property::getForProspectBuyer($buyer);
        $properties = Property::getForProspectBuyers($buyer);
        return view('developers.buyers.add', compact('buyer','property','properties'));
    }

    /**
    * Add the new buyer
    *
    */
    public function upgradeProspectBuyer(ProspectBuyer $prospect_buyer, AddEditBuyerRequest $request)
    {
        $prospect_property = ProspectProperty::getForProspectBuyer($prospect_buyer);
        
        $new_buyer = new Buyer();
        $new_buyer->developer_id = Auth::user()->developer_id;
        $return = Buyer::updateBuyer($new_buyer, $request);

        if($return["success"] and $prospect_buyer->delete() and $prospect_property->delete()) {
            return redirect(route('buyers'))->withSuccess('Buyer <i>'.$request->get('first_name').' '.$request->get('last_name').' </i> was successfully added');
        } else {
            return redirect(route('buyers'))->withDanger('Buyer <i>'.$request->get('first_name').' '.$request->get('last_name').' </i> was unsuccessfully added');
        }
    }

    /**
    * Delete a prospect buyer and his/her prospect properties.
    *
    */
    public function deleteProspectBuyer(ProspectBuyer $prospect_buyer, Request $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = ProspectBuyer::deleteProspectBuyer($prospect_buyer);
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
