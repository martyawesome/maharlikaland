<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\InstallmentAccountLedger;
use App\InstallmentAccountLedgerDetail;
use DB;

class PaymentType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_types';

    /**
    * Get the possible payment types that the buyer do.
    *
    */
    public static function getCurrentPayment(InstallmentAccountLedger $ledger)
    {
        $total_reservation_fee = InstallmentAccountLedgerDetail::whereRaw('installment_account_ledger_id = '
            .$ledger->id.' and payment_type_id = '.config('constants.PAYMENT_TYPE_RESERVATION_FEE'))->sum('amount_paid');

        $total_downpayment = InstallmentAccountLedgerDetail::whereRaw('installment_account_ledger_id = '
            .$ledger->id.' and payment_type_id = '.config('constants.PAYMENT_TYPE_DOWNPAYMENT'))->sum('amount_paid');

        $total_bank_finance = InstallmentAccountLedgerDetail::whereRaw('installment_account_ledger_id = '
            .$ledger->id.' and payment_type_id = '.config('constants.PAYMENT_TYPE_BANK_FINANCE_PAYMENT'))->sum('amount_paid');

        $balance = InstallmentAccountLedgerDetail::getLastBalance($ledger);  

        if($ledger->bank_finance) {
            if($total_reservation_fee < $ledger->reservation_fee and $total_downpayment < 
                $ledger->dp - $ledger->reservation_fee - $ledger->dp_discount){
                return config('constants.PAYMENT_TYPE_RESERVATION_FEE');
            }
            else if($total_downpayment + $total_reservation_fee < $ledger->dp - $ledger->dp_discount) {
                return config('constants.PAYMENT_TYPE_DOWNPAYMENT');
            } else {
                return config('constants.PAYMENT_TYPE_BANK_FINANCE_PAYMENT');
            }
        } else {
            // Buyer started to pay the monthly amorization and is not equal to the balance
            if($balance > 0 and $total_downpayment + $total_reservation_fee >= $ledger->dp){
                return config('constants.PAYMENT_TYPE_MA'); 
            }
            else if(round($balance,2) == 0){
                return config('constants.PAYMENT_TYPE_FULL_PAYMENT'); 
            }
            // Buyer has not yet paid the reservation fee
            else if($total_reservation_fee < $ledger->reservation_fee) {
                // Buyer is not yet paying for the MA and the DP and reservation fee is less than the required
                if(($total_downpayment + $total_reservation_fee) < $ledger->dp - $ledger->dp_discount) {
                    return config('constants.PAYMENT_TYPE_DOWNPAYMENT');
                } else {
                    return config('constants.PAYMENT_TYPE_RESERVATION_FEE');
                }
            } else {
                return null;
            }
        }
    }

    /**
    * Get the possible payment types that the buyer do.
    *
    */
    public static function getPossiblePaymentTypes(InstallmentAccountLedger $ledger)
    {
        $remaning_penalty = round(InstallmentAccountLedgerDetail::getRemainingPenalty($ledger),2);
    	if(PaymentType::getCurrentPayment($ledger) == config('constants.PAYMENT_TYPE_RESERVATION_FEE') or 
            PaymentType::getCurrentPayment($ledger) == config('constants.PAYMENT_TYPE_DOWNPAYMENT')) {
             return PaymentType::whereRaw(DB::raw('id = '.config('constants.PAYMENT_TYPE_RESERVATION_FEE').' or id = '.config('constants.PAYMENT_TYPE_DOWNPAYMENT') ))->lists('payment_type','id');
    	} 
    	// Buyer has not payed all the MA
    	else if(PaymentType::getCurrentPayment($ledger) == config('constants.PAYMENT_TYPE_MA')) {
    		if($remaning_penalty > 0){
                return PaymentType::whereRaw('id = '.config('constants.PAYMENT_TYPE_MA').' or id = '.config('constants.PAYMENT_TYPE_PENALTY_PAYMENT')  
                    .' or id = '.config('constants.PAYMENT_TYPE_PENALTY_FEE').' or id = '.config('constants.PAYMENT_TYPE_FULL_PAYMENT'))
                ->lists('payment_type','id');  
            } else {
                return PaymentType::whereRaw('id = '.config('constants.PAYMENT_TYPE_MA') 
                    .' or id = '.config('constants.PAYMENT_TYPE_PENALTY_FEE').' or id = '.config('constants.PAYMENT_TYPE_FULL_PAYMENT'))
                ->lists('payment_type','id');  
            }
    	} 
        // Buyer has applied for a bank loan
        else if(PaymentType::getCurrentPayment($ledger) == config('constants.PAYMENT_TYPE_BANK_FINANCE_PAYMENT')) {
            if($remaning_penalty > 0){
                return PaymentType::whereRaw('id = '.config('constants.PAYMENT_TYPE_BANK_FINANCE_PAYMENT').' or id = '.config('constants.PAYMENT_TYPE_PENALTY_PAYMENT')  
                    .' or id = '.config('constants.PAYMENT_TYPE_PENALTY_FEE'))
                ->lists('payment_type','id');   
            } else {
                return PaymentType::whereRaw('id = '.config('constants.PAYMENT_TYPE_BANK_FINANCE_PAYMENT').' or id = '.config('constants.PAYMENT_TYPE_PENALTY_FEE')
                    .' or id = '.config('constants.PAYMENT_TYPE_FULL_PAYMENT'))
                ->lists('payment_type','id');   
            }
        } 
        else {
            if(InstallmentAccountLedgerDetail::getRemainingPenalty($ledger) > 0) {
                return PaymentType::whereRaw('id = '.config('constants.PAYMENT_TYPE_PENALTY_PAYMENT'))->lists('payment_type','id');  
            } else {
                return null;
            }
        }
    }
}
