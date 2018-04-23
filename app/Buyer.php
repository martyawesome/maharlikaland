<?php

namespace App;

use App\Http\Requests\AddEditBuyerRequest;
use Illuminate\Database\Eloquent\Model;

use App\Property;
use App\InstallmentAccountLedger;
use App\InstallmentAccountLedgerDetail;
use App\User;

use Auth;
use DB;
use Hash;

class Buyer extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'buyers';

    /**
    * Get all of the buyers according to the logged developer.
    *
    */
    public static function getByDeveloper()
    {
    	return Buyer::get();
    }

    /**
    * Get the buyer of a specific property.
    *
    */
    public static function getBuyerOfProperty(Property $property)
    {
    	return Buyer::whereId($property->buyer_id)->get();
    }

    /**
    * Get the list of buyers with no associated properties yet.
    *
    */
    public static function getBuyersWithoutProperties()
    {
    	if(Auth::user()->user_type_id == config('constants.AGENT_TYPE_DEVELOPER')) {
			return Buyer::leftJoin('properties','properties.buyer_id','=','buyers.id')
			->select('buyers.*')
			->whereRaw('properties.buyer_id IS NULL')
			->get();
		} else {
			return Buyer::leftJoin('properties','properties.buyer_id','=','buyers.id')
			->select('buyers.*')
			->whereRaw('properties.buyer_id IS NULL')
			->get();
		}
    }

    /**
    * Get the list of buyers to be including when adding or editing a property.
    *
    */
    public static function getBuyersForForm(Property $property)
    {
    	if(Auth::user()->user_type_id == config('constants.USER_TYPE_ADMIN') or
            Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER') or
            Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN') or
             Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY') or 
              Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ACCOUNTANT')) {
            return Buyer::leftJoin('properties','properties.buyer_id','=','buyers.id')
			->select( DB::raw("(concat(COALESCE(buyers.last_name,''),', ', COALESCE(buyers.first_name,''),' ',COALESCE(buyers.middle_name,''))) as full_name, buyers.id"))
			->lists('full_name', 'id');
		} else {
			return Buyer::leftJoin('properties','properties.buyer_id','=','buyers.id')
			->select( DB::raw("(concat(buyers.first_name,' ', buyers.middle_name,' ',buyers.last_name)) AS full_name, buyers.id"))
			->whereRaw('buyers.agent_id = '.Auth::user()->agent_id.' 
				and properties.buyer_id IS NULL or properties.buyer_id = '. $property->buyer_id)
			->lists('full_name', 'id');
		}
    }

    /**
    * Update a buyer's profile including a newly added buyer.
    *
    */
    public static function updateBuyer(Buyer $buyer, AddEditBuyerRequest $request)
    {
    	DB::beginTransaction();
        try {
            $buyer->first_name = $request->get('first_name');
        	$buyer->middle_name = $request->get('middle_name');
        	$buyer->last_name = $request->get('last_name');
            $buyer->sex = $request->get('sex');
        	$buyer->home_address = $request->get('home_address');
        	$buyer->contact_number_mobile = $request->get('contact_number_mobile');
        	$buyer->contact_number_home = $request->get('contact_number_home');
        	$buyer->contact_number_office = $request->get('contact_number_office');
        	$buyer->email = $request->get('email');
        	if($request->get('civil_status')){
        		$buyer->civil_status = $request->get('civil_status');
        	}
        	if($request->get('birthdate')){
        		$buyer->birthdate = $request->get('birthdate');
        	}
        	if($request->get('spouse_name')){
        		$buyer->spouse_name = $request->get('spouse_name');
        	}
        	if($request->get('num_of_children')){
        		$buyer->num_of_children = $request->get('num_of_children');
        	}
        	if($request->get('company_name')){
        		$buyer->company_name = $request->get('company_name');
        	}
        	if($request->get('position')){
        		$buyer->position = $request->get('position');
        	}
        	if($request->get('company_address')){
        		$buyer->company_address = $request->get('company_address');
        	}
        	$return["success"] = $buyer->touch();

            if($return["success"]){
                $user = User::whereBuyerId($buyer->id)->first();

                if(!$user) {
                    $user = new Users();
                    $user->buyer_id = $buyer->id;
                    $user->user_type_id = config('constants.USER_TYPE_BUYER');
                    $user->able_to_sell = false;
                }

                $user->first_name = $buyer->first_name;
                $user->middle_name = $buyer->middle_name;
                $user->last_name = $buyer->last_name;
                $user->email = $buyer->email;
                $user->contact_number = $buyer->contact_number_mobile;
                $user->sex = $buyer->sex;
                $user->address = $buyer->home_address;
                
                $return["success"] = $user->touch();
                if($return["success"]) {
                    DB::commit();
                } else {
                    DB::rollback();
                }
            } else {
                $return["success"] = false;
                DB::rollback();
            }
        	
        } catch(Exeption $e) {
            DB::rollback();
        }

        return $return;
    }

    /**
    * Delete a prospect buyer and his/her prospect properties.
    *
    */
    public static function deleteBuyer(Buyer $buyer)
    {
        DB::beginTransaction();

        $installment_account_ledgers = InstallmentAccountLedger::whereBuyerId($buyer->id)->get();

        $counter = 0;
        $installment_account_ledgers_counter = count($installment_account_ledgers);
        foreach($installment_account_ledgers as $installment_account_ledger){
            $return["success"] = InstallmentAccountLedgerDetail::whereInstallmentAccountLedgerId($installment_account_ledger->id)->delete();
            if($return["success"]) {
                $counter++;
            }
        }

        if($counter == $installment_account_ledgers_counter) {
            if($installment_account_ledgers_counter > 0) {
                $return["success"] = InstallmentAccountLedger::whereBuyerId($buyer->id)->delete();
            } else {
                $return["success"] = true;
            }
            
            if($return["success"]) {
                $return["success"] = Buyer::find($buyer->id)->delete();
                if($return["success"]) {
                    $properties = Property::getPropertiesOfBuyer($buyer);
                    $counter = 0;
                    $properties_count = count($properties);
                    foreach($properties as $property){
                        $property->buyer_id = NULL;
                        $property->property_status_id = config('constants.PROPERTY_STATUS_FOR_SALE');
                        if($property->touch()){
                            $counter++;
                        }
                    }
                    if($counter == $properties_count) {
                        DB::commit();
                    } else {
                        DB::rollback();
                    }
                } else {    
                    DB::rollback();
                }
            } else {
                DB::rollback();
            }
        } else {
            DB::rollback();
        }

        return $return;
    }

    /**
    * Save the buyers imported from an excel file. Also, create a user account for the newly created buyer.
    *
    */
    public static function importFromExcel($data)
    {
        DB::beginTransaction();

        $counter = true;
        $row_counter = 1;
        
        foreach($data as $datum) {
            if($datum->last_name != null and $datum->first_name != null) {
                $buyer = Buyer::getByWholeName($datum->first_name, $datum->middle_name, $datum->last_name);
                if(!$buyer and $datum->email)
                    $user = Buyer::whereEmail($datum->email)->first();

                if(!$buyer){
                    $buyer = new Buyer();

                    $buyer->first_name = ucwords(str_replace('Ñ', 'ñ', strtolower($datum->first_name)));
                    if($datum->middle_name)
                        $buyer->middle_name = ucwords(str_replace('Ñ', 'ñ', strtolower($datum->middle_name)));
                    $buyer->last_name = ucwords(str_replace('Ñ', 'ñ', strtolower($datum->last_name)));
                    $buyer->sex = $datum->sex;
                    $buyer->birthdate = $datum->birthdate;
                    $buyer->email = $datum->email;
                    $buyer->contact_number_mobile = $datum->contact_number;
                    $buyer->email = $datum->email;
                    $buyer->home_address = $datum->address;
                    $buyer->civil_status = $datum->civil_status;
                    $buyer->spouse_name = $datum->spouse_name;
                    $buyer->company_name = $datum->company_name;
                    $buyer->company_address = $datum->company_address;
                    $buyer->position = $datum->position;

                    if($buyer->touch()){
                        $user = User::whereBuyerId($buyer->id)->first();

                        if(!$user) {
                            $user = new User();
                            $id = User::orderBy('id','desc')->first()->id + 1;
                            $user->username = ucwords(trim(str_replace(' ', '', str_replace('Ñ', 'n', str_replace('ñ', 'n', strtolower($datum->last_name)))))).$id;
                            $user->password = Hash::make('12345');
                            $user->buyer_id = $buyer->id;
                            $user->user_type_id = config('constants.USER_TYPE_BUYER');
                            $user->able_to_sell = false;
                            $user->is_mobile_activated = true;
                            $user->profile_picture_path = 'img/defaults/icon-user-default.png';
                        }

                        $user->first_name = $buyer->first_name;
                        $user->middle_name = $buyer->middle_name;
                        $user->last_name = $buyer->last_name;
                        $user->email = $buyer->email;
                        $user->birthdate = $buyer->birthdate;
                        $user->contact_number = $buyer->contact_number_mobile;
                        $user->sex = $buyer->sex;
                        $user->address = $buyer->home_address;
                        
                        if($user->touch()) {
                            $counter = true;
                        } else {
                            $counter = false;
                            break;
                        }
                    } else {
                        $return["success"] = false;
                        DB::rollback();
                    }
                }
                
            } else {
                // Skip the headers
                if($row_counter > 1){
                    break;
                    DB::rollback();
                    $return['success'] = false;
                    $return['message'] = "Data missing in row ".$row_counter;
                }
            }
            $row_counter++;
        }

        if($counter){
            DB::commit();
            $return['success'] = true;
        } else {
            DB::rollback();
            $return['success'] = false;
            if(!$return['message']) {
                $return['message'] = "Users were unsuccessfully imported";
            }
        }

        return $return;
    }

    /**
    * Find user by whole name.
    *
    */
    public static function getByWholeName($first_name, $middle_name, $last_name)
    {
        return Buyer::whereRaw("first_name like '%".$first_name."%' and middle_name like '%".$middle_name."%' and last_name like '%".$last_name."%'")->first();
    }
}
