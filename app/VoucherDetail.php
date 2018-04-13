<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Http\Requests\AddEditVoucherDetailRequest;

use App\Voucher;
use Auth;

use DB;

class VoucherDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'voucher_details';

    /**
    * Get the total amount of voucher's details.
    *
    */
    public static function getTotalAmount(Voucher $voucher)
    {
    	return VoucherDetail::whereVoucherId($voucher->id)->sum('amount');
    }

    /**
    * Get the voucher details of a voucher.
    *
    */
    public static function getAll(Voucher $voucher)
    {	
    	return VoucherDetail::leftJoin('account_titles','account_titles.id','=','voucher_details.account_title_id')
    	->leftJoin('properties','properties.id','=','voucher_details.property_id')
    	->select(DB::raw('voucher_details.*, account_titles.title as account_title, properties.name as property'))
    	->whereRaw(DB::raw('voucher_details.voucher_id = '.$voucher->id))
    	->get();
    }

    /**
    * Update a voucher detail.
    *
    */
    public static function updateVoucherDetail(Voucher $voucher, VoucherDetail $voucher_detail, AddEditVoucherDetailRequest $request)
    {
    	DB::beginTransaction();

    	$voucher_detail->voucher_id = $voucher->id;
    	$voucher_detail->account_title_id = $request->get('account_title');
    	$voucher_detail->property_id = $request->get('property');
    	$voucher_detail->amount = str_replace(',','',$request->get('amount'));
    	$voucher_detail->remarks = $request->get('remarks');


    	if($voucher_detail->touch()) {
    		$return["success"] = true;
    		DB::commit();
    	} else {
    		$return["success"] = false;
    		DB::rollback();
    	}

    	return $return;
    }

    /**
    * Delete voucher detail.
    *
    */
    public static function deleteVoucherDetail(VoucherDetail $voucher_detail) 
    {
    	DB::beginTransaction();

    	$voucher_detail_deleted = VoucherDetail::whereId($voucher_detail->id)->delete();

    	if($voucher_detail_deleted) {
    		$return["success"] = true;
    		DB::commit();
    	} else {
    		$return["success"] = false;
    		DB::rollback();
    	}

    	return $return;
    }

    /**
    * Retrieve the last 10 vouchers from the database.
    *
    */
    public static function getLastTenVouchersDetails()
    {
        return VoucherDetail::leftJoin('vouchers','vouchers.id','=','voucher_details.voucher_id')
        ->leftJoin('properties','properties.id','=','voucher_details.property_id')
        ->leftJoin('projects','projects.id','=','properties.project_id')
        ->leftJoin('account_titles','account_titles.id','=','voucher_details.account_title_id')
        ->selectRaw('properties.name as property, vouchers.*, voucher_details.*, account_titles.title as account_title, projects.slug as project')
        ->orderBy('voucher_details.updated_at','desc')
        ->take(10)
        ->get();
    }

}
