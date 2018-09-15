<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\AddEditInstallmentAccountLedgerDetailRequest;

use App\Buyer;
use App\InstallmentAccountLedger;
use App\InstallmentAccountLedgerDetail;

use Auth;
use DB;

class InstallmentAccountLedgerDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'installment_account_ledger_details';

    /**
    * Get the ledger details of a buyer.
    *
    */
    public static function getLedgerDetailsOfBuyer(InstallmentAccountLedger $ledger)
    {
        return InstallmentAccountLedgerDetail::whereInstallmentAccountLedgerId($ledger->id)->get();
    }

    /**
    * Get the ledger details of a buyer.
    *
    */
    public static function getLedgerDetailsOfBuyerForExcel(InstallmentAccountLedger $ledger)
    {
        return InstallmentAccountLedgerDetail::select('payment_date','details_of_payment','or_no',
            'amount_paid','ma_covered_date','interest','principal','balance','penalty','remarks')
        ->whereInstallmentAccountLedgerId($ledger->id)->get();
    }


    /**
    * Get the ledger details of a buyer.
    *
    */
    public static function getMonthAmoritizationCount(InstallmentAccountLedger $ledger)
    {
        $ledger_details = InstallmentAccountLedgerDetail::select(DB::raw('sum(amount_paid) as amount_paid, details_of_payment'))
        ->whereInstallmentAccountLedgerId($ledger->id)
        ->wherePaymentTypeId(config('constants.PAYMENT_TYPE_MA'))
        ->whereRaw('amount_paid >= '.$ledger->mo_amortization)
        ->groupBy(DB::raw('details_of_payment'))
        ->orderBy('id')
        ->get();

        $ma_counter = 0;
        foreach($ledger_details as $ledger_detail) {
            if($ledger_detail->amount_paid >= $ledger->mo_amortization) {
                $ma_counter++;
            }
        }

        return $ma_counter;
    }

    /**
    * Get the ledger details of a buyer.
    *
    */
    public static function getMonthBankFinanceCount(InstallmentAccountLedger $ledger)
    {
        $ledger_details = InstallmentAccountLedgerDetail::select(DB::raw('sum(amount_paid) as amount_paid, details_of_payment'))
        ->whereInstallmentAccountLedgerId($ledger->id)
        ->wherePaymentTypeId(config('constants.PAYMENT_TYPE_BANK_FINANCE_PAYMENT'))
        ->whereRaw('amount_paid >= '.$ledger->bank_finance_monthly)
        ->groupBy(DB::raw('details_of_payment'))
        ->orderBy('id')
        ->get();

        $ma_counter = 0;
        foreach($ledger_details as $ledger_detail) {
            if($ledger_detail->amount_paid >= $ledger->bank_finance_monthly) {
                $ma_counter++;
            }
        }

        return $ma_counter;
    }

    /**
    * Add a ledger entry for a property.
    *
    */
    public static function addLedgerDetail(InstallmentAccountLedger $ledger, InstallmentAccountLedgerDetail $ledger_detail, AddEditInstallmentAccountLedgerDetailRequest $request)
    {
        return InstallmentAccountLedgerDetail::updateLedgerDetailsOfBuyer($ledger, $ledger_detail, $request, InstallmentAccountLedgerDetail::getLastBalance($ledger));
    }

    /**
    * Edit a ledger entry for a property.
    *
    */
    public static function editLedgerDetail(InstallmentAccountLedger $ledger, InstallmentAccountLedgerDetail $ledger_detail, AddEditInstallmentAccountLedgerDetailRequest $request)
    {
        $return = InstallmentAccountLedgerDetail::updateLedgerDetailsOfBuyer($ledger, $ledger_detail, $request, InstallmentAccountLedgerDetail::getBalanceOfPreviousEntry($ledger, $ledger_detail));
        
        DB::beginTransaction();
        if($return["success"]){
            /*
                Updating the next ledger entries after the current entry being edited.
            */
            $succeeding_ledger_details = InstallmentAccountLedgerDetail::getSucceedingEntries($ledger, $return["object"]);
            $counter = 0;
            for($i=0;$i<count($succeeding_ledger_details);$i++) {
                if($i == 0) {
                    if($succeeding_ledger_details[$i]->payment_type_id == config('constants.PAYMENT_TYPE_MA')){
                        $succeeding_ledger_details[$i]->interest = $return["object"]->balance * ($ledger->mo_interest/100);
                        $succeeding_ledger_details[$i]->principal = $succeeding_ledger_details[$i]->amount_paid - $succeeding_ledger_details[$i]->interest;
                        $succeeding_ledger_details[$i]->balance = $return["object"]->balance - $succeeding_ledger_details[$i]->principal;
                    } else if($request->get('payment_type') == config('constants.PAYMENT_TYPE_PENALTY_FEE')) {
                        if($succeeding_ledger_details[$i]->interest > 0) {
                            $succeeding_ledger_details[$i]->interest = ($ledger->mo_interest/100) * $return["object"]->balance;
                            $succeeding_ledger_details[$i]->principal = $succeeding_ledger_details[$i]->amount_paid - $succeeding_ledger_details[$i]->interest;
                            $succeeding_ledger_details[$i]->balance = $return["object"]->balance - $succeeding_ledger_details[$i]->principal;
                        }
                    }  
                } else {
                    if($succeeding_ledger_details[$i]->payment_type_id == config('constants.PAYMENT_TYPE_MA')){
                        $succeeding_ledger_details[$i]->interest = $succeeding_ledger_details[$i-1]->balance * ($ledger->mo_interest/100);
                        $succeeding_ledger_details[$i]->principal = $succeeding_ledger_details[$i]->amount_paid - $succeeding_ledger_details[$i]->interest;
                        $succeeding_ledger_details[$i]->balance = $succeeding_ledger_details[$i-1]->balance - $succeeding_ledger_details[$i]->principal;
                    } else if($succeeding_ledger_details[$i]->payment_type_id == config('constants.PAYMENT_TYPE_PENALTY_FEE')) {
                        if($succeeding_ledger_details[$i]->interest > 0) {
                            $succeeding_ledger_details[$i]->interest = ($ledger->mo_interest/100) * $succeeding_ledger_details[$i-1]->balance;
                            $succeeding_ledger_details[$i]->principal = $succeeding_ledger_details[$i]->amount_paid - $succeeding_ledger_details[$i]->interest;
                            $succeeding_ledger_details[$i]->balance = $succeeding_ledger_details[$i-1]->balance - $succeeding_ledger_details[$i]->principal;
                        } else {
                            $succeeding_ledger_details[$i]->balance = $succeeding_ledger_details[$i-1]->balance;
                        }
                    }  
                }
                if($succeeding_ledger_details[$i]->touch()){
                    $counter++;
                }
            }

            if($counter == count($succeeding_ledger_details)) {
                $property = Property::find($ledger->property_id);
                $return["success"] = PropertyStatus::updatePropertyStatus($property, $ledger);
                if($return["success"]) {
                    DB::commit();
                } else {
                    DB::rollback();
                }
            } else {
                DB::rollback();
                $return["success"] = false;
            }
        } 
        return $return;
    }

    /**
    * Update the ledger details of a buyer.
    *
    */
    public static function updateLedgerDetailsOfBuyer(InstallmentAccountLedger $ledger, 
    	InstallmentAccountLedgerDetail $ledger_detail, AddEditInstallmentAccountLedgerDetailRequest $request,
        $latest_balance)
    {
        $property = Property::find($ledger->property_id);
        
    	DB::beginTransaction();
    	$ledger_detail->installment_account_ledger_id = $ledger->id;
    	$ledger_detail->payment_type_id = $request->get('payment_type');

    	$ledger_detail->payment_date = $request->get('payment_date');
    	$ledger_detail->or_no = $request->get('or_no');
    	$ledger_detail->amount_paid = str_replace(',','',$request->get('amount_paid'));
        $ledger_detail->uploader_id = Auth::user()->id;
        $ledger_detail->remarks = $request->get('remarks');

        $ma_count = InstallmentAccountLedgerDetail::getMonthAmoritizationCount($ledger);
        if($ledger->bank_finance){
            $bf_count = InstallmentAccountLedgerDetail::getMonthBankFinanceCount($ledger);
        } else {
            $bf_count = 0;
        }
        
        if($ledger_detail->details_of_payment == "") {
            switch($request->get('payment_type')) {
                case config('constants.PAYMENT_TYPE_RESERVATION_FEE'):
                    $ledger_detail->details_of_payment = "Reservation Fee";
                    break;
                case config('constants.PAYMENT_TYPE_DOWNPAYMENT'):
                    $ledger_detail->details_of_payment = "Down payment";
                    break;
                case config('constants.PAYMENT_TYPE_MA'):
                    $ledger_detail->details_of_payment = InstallmentAccountLedgerDetail::getNthString($ma_count+1). " Monthly Amortization";
                    break;
                case config('constants.PAYMENT_TYPE_PENALTY_PAYMENT'):
                    $ledger_detail->details_of_payment = "Penalty Payment";
                    break;
                case config('constants.PAYMENT_TYPE_PENALTY_FEE'):
                    $ledger_detail->details_of_payment = "Penalty Fee";
                    break;
                case config('constants.PAYMENT_TYPE_FULL_PAYMENT'):
                    $ledger_detail->details_of_payment = "Full payment";
                    break;
                case config('constants.PAYMENT_TYPE_BANK_FINANCE_PAYMENT'):
                    $ledger_detail->details_of_payment = InstallmentAccountLedgerDetail::getNthString($bf_count+1). " Bank-Finance Payment";
                    break;
            }
        }

        if($request->get('payment_type') == config('constants.PAYMENT_TYPE_MA') or 
            $request->get('payment_type') == config('constants.PAYMENT_TYPE_FULL_PAYMENT') or 
    		$request->get('payment_type') == config('constants.PAYMENT_TYPE_PENALTY_PAYMENT') or 
            $request->get('payment_type') == config('constants.PAYMENT_TYPE_PENALTY_FEE') or 
            $request->get('payment_type') == config('constants.PAYMENT_TYPE_BANK_FINANCE_PAYMENT')) {
    		
	    	if($request->get('payment_type') == config('constants.PAYMENT_TYPE_MA')) {
                $ledger_detail->ma_covered_date = $request->get('ma_covered_date');
                
                // Get the total paid for the covered month
                if($ledger_detail->id != null) { 
                    $ledger_details_for_covered_month = InstallmentAccountLedgerDetail::whereRaw(DB::raw('installment_account_ledger_detail_id = '.$ledger_detail->id.' and (ma_covered_date = "'.$request->get('ma_covered_date')).'" or details_of_payment = "'.$ledger_detail->details_of_payment.'")')->sum('amount_paid');
                } else {
                    $ledger_details_for_covered_month = InstallmentAccountLedgerDetail::whereRaw(DB::raw('installment_account_ledger_id = '.$ledger->id.' and (ma_covered_date = "'.$request->get('ma_covered_date')).'" or details_of_payment = "'.$ledger_detail->details_of_payment.'")')->sum('amount_paid');
                }

                $new_total_covered_month = $ledger_details_for_covered_month + str_replace(',','',$request->get('amount_paid'));
                $amount_paid = str_replace(',','',$request->get('amount_paid'));

                // If payment exceeds the montly amortization amount
                if($new_total_covered_month > $ledger->mo_amortization) {
                    if($request->get('balloon_payment') == "on") {
                        $additional_ledger_details[0] = new InstallmentAccountLedgerDetail();
                        $additional_ledger_details[0]->installment_account_ledger_id = $ledger->id;
                        $additional_ledger_details[0]->payment_type_id = $request->get('payment_type');
                        $additional_ledger_details[0]->payment_date = $request->get('payment_date');
                        $additional_ledger_details[0]->details_of_payment = "Monthly Amortization";
                        $additional_ledger_details[0]->or_no = $request->get('or_no');
                        $additional_ledger_details[0]->amount_paid = $ledger_detail->amount_paid - $ledger->mo_amortization;
                        $additional_ledger_details[0]->balance = $latest_balance - $additional_ledger_details[0]->amount_paid;
                    } else {
                        $ma_counter = ceil($new_total_covered_month / $ledger->mo_amortization);

                        $success_counter = 0;
                        for($i=0;$i<$ma_counter;$i++) {
                            $additional_ledger_details[$i] = new InstallmentAccountLedgerDetail();
                            $additional_ledger_details[$i]->installment_account_ledger_id = $ledger->id;
                            $additional_ledger_details[$i]->payment_type_id = $request->get('payment_type');
                            $additional_ledger_details[$i]->payment_date = $request->get('payment_date');
                            $additional_ledger_details[$i]->or_no = $request->get('or_no');
                            $additional_ledger_details[$i]->details_of_payment = InstallmentAccountLedgerDetail::getNthString($ma_count+($i+1)). " Monthly Amortization";
                            if($i == 0) {
                                $additional_ledger_details[$i]->ma_covered_date = $ledger_detail->ma_covered_date;
                                if($new_total_covered_month < $ledger->mo_amortization){
                                    $additional_ledger_details[$i]->amount_paid = $new_total_covered_month;
                                } else if($new_total_covered_month >= $ledger->mo_amortization){
                                    $additional_ledger_details[$i]->amount_paid = $ledger->mo_amortization;
                                }
                            } else {
                                $additional_ledger_details[$i]->ma_covered_date = date('F, Y', strtotime("+".$i." months", strtotime($ledger_detail->ma_covered_date)));
                                if($new_total_covered_month < $ledger->mo_amortization){
                                    $additional_ledger_details[$i]->amount_paid = $new_total_covered_month;
                                } else if($new_total_covered_month >= $ledger->mo_amortization){
                                    $additional_ledger_details[$i]->amount_paid = $ledger->mo_amortization;
                                }
                            }

                            if($new_total_covered_month >= $ledger->mo_amortization) {
                                $additional_ledger_details[$i]->interest = ($ledger->mo_interest/100) * $latest_balance;
                                $additional_ledger_details[$i]->principal = $additional_ledger_details[$i]->amount_paid - $additional_ledger_details[$i]->interest;
                            }

                            $new_total_covered_month -= $additional_ledger_details[$i]->amount_paid;

                            $additional_ledger_details[$i]->balance = $latest_balance - $additional_ledger_details[$i]->principal;
                            $latest_balance = $additional_ledger_details[$i]->balance;
                            
                            if($additional_ledger_details[$i]->touch()){
                                $success_counter++;
                            }
                        }

                        if($success_counter == ($ma_counter)){
                            $return["success"] = PropertyStatus::updatePropertyStatus($property, $ledger);

                            if($return["success"]) {
                                DB::commit();
                                $return["object"] = $ledger_detail;
                                $return["success"] = true;  
                            } else {
                                DB::rollback();
                                $return["success"] = false;
                            }
                        } else {
                            DB::rollback();
                            $return["success"] = false;
                        }
                        return $return;
                    }
                } else if ($new_total_covered_month == $ledger->mo_amortization){
                    $ledger_detail->interest = ($ledger->mo_interest/100) * $latest_balance;
                    $ledger_detail->principal = $ledger->mo_amortization - $ledger_detail->interest;
                    $ledger_detail->balance = $latest_balance - $ledger_detail->principal;
                } else {
                    $ledger_detail->balance = $latest_balance;
                }

                /*if($request->get('with_interest') == "on") {
    	    		$ledger_detail->interest = ($ledger->mo_interest/100) * $latest_balance;
    		    	$ledger_detail->principal = $ledger_detail->amount_paid - $ledger_detail->interest;
    		    	$ledger_detail->balance = $latest_balance - $ledger_detail->principal;
                } else {
                    $ledger_detail->balance = $latest_balance - $ledger_detail->amount_paid;
                }*/
	    	} else if($request->get('payment_type') == config('constants.PAYMENT_TYPE_PENALTY_FEE')) {
                //compute interest with penalty
                /*$ledger_detail->ma_covered_date = $request->get('ma_covered_date');
                $ledger_detail->amount_paid = 0;
                if($request->get('with_interest') == "on") {
                    $ledger_detail->interest = ($ledger->mo_interest/100) * $latest_balance;
                    $ledger_detail->principal = $ledger_detail->amount_paid - $ledger_detail->interest;
                    $ledger_detail->balance = $latest_balance - $ledger_detail->principal;
                } 
                $ledger_detail->details_of_payment = "Penalty Fee";
                $next_penalty = InstallmentAccountLedgerDetail::getNextPenalty($ledger);
                $ledger_detail->penalty = $next_penalty;*/

                $ledger_detail = InstallmentAccountLedgerDetail::getPenalty($ledger, $request->get('ma_covered_date'));
            }  else if($request->get('payment_type') == config('constants.PAYMENT_TYPE_FULL_PAYMENT')) {
                $ledger_detail->amount_paid = InstallmentAccountLedgerDetail::getLastBalance($ledger) + InstallmentAccountLedgerDetail::getInterestForFullPayment($ledger);
                $ledger_detail->balance = 0;

                if(InstallmentAccountLedgerDetail::hasRemainingPenalty($ledger)){
                    $return["success"] = false;
                    $return["message"] = "Please, pay the remaining penalty.";
                    DB::rollback();
                    return $return;
                }

                $property = Property::find($ledger->property_id);
                $property->property_status_id = config('constants.PROPERTY_STATUS_FULLY_PAID');

                $return["success"] = $property->touch();
                if(!$return["success"]){
                    DB::rollback();
                    return $return;
                } 
            } else if($request->get('payment_type') == config('constants.PAYMENT_TYPE_BANK_FINANCE_PAYMENT')) {
                if($bf_count == 0) {
                    $ledger_detail->interest = $ledger->bank_finance_diff * $ledger->bank_finance_mo_interest;
                    $ledger_detail->principal = $ledger_detail->amount_paid - $ledger_detail->interest;
                    $ledger_detail->balance = $ledger->bank_finance_diff - $ledger_detail->principal;
                } else {
                    $ledger_detail->interest =  $latest_balance * $ledger->bank_finance_mo_interest;
                    $ledger_detail->principal = $ledger->mo_amortization - $ledger_detail->interest;
                    $ledger_detail->balance = $latest_balance - $ledger_detail->principal;
                }
            }
    	}

    	$return["success"] = $ledger_detail->touch();
    	if($return["success"]){
            $return["object"] = $ledger_detail;
            DB::commit();

    		// Update the status of the property
            $return["success"] = PropertyStatus::updatePropertyStatus($property, $ledger);
            if($return["success"]) {
                DB::commit();
            } else {
                DB::rollback();
            }
    	} else {
    		DB::rollback();
    	}

    	return $return;
    }

    /**
    *
    *
    */
    public static function getSucceedingEntries(InstallmentAccountLedger $ledger, InstallmentAccountLedgerDetail $ledger_detail)
    {
        return InstallmentAccountLedgerDetail::whereInstallmentAccountLedgerId($ledger->id)
        ->where('id','>',$ledger_detail->id)
        ->get();
    }


    /**
    * Get the position of an integer.
    *
    */
    public static function getNthString($position)
    {
        switch($position){
            case 1: return "1st";
            case 2: return "2nd";
            case 3: return "3rd";
            default: return $position."th";
        }
    }

    /**
    * Delete a ledger entry with database transaction.
    *
    */
    public static function deleteEntry(InstallmentAccountLedgerDetail $ledger_detail)
    {
        DB::beginTransaction();

        $return["success"] = $ledger_detail->delete();

        if($return["success"]) {
            DB::commit();
        } else {
            $return["success"] = false;
            DB::rollback();
        }

        return $return;
    }

    /**
    * Get the latest balance of the ledger.
    *
    */
    public static function getLastBalance(InstallmentAccountLedger $ledger)
    {
        $latest_balance = InstallmentAccountLedgerDetail::whereInstallmentAccountLedgerId($ledger->id)
        ->whereRaw('payment_type_id = '.config('constants.PAYMENT_TYPE_MA').' or payment_type_id = '.config('constants.PAYMENT_TYPE_PENALTY_PAYMENT'))
        ->orderBy('id', 'desc')
        ->first();
        
        if($latest_balance != null){
            return $latest_balance->balance;
        } else {
            return $ledger->balance;
        }
    }

    /**
    * Get the latest balance of the ledger.
    *
    */
    public static function getBalanceOfPreviousEntry(InstallmentAccountLedger $ledger, InstallmentAccountLedgerDetail $ledger_detail)
    {
        $latest_balance = InstallmentAccountLedgerDetail::whereInstallmentAccountLedgerId($ledger->id)
        ->where('id','<',$ledger_detail->id)
        ->where('balance','>=','0')
        ->whereRaw('balance >= 0 and (payment_type_id = '.config('constants.PAYMENT_TYPE_MA').' or '.config('constants.PAYMENT_TYPE_PENALTY_PAYMENT').')')
        ->orderBy('id', 'desc')->first();

        if($latest_balance != null){
            return $latest_balance->balance;
        } else {
            return $ledger->balance;
        }
    }

    /**
    * Check if the buyer has already paid the required MA for the current month.
    *
    */
    public static function hasPaidForTheMonth(InstallmentAccountLedger $ledger)
    {
        $ledger_detail = InstallmentAccountLedgerDetail::whereInstallmentAccountLedgerId($ledger->id)
        ->wherePaymentTypeId(config('constants.PAYMENT_TYPE_MA'))
        ->whereMaCoveredDate(date('F, Y'))->get();

        if($ledger_detail != null and count($ledger_detail) > 0) {
            $total_ma = 0;
            foreach($ledger_details as $ledger_detail) {
                $total_ma += $ledger_detail->amount_paid;
            }
            if($total_ma >= $ledger->mo_amortization) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
    * Has no penalty update for the month.
    *
    */
    public static function hasPenaltyForMonth(InstallmentAccountLedger $ledger)
    {
        $ledger_detail = InstallmentAccountLedgerDetail::whereInstallmentAccountLedgerId($ledger->id)
        ->wherePaymentTypeId(config('constants.PAYMENT_TYPE_PENALTY_FEE'))
        ->whereMonth('created_at','=',date('m'))
        ->whereYear('created_at','=',date('Y'))->first();

        if($ledger_detail != null) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Update the next penalty of the buyer.
    *
    */
    public static function addPenalty(InstallmentAccountLedger $ledger, $ma_covered_date)
    {
        $ledger_detail = new InstallmentAccountLedgerDetail();
        $ledger_detail->installment_account_ledger_id = $ledger->id;
        $ledger_detail->payment_type_id = config('constants.PAYMENT_TYPE_PENALTY_FEE');
        $ledger_detail->details_of_payment = "Penalty Fee";


        $latest_balance = InstallmentAccountLedgerDetail::getLastBalance($ledger);
        $ledger_detail->amount_paid = 0;
        $ledger_detail->interest = InstallmentAccountLedgerDetail::getTrueBalance($ledger)*0.02;
        //$ledger_detail->principal = $ledger_detail->amount_paid - $ledger_detail->interest;
        $ledger_detail->principal = 0;
        $ledger_detail->balance = $latest_balance;
        $ledger_detail->penalty = InstallmentAccountLedgerDetail::getNextPenalty($ledger);
        $ledger_detail->ma_covered_date = date('F, Y');
        $ledger_detail->touch();
    }

    /**
    * Update the next penalty of the buyer.
    *
    */
    public static function getPenalty(InstallmentAccountLedger $ledger, $ma_covered_date)
    {
        $ledger_detail = new InstallmentAccountLedgerDetail();
        $ledger_detail->installment_account_ledger_id = $ledger->id;
        $ledger_detail->payment_type_id = config('constants.PAYMENT_TYPE_PENALTY_FEE');
        $ledger_detail->details_of_payment = "Penalty Fee";

        $latest_balance = InstallmentAccountLedgerDetail::getLastBalance($ledger);
        $ledger_detail->amount_paid = 0;
        $ledger_detail->interest = InstallmentAccountLedgerDetail::getTrueBalance($ledger)*0.02;
        
        if($ledger->penalty_type == config('constants.PENALTY_TYPE_COMPOUNDED_PENALTY')){
            $ledger_detail->principal = 0;
            $ledger_detail->penalty = InstallmentAccountLedgerDetail::getNextPenalty($ledger);
            $ledger_detail->balance = $latest_balance;
        } else if($ledger->penalty_type == config('constants.PENALTY_TYPE_NEGATIVE_PRINCIPAL')){
            $ledger_detail->principal = $ledger_detail->amount_paid - $ledger_detail->interest;
            $ledger_detail->penalty = InstallmentAccountLedgerDetail::getNextPenalty($ledger);
            $ledger_detail->balance = $latest_balance - $ledger_detail->principal;
        } else {
            $ledger_detail->principal = 0;
            $ledger_detail->penalty = InstallmentAccountLedgerDetail::getNextPenalty($ledger) + $ledger_detail->interest;
            $ledger_detail->balance = $latest_balance;
        }
        
        $ledger_detail->ma_covered_date = $ma_covered_date;
        return $ledger_detail;
    }

    /**
    * Get the next penalty of the buyer.
    *
    */
    public static function getNextPenalty(InstallmentAccountLedger $ledger)
    {
        $ledger_details = InstallmentAccountLedgerDetail::whereRaw('installment_account_ledger_id = '.$ledger->id.' or payment_type_id = '.
            config('constants.PAYMENT_TYPE_PENALTY_PAYMENT').' or payment_type_id = '.config('constants.PAYMENT_TYPE_PENALTY_FEE'))
        ->get();

        # Get the interest assumed to have paid on-time
        $penalty_counter = 0;
        $payment_counter = 0;
        $compound_counter = 0;
        foreach($ledger_details as $ledger_detail) {           
            $payment_counter += $ledger_detail->amount_paid;
            $penalty_counter += round($ledger_detail->penalty,2);
            if($penalty_counter == $payment_counter){
                $penalty_counter = 0;
                $payment_counter = 0;
                $compound_counter = 0;
            } else {
                if($ledger_detail->payment_type_id == config('constants.PAYMENT_TYPE_PENALTY_FEE')) {
                    ++$compound_counter;
                }
            }
        }

        if($ledger->bank_finance){
            $base = $ledger->bank_finance_monthly;
        } else {
            $base = $ledger->mo_amortization;
        }

        if($compound_counter == 0) {
            return $base * config('constants.PENALTY_PERCENTAGE');
        } else {
            $next_penalty_base = $base + ($base * config('constants.PENALTY_PERCENTAGE'));
            for($i=0;$i<$compound_counter;$i++) {
                $next_penalty = $next_penalty_base * config('constants.PENALTY_PERCENTAGE'); 
                $next_penalty_base += $next_penalty;
            }
        }

        return $next_penalty;
    }

    /**
    * Get the next penalty of the buyer.
    *
    */
    public static function getRemainingPenalty(InstallmentAccountLedger $ledger)
    {
        $ledger_details = InstallmentAccountLedgerDetail::whereRaw('installment_account_ledger_id = '.$ledger->id.' and (payment_type_id = '.
            config('constants.PAYMENT_TYPE_PENALTY_PAYMENT').' or payment_type_id = '.config('constants.PAYMENT_TYPE_PENALTY_FEE').')')
        ->get();

        $penalty_counter = 0;
        $payment_counter = 0;
        foreach($ledger_details as $ledger_detail) {           
            $payment_counter += $ledger_detail->amount_paid;
            $penalty_counter += round($ledger_detail->penalty,2) + $ledger_detail->interest;
            if($penalty_counter == $payment_counter){
                $penalty_counter = 0;
                $payment_counter = 0;
            }
        }

        return abs(round($penalty_counter - $payment_counter,2));
    }

    /**
    * Check if the buyer still has pending penalties.
    *
    */
    public static function hasRemainingPenalty(InstallmentAccountLedger $ledger)
    {
        $penalty = InstallmentAccountLedgerDetail::getRemainingPenalty($ledger);

        if(round($penalty,2) > 0){
            return true;
        } else {
            return false;
        }
    }

    /**
    * Retrieve the last 10 ledger details.
    *
    */ 
    public static function getLastTenLedgerDetails()
    {
        return InstallmentAccountLedgerDetail::leftJoin('installment_account_ledger','installment_account_ledger.id','=','installment_account_ledger_details.installment_account_ledger_id')
        ->leftJoin('properties','properties.id','=','installment_account_ledger.property_id')
        ->leftJoin('payment_types','payment_types.id','=','installment_account_ledger_details.payment_type_id')
        ->selectRaw('installment_account_ledger_details.*, properties.name as property, installment_account_ledger.*, payment_types.payment_type')
        ->orderBy('installment_account_ledger_details.payment_date','desc')
        ->whereRaw('installment_account_ledger_details.payment_type_id != '. config('constants.PAYMENT_TYPE_PENALTY_FEE'))
        ->take(10)
        ->get();
    }


    /**
    * Retrieve the true balance. In this computation, it it assumed that the buyer has paid faithfully.
    *
    */ 
    public static function getTrueBalance(InstallmentAccountLedger $ledger)
    {
        $ma = $ledger->mo_amortization;

        $first_ma = InstallmentAccountLedgerDetail::whereRaw('installment_account_ledger_id = '.$ledger->id.
                ' and payment_type_id = '.config('constants.PAYMENT_TYPE_MA').
                ' or payment_type_id = '.config('constants.PAYMENT_TYPE_PENALTY_FEE'))
        ->first();

        // Check if the buyer has already paid a monthly amortization
        if($first_ma) {
            $first_date = $first_ma->ma_covered_date;

            $last_ma = InstallmentAccountLedgerDetail::whereRaw('installment_account_ledger_id = '.$ledger->id.
                ' and payment_type_id = '.config('constants.PAYMENT_TYPE_MA').
                ' or payment_type_id = '.config('constants.PAYMENT_TYPE_PENALTY_FEE'))
            ->orderBy('id','desc')
            ->first();
            $last_date = $last_ma->ma_covered_date;

            // Set timezone
            date_default_timezone_set('UTC');

            $first_month_date = explode(',',str_replace(' ','',$first_date));
            $end_month_date = explode(',',str_replace(' ','',$last_date));

            // Start date
            $date = $first_month_date[1].'-'.date('m', strtotime($first_month_date[0])).'-01';
            // End date
            $end_date = $end_month_date[1].'-'.date('m', strtotime($end_month_date[0])).'-01';

            $dates = [];
            array_push($dates, date("M, Y", strtotime($date)));
            while (strtotime($date) <= strtotime($end_date)) {
                $date = date ("Y-m-d", strtotime("+1 month", strtotime($date)));
                array_push($dates, date("M, Y", strtotime($date)));
            }

            //dd($dates);

            $balance = $ledger->balance;
            $interest = 0;
            $principal = 0;

            for($i=0;$i<count($dates)-1;$i++){
                $interest = $balance * 0.02;
                $principal = $ma - $interest;
                $balance -= $principal;
            }   

            return $balance;
        }
    }

    /**
    * Get the interest remaining when paying in full.
    *
    */
    public static function getInterestForFullPayment(InstallmentAccountLedger $ledger)
    {
        $interest_percentage = config('constants.FULL_PAYMENT_INTEREST_PERCENTAGE');
        $total_interest = 0;
        if($ledger->bank_finance) {
            $monthly = $ledger->bank_finance_monthly;
            $months_to_pay = $ledger->bank_finance_months;
            $number_of_amortization = InstallmentAccountLedgerDetail::whereRaw('installment_account_ledger_id = '.$ledger->id.' and payment_type_id = '.config('constants.PAYMENT_TYPE_BANK_FINANCE_PAYMENT'))->count();
        } else {
            $monthly = $ledger->mo_amortization;
            $months_to_pay = $ledger->years_to_pay * 12;
            $number_of_amortization = InstallmentAccountLedgerDetail::whereRaw('installment_account_ledger_id = '.$ledger->id.' and payment_type_id = '.config('constants.PAYMENT_TYPE_MA'))->count();
        }

        $latest_balance = InstallmentAccountLedgerDetail::getLastBalance($ledger);
        
        for($i=0;$i<$months_to_pay-$number_of_amortization;$i++){
            $interest = $latest_balance * ($ledger->mo_interest * 0.01);
            $total_interest += $interest;
            $principal = $monthly - $interest;
            $latest_balance -= $principal;
        }

        return round($total_interest * $interest_percentage,2);
    }

}
