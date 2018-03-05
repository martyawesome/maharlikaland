<?php

namespace App;

use App\Http\Requests\AddEditInstallmentAccountLedgerRequest;

use Illuminate\Database\Eloquent\Model;

use App\InstallmentAccountLedgerDetail;
use App\Buyer;
use App\Property;
use App\PropertyLocation;
use App\Developer;

use Auth;
use DB;
use Excel;
use DateTime;

class InstallmentAccountLedger extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'installment_account_ledger';

    /**
    * Get the ledger of a buyer.
    *
    */
    public static function getLedgersOfPropertiesOfBuyer(Buyer $buyer)
    {
        return InstallmentAccountLedger::leftJoin('properties','properties.id','=','installment_account_ledger.property_id')
        ->whereRaw('installment_account_ledger.buyer_id = '.$buyer->id)
        ->get();
    }

    /**
    * Get the ledger of a property.
    *
    */
    public static function getLedgerOfProperty(Property $property)
    {
        return InstallmentAccountLedger::wherePropertyId($property->id)->first();
    }

    /**
    * Save the ledger of a buyer. If anything wrong happens, rollback the database transaction
    * and notify the user that something went wrong.
    *
    */
    public static function updateLedger(Buyer $buyer, InstallmentAccountLedger $ledger, AddEditInstallmentAccountLedgerRequest $request)
    {
    	DB::beginTransaction();

        if($ledger->id != null) {
            $restructure_allowed = InstallmentAccountLedgerDetail::getRemainingPenalty($ledger) <= 0 ? true : false;
        } else {
            $restructure_allowed = false;
        }
        $for_restructure = ($ledger->years_to_pay != $request->get('years_to_pay') or ($ledger->mo_interest != $request->get('mo_interest'))) ? true : false;

        $property = Property::find($request->get('property'));
        $property->buyer_id = $buyer->id;
        $return["success"] = $property->touch();

        if($return["success"]) {
        	$ledger->property_id = $request->get('property');
        	$ledger->buyer_id = $buyer->id;
        	$ledger->tct_no = $request->get('tct_no');
        	$ledger->tcp = str_replace(',','',$request->get('tcp'));
        	$ledger->dp_percentage = str_replace('%','',$request->get('dp_percentage'));
        	$ledger->dp = str_replace(',','',$request->get('dp'));
            $ledger->dp_discount = str_replace(',','',$request->get('dp_discount'));
        	$ledger->balance = str_replace(',','',$request->get('balance'));
        	$ledger->reservation_fee = str_replace(',','',$request->get('reservation_fee'));
        	$ledger->contract_date = $request->get('contract_date');
        	$ledger->years_to_pay = $request->get('years_to_pay');
        	$ledger->due_date = $request->get('due_date');
        	$ledger->mo_interest = $request->get('mo_interest');
        	$ledger->mo_amortization = str_replace(',','',$request->get('mo_amortization'));
            $ledger->uploader_id = Auth::user()->id;
            $ledger->bank_finance = str_replace(',','',$request->get('bank_finance'));
            $ledger->bank_finance_months = $request->get('bank_finance_months');
            //dd(InstallmentAccountLedgerDetail::getLastBalance($ledger));
            $ledger->bank_finance_diff = abs(InstallmentAccountLedgerDetail::getLastBalance($ledger) - $request->get('bank_finance'));  
        	

            if($ledger->years_to_pay < 3){
                $mo_interest = 0.015;
            } else {
                $mo_interest = 0.02;
            }
            $ledger->bank_finance_mo_interest = $mo_interest;
            
            if($ledger->bank_finance){
                $factor = $mo_interest/(1-(pow((1+$mo_interest), (-1 * $ledger->bank_finance_months))));
                $ledger->bank_finance_monthly = $ledger->bank_finance_diff * $factor;
            }
            
            $return["success"] = $ledger->touch();

        	if($return["success"]){
                if($restructure_allowed and $for_restructure) {
                    $return = InstallmentAccountLedger::restructureLedger($ledger);
                    if($return["success"]) {
                        DB::commit();
                    } else {
                        DB::rollback();
                    }
                } else {
                    DB::commit();
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
    * Import the data read from an excel file.
    *
    */
    public static function importLedger(InstallmentAccountLedger $ledger, $data)
    {
        $ledger_details = InstallmentAccountLedgerDetail::getLedgerDetailsOfBuyer($ledger);
        DB::beginTransaction();

        $ledger_details_count = count($ledger_details);
        $counter = 0;
        foreach($ledger_details as $ledger_detail){
            $deleted = $ledger_detail->delete();
            if($deleted) $counter++; 
        }   

        if($ledger_details_count == $counter) {
            $new_ledger_count = count($data);
            $new_ledger_detail_counter = 0;

            foreach($data as $datum) {
                if($datum->details != null) {
                    $ledger_detail = new InstallmentAccountLedgerDetail();
                    $ledger_detail->installment_account_ledger_id = $ledger->id;

                    if($datum->date == null){
                        $ledger_detail->payment_date = "";
                    } else {
                        $date = new DateTime($datum->date);
                        $ledger_detail->payment_date = $date->format('Y-m-d');
                    }
                    $ledger_detail->details_of_payment = $datum->details;

                    if(strpos(strtolower($datum->details), 'reservation') !== false) {
                        $ledger_detail->payment_type_id = config('constants.PAYMENT_TYPE_RESERVATION_FEE');
                    } 
                    else if(strpos(strtolower($datum->details), 'downpayment') !== false or strpos(strtolower($datum->details), 'down payment') !== false) {
                        $ledger_detail->payment_type_id = config('constants.PAYMENT_TYPE_DOWNPAYMENT');
                    }
                    else if(strpos(strtolower($datum->details), 'penalty fee') !== false) {
                        $ledger_detail->payment_type_id = config('constants.PAYMENT_TYPE_PENALTY_FEE');
                    }
                    else if(strpos(strtolower($datum->details), 'monthly amortization') !== false) {
                        $ledger_detail->payment_type_id = config('constants.PAYMENT_TYPE_MA');
                    } 
                    else if(strpos(strtolower($datum->details), 'penalty payment') !== false) {
                        $ledger_detail->payment_type_id = config('constants.PAYMENT_TYPE_PENALTY_PAYMENT');
                    }
                    else if(strpos(strtolower($datum->details), 'bank-finance') !== false) {
                        $ledger_detail->payment_type_id = config('constants.PAYMENT_TYPE_BANK_FINANCE_PAYMENT');
                    }

                    $ledger_detail->ma_covered_date = $datum->ma_covered;
                    $ledger_detail->or_no = $datum->or_no;
                    $ledger_detail->amount_paid = str_replace(',','',$datum->amount);
                    $ledger_detail->interest = str_replace(',','',$datum->interest);
                    $ledger_detail->principal = str_replace(',','',$datum->principal);
                    $ledger_detail->balance = str_replace(',','',$datum->balance);
                    $ledger_detail->remarks = $datum->remarks;
                    $ledger_detail->penalty = str_replace(',','',$datum->penalty);
                    //$ledger_detail->floating = str_replace(',','',$datum->floating);
                    $ledger_detail->uploader_id = Auth::user()->id;

                    $ledger_detail_success = $ledger_detail->touch();
                } else {
                    $ledger_detail_success = true;
                }

                if($ledger_detail_success) $new_ledger_detail_counter++;
            }

            if($new_ledger_count == $new_ledger_detail_counter) {
                $property = Property::find($ledger->property_id);
                $return["success"] = PropertyStatus::updatePropertyStatus($property, $ledger);
                if($return["success"]) {
                    DB::commit();
                } else {
                    DB::rollback();
                }
            } else {
                // Notify user that the import of ledger details has failed
                DB::rollback();
                $return["success"] = false;
            }
        } else {
            // Notify user that the import of ledger details has failed
            DB::rollback();
            $return["success"] = false;
        }

        return $return;

    }

    /**
    * Format the ledger to be exported to either excel of pdf.
    *
    */
    public static function formatLedgerToExport(Buyer $buyer, InstallmentAccountLedger $ledger)
    {
        $property = Property::find($ledger->property_id);
        $developer = Developer::find(Auth::user()->developer_id);
        return Excel::create($property->name, function($excel) use ($buyer, $ledger, $property, $developer) {
            $excel->setTitle($property->name);
            $excel->setCompany($developer->name);
            $excel->setDescription("The ledger account of ".$buyer->first_name." "
                .$buyer->middle_name." ".$buyer->last_name." for ".$property->name);
            $excel->sheet("Ledger Entries", function($sheet) use($ledger, $property, $buyer) {
                $ledger_details = InstallmentAccountLedgerDetail::getLedgerDetailsOfBuyerForExcel($ledger);

                $property_location = PropertyLocation::wherePropertyId($property->id)->first(); 

                // Merge cells for name
                $sheet->mergeCells('A1:J1');

                $sheet->row(1, array('DATE', 'DETAILS','MA COVERED','OR NO','AMOUNT','INTEREST','PRINCIPAL','BALANCE','PENALTY','REMARKS'));

                $sheet->cells('A1:J1', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });
                $sheet->setBorder('A1:J1', 'thin');

                $ledger_start = 2;
                for($i=$ledger_start;$i<count($ledger_details)+$ledger_start;$i++){
                    if($ledger_details[$i-$ledger_start]->payment_date == "0000-00-00") {
                        $payment_date = "";
                    } else {
                        $payment_date = $ledger_details[$i-$ledger_start]->payment_date;
                    }
                    $sheet->row($i, array($payment_date,
                        $ledger_details[$i-$ledger_start]->details_of_payment,
                        $ledger_details[$i-$ledger_start]->ma_covered_date,
                        $ledger_details[$i-$ledger_start]->or_no, 
                        number_format($ledger_details[$i-$ledger_start]->amount_paid, 2, '.', ','),
                        number_format($ledger_details[$i-$ledger_start]->interest, 2, '.', ','),
                        number_format($ledger_details[$i-$ledger_start]->principal, 2, '.', ','),
                        number_format($ledger_details[$i-$ledger_start]->balance, 2, '.', ','),
                        number_format($ledger_details[$i-$ledger_start]->penalty, 2, '.', ','),
                        //number_format($ledger_details[$i-$ledger_start]->floating, 2, '.', ','),
                        $ledger_details[$i-$ledger_start]->remarks));

                    $sheet->setBorder('A'.$i.':J'.$i, 'thin');
                    $sheet->cells('A'.$i, function($cells){
                        $cells->setAlignment('center');
                    });
                    $sheet->cells('C'.$i.':J'.$i, function($cells){
                        $cells->setAlignment('center');
                    });
                }
            });

            $excel->sheet("Basic Info", function($sheet) use ($ledger, $property, $buyer) {
                $property_location = PropertyLocation::wherePropertyId($property->id)->first(); 
    
                $sheet->cells('B1:B13', function($cells) {
                    $cells->setAlignment('left');
                });                

                $sheet->row(1, array('Name', $buyer->first_name." ".$buyer->middle_name." ".$buyer->last_name));
                $sheet->row(2, array('Address', $buyer->home_address));
                $sheet->row(3, array('Block No', $property_location->block_number));
                $sheet->row(4, array('Lot No', $property_location->lot_number));
                $sheet->row(5, array('Price per sqm', $property->price_per_sqm));
                $sheet->row(6, array('Area (sqm)', $property->lot_area));
                $sheet->row(7, array('Contract Price', $ledger->tcp));
                $sheet->row(8, array('Monthly Amortization', $ledger->mo_amortization));
                $sheet->row(9, array('Down payment', $ledger->dp));
                $sheet->row(10, array('Balance', $ledger->balance));
                $sheet->row(11, array('Due Date', $ledger->due_date));
                $sheet->row(12, array('Contract Date', $ledger->contract_date));
                $sheet->row(13, array('TCT No', $ledger->tct_no));
            });
        });
    }

    /**
    * Restructure the whole ledger. Happens when buyer changes years to pay.
    *
    */
    public static function restructureLedger(InstallmentAccountLedger $ledger)
    {
        $ledger_details = InstallmentAccountLedgerDetail::whereRaw('installment_account_ledger_id = '.$ledger->id.' and (payment_type_id = '.config('constants.PAYMENT_TYPE_RESERVATION_FEE').' or payment_type_id = '.
            config('constants.PAYMENT_TYPE_DOWNPAYMENT').' or payment_type_id = '. config('constants.PAYMENT_TYPE_MA').')')
        ->get();

        $ledger_details_count = count($ledger_details);
        $ledger_details_counter = 0;

        $has_deleted = InstallmentAccountLedgerDetail::whereInstallmentAccountLedgerId($ledger->id)->delete();

        if($has_deleted) {
            foreach($ledger_details as $ledger_detail) {
                $new_ledger_detail = new InstallmentAccountLedgerDetail();
                $new_ledger_detail->installment_account_ledger_id = $ledger->id;
                $new_ledger_detail->payment_type_id  = $ledger_detail->payment_type_id;
                $new_ledger_detail->payment_date = $ledger_detail->payment_date;
                $new_ledger_detail->details_of_payment = $ledger_detail->details_of_payment;
                $new_ledger_detail->or_no = $ledger_detail->or_no;
                $new_ledger_detail->amount_paid = $ledger_detail->amount_paid;
                $new_ledger_detail->remarks = $ledger_detail->remarks;
                $new_ledger_detail->penalty = $ledger_detail->penalty;
                $new_ledger_detail->ma_covered_date = $ledger_detail->ma_covered_date;
                $new_ledger_detail->uploader_id = Auth::user()->id;
                //$new_ledger_detail->floating = $ledger_detail->floating;

                $last_balance = ($ledger_details_counter == 0) ? $ledger->balance : InstallmentAccountLedgerDetail::getLastBalance($ledger);
                
                if($new_ledger_detail->payment_type_id == config('constants.PAYMENT_TYPE_MA')) {
                    $new_ledger_detail->interest = $last_balance * ($ledger->mo_interest/100);
                    $new_ledger_detail->principal = $new_ledger_detail->amount_paid - $new_ledger_detail->interest;
                    $new_ledger_detail->balance = $last_balance - $new_ledger_detail->principal;
                }
                if($new_ledger_detail->touch()) {
                    $ledger_details_counter++;
                }
            }
            if($ledger_details_counter == $ledger_details_count) {
                $property = Property::find($ledger->property_id);
                $return["success"] = PropertyStatus::updatePropertyStatus($property, $ledger);
            } else {
                $return["success"] = false;
            }
            
        } else {
            $return["success"] = false;
        }

        return $return;
    }

    /**
    * Delete a ledger account; delete all entries, the property's status and the property's buyer.
    *
    */
    public static function deleteLedgerAccount(InstallmentAccountLedger $ledger, Property $property)
    {
        DB::beginTransaction();

        $ledger_details_count = InstallmentAccountLedgerDetail::whereInstallmentAccountLedgerId($ledger->id)->count();

        if($ledger_details_count > 0) {
            $return["success"] = InstallmentAccountLedgerDetail::whereInstallmentAccountLedgerId($ledger->id)->delete();
        } else {
            $return["success"] = true;
        }

        if($return["success"]) {
            $return["success"] = $ledger->delete();
            if($return["success"]) {
                $property->property_status_id = config('constants.PROPERTY_STATUS_FOR_SALE');
                $property->buyer_id = NULL;
                $return["success"] = $property->touch();
                if($return["success"]) {
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

        return $return;
    }

    /**
    * Get the ledgers where the due date is the current day.
    *
    */
    public static function getCurrentDueDates()
    {
        $current_day = (int)(date('d'));
        $developer = Developer::getCurrentDeveloper();

        return InstallmentAccountLedger::leftJoin(DB::raw('(select installment_account_ledger_id, sum(amount_paid) as total_amount_paid from installment_account_ledger_details where payment_type_id = '.config('constants.PAYMENT_TYPE_MA') .' group by installment_account_ledger_id) as ap'),'ap.installment_account_ledger_id','=','installment_account_ledger.id')
        ->leftJoin(DB::raw('(select installment_account_ledger_id, sum(amount_paid) as total_reservation from installment_account_ledger_details where payment_type_id = '.config('constants.PAYMENT_TYPE_RESERVATION_FEE') .' group by installment_account_ledger_id) as r'),'r.installment_account_ledger_id','=','installment_account_ledger.id')
        ->leftJoin(DB::raw('(select installment_account_ledger_id, sum(amount_paid) as total_dp from installment_account_ledger_details where payment_type_id = '.config('constants.PAYMENT_TYPE_DOWNPAYMENT') .' group by installment_account_ledger_id) as d'),'d.installment_account_ledger_id','=','installment_account_ledger.id')
        ->leftJoin('properties','properties.id','=','installment_account_ledger.property_id')
        ->leftJoin('projects','projects.id','=','properties.project_id')
        ->leftJoin('buyers','buyers.id','=','installment_account_ledger.buyer_id')
        ->select(DB::raw('properties.name as property, properties.slug as property_slug, projects.name as project, projects.slug as project_slug, installment_account_ledger.*'))
        ->whereRaw(DB::raw('installment_account_ledger.due_date = '.$current_day.' and projects.developer_id = '.$developer->id.' and ((IFNULL(total_amount_paid,0) > 0 and installment_account_ledger.balance > IFNULL(total_amount_paid,0)) or (IFNULL(total_reservation,0) + IFNULL(total_dp,0) >= installment_account_ledger.dp))'))
        ->get();
    }

    /**
    * Delete a property.
    *
    */
    public static function deleteProperty(Property $property)
    {
        $installment_account_ledger = InstallmentAccountLedger::wherePropertyId($property)->first();
        if(!$installment_account_ledger){
            $installment_account_ledger_deleted = true;
            $details_deleted = true;
        } else {
            if(InstallmentAccountLedgerDetail::whereInstallmentAccountLedgerId($installment_account_ledger->id)->count() == 0) {
                $details_deleted = true;
            } else {
                $details_deleted = InstallmentAccountLedgerDetail::whereInstallmentAccountLedgerId($installment_account_ledger->id)->delete();
            }   
            $installment_account_ledger_deleted = $installment_account_ledger->delete();
        }

        return $installment_account_ledger_deleted and $details_deleted;
    }

    /**
    * Delete a project.
    *
    */
    public static function deleteByProject(Project $project)
    {
        $installment_account_ledgers = InstallmentAccountLedger::leftJoin('properties','properties.id','=','installment_account_ledger.property_id')
        ->where(DB::raw('properties.project_id = '.$project->id))
        ->get();

        if(count($installment_account_ledgers) == 0){
            $installment_account_ledgers_deleted = true;
            $details_deleted = true;
        } else {
            $installment_account_ledger_details = InstallmentAccountLedgerDetail::leftJoin('installment_account_ledger','installment_account_ledger.id','=','installment_account_ledger_details.installment_account_ledger_id')
            ->leftJoin('properties','properties.id','=','installment_account_ledger.property_id')
            ->where(DB::raw('properties.project_id = '.$project->id))
            ->get();

            if($installment_account_ledgers != null and count($installment_account_ledger_details) > 0) {
                if(count($installment_account_ledger_details) == $details_deleted = $installment_account_ledger_details->delete()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }   
            $installment_account_ledgers_deleted = $installment_account_ledgers->delete();
        }

        return $installment_account_ledgers_deleted and $details_deleted;
    }
}
