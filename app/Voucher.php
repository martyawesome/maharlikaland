<?php

namespace App;

use App\Http\Requests\AddEditVoucherRequest;

use Illuminate\Database\Eloquent\Model;

use DB;

class Voucher extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vouchers';

    /**
    * Retrieve all vouchers.
    *
    */
    //public static function getAll(Project $project)
    public static function getAll()
    {
    	return Voucher::leftJoin(DB::raw('(select voucher_id, sum(amount) as details_amount from voucher_details group by voucher_id) as v'),'v.voucher_id','=','vouchers.id')->get();
    }

    /**
	* Update a voucher.
	*
	*/
	public static function updateVoucher(Voucher $voucher, AddEditVoucherRequest $request)
	{
		DB::beginTransaction();

		$voucher->date = $request->get('date');
		$voucher->voucher_number = $request->get('voucher_number');
		$voucher->payee = $request->get('payee');
		$voucher->issued_by = $request->get('issued_by');
		$voucher->received_by = $request->get('received_by');

		if($voucher->touch()) {
			$return["success"] = true;
			DB::commit();
		} else {
			$return["success"] = false;
			DB::rollback();
		}

		return $return;
	}

	/**
	* Delete a project's voucher and voucher details.
	*
	*/
	public static function deleteVoucher(Voucher $voucher)
	{
		DB::beginTransaction();

		$voucher_details_deleted = VoucherDetail::whereVoucherId($voucher->id)->delete();
		$voucher_deleted = Voucher::whereId($voucher->id)->delete();

		if($voucher_details_deleted and $voucher_deleted) {
			$return["success"] = true;
			DB::commit();
		} else {
			$return["success"] = false;
			DB::rollback();
		}

		return $return;
	}

}
  