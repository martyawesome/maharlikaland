<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\PaymentType;
use App\InstallmentAccountLedgerDetail;

use DB;

class PropertyStatus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'property_statuses';

    public static function updatePropertyStatus(Property $property, InstallmentAccountLedger $ledger)
    {
    	DB::beginTransaction();

    	// Buyer has not payed the required DP
    	if(PaymentType::getCurrentPayment($ledger) == config('constants.PAYMENT_TYPE_DOWNPAYMENT')) {
    		$total_downpayment = InstallmentAccountLedgerDetail::whereRaw('installment_account_ledger_id = '
            .$ledger->id.' and payment_type_id = '.config('constants.PAYMENT_TYPE_MA'))->sum('amount_paid');
            
            // Just got reserved
            if($total_downpayment == 0) {
            	$property->property_status_id = config('constants.PROPERTY_STATUS_RESERVED');
            } 
            // Started to pay for DP
            else {
            	$property->property_status_id = config('constants.PROPERTY_STATUS_SOLD_ONGOING_DP');
            }
            $prospect_properties_deleted = true;
    	}
    	// Buyer has not payed all the MA
    	else if(PaymentType::getCurrentPayment($ledger) == config('constants.PAYMENT_TYPE_MA') or
    		round(InstallmentAccountLedgerDetail::getRemainingPenalty($ledger),2) > 0) {
            $property->property_status_id = config('constants.PROPERTY_STATUS_SOLD_ONGOING_MA');
            $prospect_properties_deleted = true;
    	} 
    	// Else, assume the property has been fully paid
    	else {
            $property->property_status_id = config('constants.PROPERTY_STATUS_FULLY_PAID');
            $prospect_properties_deleted = ProspectProperty::wherePropertyId($property->id)->delete();
    	}

    	$return["success"] = $property->touch();

    	if($return["success"] and $prospect_properties_deleted) {
    		DB::commit();
    	} else {
    		DB::rollback();
    	}

    	return $return;
    }
}
