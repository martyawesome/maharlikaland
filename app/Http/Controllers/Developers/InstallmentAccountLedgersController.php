<?php

namespace App\Http\Controllers\Developers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\AddEditInstallmentAccountLedgerRequest;
use App\Http\Requests\AddEditInstallmentAccountLedgerDetailRequest;
use App\Http\Controllers\Controller;

use PHPExcel_Cell;

use App\Buyer;
use App\InstallmentAccountLedger;
use App\InstallmentAccountLedgerDetail;
use App\Property;
use App\PropertyLocation;
use App\PaymentType;
use App\PenaltyType;
use App\PropertyStatus;
use App\Developer;
use App\User;
use SMS;
use Auth;    
use Excel;
use Input;
use Hash;

class InstallmentAccountLedgersController extends Controller
{

    /**
     * Display all buyers.
     *
     *
     */
    public function showBuyers()
    {
        $buyers = Buyer::getByDeveloper();
        $type = 1;
        return view('developers.installment_account_ledgers.buyers', compact('buyers','type'));
    }

    /**
     * Display the properties of the buyer.
     *
     */
    public function showLedgerProperties(Buyer $buyer)
    {
        $ledger_properties = InstallmentAccountLedger::getLedgersOfPropertiesOfBuyer($buyer);
        return view('developers.installment_account_ledgers.ledger_properties', compact('ledger_properties','buyer'));
    }

    /**
    * Show the form for adding a ledger.
    *
    */
    public function showAddLedger(Buyer $buyer)
    {
        $property = new Property();
        $properties = Property::getPropertiesOfBuyerForNewLedger($buyer);
        $ledger = new InstallmentAccountLedger();
        $restructure_allowed = true;
        $add = true;
        $developer = Developer::getCurrentDeveloper();
        $penalty_types = PenaltyType::lists('type','id');
        return view('developers.installment_account_ledgers.add', compact('buyer','property','properties',
            'ledger','restructure_allowed', 'add','penalty_types'));
    }

    /**
    * Save the ledger account.
    *
    */
    public function addLedger(Buyer $buyer, AddEditInstallmentAccountLedgerRequest $request)
    {
        $return = InstallmentAccountLedger::updateLedger($buyer, new InstallmentAccountLedger(), $request);
        if($return["success"]){
            return redirect(route('ledgers_buyers'))->withSuccess('Ledger Account for <i>'.$buyer->first_name.' '.$buyer->last_name.'</i> was successfully added');
        } else {
            return redirect(back())->withDanger('Ledger Account for <i>'.$buyer->first_name.' '.$buyer->last_name.'</i> was unsuccessfully added');
        }
    }

    /**
     * Show the buyers to choose a ledger account.
     *
     */
    public function showLedgersBuyers()
    {
        $buyers = Buyer::getByDeveloper();
        $type = 2;
        return view('developers.installment_account_ledgers.buyers', compact('buyers','type'));
    }

    /**
    * Show the ledger details of a buyer.
    *
    */
    public static function showLedger(Buyer $buyer, Property $property)
    {
        $ledger = InstallmentAccountLedger::getLedgerOfProperty($property);
        $ledger_details = InstallmentAccountLedgerDetail::getLedgerDetailsOfBuyer($ledger);
        $remaining_penalty = InstallmentAccountLedgerDetail::getRemainingPenalty($ledger);
        $payment_types = PaymentType::getPossiblePaymentTypes($ledger);
        /*InstallmentAccountLedgerDetail::getTrueBalance($ledger);*/
        return view('developers.installment_account_ledgers.view', compact('buyer','ledger','property','ledger_details','remaining_penalty','payment_types'));
    }

    /**
    * Show the form for editing a ledger.
    *
    */
    public function showEditLedger(Buyer $buyer, InstallmentAccountLedger $ledger)
    {
        $property = Property::getPropertyFromLedger($ledger);
        $properties = Property::whereBuyerId($buyer->id)->lists('name','id');
        $restructure_allowed = InstallmentAccountLedgerDetail::getRemainingPenalty($ledger) <= 0 ? true : false;
        $add = false;
        return view('developers.installment_account_ledgers.edit', compact('buyer','property','properties','ledger','restructure_allowed','add'));
    }

    /**
    * Edit the ledger account.
    *
    */
    public function editLedger(Buyer $buyer, InstallmentAccountLedger $ledger, AddEditInstallmentAccountLedgerRequest $request)
    {
        $return = InstallmentAccountLedger::updateLedger($buyer, $ledger, $request);
        $property = Property::getPropertyFromLedger($ledger);
        
        if($return["success"]){
            return redirect(route('ledger', array($buyer->id, $property->slug)))->withSuccess('Ledger Account for <i>'.$buyer->first_name.' '.$buyer->last_name.'</i> was successfully edit');
        } else {
            return redirect(route('ledger', array($buyer->id, $property->slug)))->withDanger('Ledger Account for <i>'.$buyer->first_name.' '.$buyer->last_name.'</i> was unsuccessfully edit');
        }
    }

    /**
    * Delete a ledger account; delete all entries, the property's status and the property's buyer.
    *
    */
    public function deleteLedger(Buyer $buyer, InstallmentAccountLedger $ledger, Request $request)
    {
        $property = Property::getPropertyFromLedger($ledger);
        if($property == null) return 0;
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = InstallmentAccountLedger::deleteLedgerAccount($ledger, $property);
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
    * Show the form for adding a ledger.
    *
    */
    public function showAddEntry(Buyer $buyer, InstallmentAccountLedger $ledger)
    {
        $developer = Developer::getCurrentDeveloper();
        $property = Property::getPropertyFromLedger($ledger);
        $ledger_detail = new InstallmentAccountLedgerDetail();
        $payment_types = PaymentType::getPossiblePaymentTypes($ledger);
        $ledger_details = InstallmentAccountLedgerDetail::getLedgerDetailsOfBuyer($ledger);
        return view('developers.installment_account_ledger_details.add', compact('buyer','ledger','property',
            'ledger_details','payment_types','ledger_detail'));
    }

    /**
    * Added the ledger entry for the buyer and property.
    *
    */
    public static function addEntry(Buyer $buyer, InstallmentAccountLedger $ledger, AddEditInstallmentAccountLedgerDetailRequest $request)
    {
        $return = InstallmentAccountLedgerDetail::addLedgerDetail($ledger, new InstallmentAccountLedgerDetail(), $request);
        $property = Property::find($ledger->property_id);
        if($return["success"]){
            return redirect(route('ledger', array($buyer->id,$property->slug)))->withSuccess('Ledger Entry for <i>'.$buyer->first_name.' '.$buyer->last_name.'</i> was successfully added');
        } else {
            return redirect(route('add_ledger_entry', array($buyer->id,$ledger->id)))->withDanger('Ledger Entry for <i>'.$buyer->first_name.' '.$buyer->last_name.'</i> was unsuccessfully added. '.$return['message']);
        }
    }

    /**
    * Show the form for editing a ledger.
    *
    */
    public function showEditEntry(Buyer $buyer, InstallmentAccountLedger $ledger, InstallmentAccountLedgerDetail $ledger_detail)
    {
        $property = Property::getPropertyFromLedger($ledger);
        $payment_types = PaymentType::getPossiblePaymentTypes($ledger);
        $ledger_details = InstallmentAccountLedgerDetail::getLedgerDetailsOfBuyer($ledger);
        $user = User::find($ledger_detail->uploader_id);
        return view('developers.installment_account_ledger_details.edit', compact('buyer','ledger','property','ledger_details','payment_types','ledger_detail','user'));
    }

    /**
    * Added the ledger entry for the buyer and property.
    *
    */
    public static function editEntry(Buyer $buyer, InstallmentAccountLedger $ledger, InstallmentAccountLedgerDetail $ledger_detail, AddEditInstallmentAccountLedgerDetailRequest $request)
    {
        $return = InstallmentAccountLedgerDetail::editLedgerDetail($ledger, $ledger_detail, $request);
        $property = Property::find($ledger->property_id);

        $return = PropertyStatus::updatePropertyStatus($property, $ledger);

        if($return["success"]){
            return redirect(route('ledger', array($buyer->id,$property->slug)))->withSuccess('Ledger Entry for <i>'.$buyer->first_name.' '.$buyer->last_name.'</i> was successfully edited');
        } else {
            return redirect(route('ledger', array($buyer->id,$property->slug)))->withDanger('Ledger Entry for <i>'.$buyer->first_name.' '.$buyer->last_name.'</i> was unsuccessfully edited');
        }
    }

    /**
    * Delete a ledger entry.
    *
    */
    public function deleteEntry(Buyer $buyer, InstallmentAccountLedger $ledger, InstallmentAccountLedgerDetail $ledger_detail)
    {
        $return = InstallmentAccountLedgerDetail::deleteEntry($ledger_detail);
        $property = Property::find($ledger->property_id);

        if($return["success"]){
            return redirect(route('ledger', array($buyer->id,$property->slug)))->withSuccess('Ledger Entry for <i>'.$buyer->first_name.' '.$buyer->last_name.'</i> was successfully deleted');
        } else {
            return redirect(route('ledger', array($buyer->id,$property->slug)))->withDanger('Ledger Entry for <i>'.$buyer->first_name.' '.$buyer->last_name.'</i> was unsuccessfully deleted');
        }
    }

    /**
    * Export the ledger details to an excel file.
    *
    */
    public static function exportToExcel(Buyer $buyer, InstallmentAccountLedger $ledger)
    {
        $excel = InstallmentAccountLedger::formatLedgerToExport($buyer, $ledger);
        $excel->export('xlsx');
    }

    /**
    * Export the ledger details to a PDF file.
    *
    */
    public static function exportToPdf(Buyer $buyer, InstallmentAccountLedger $ledger)
    {
        $excel = InstallmentAccountLedger::formatLedgerToExport($buyer, $ledger);
        $excel->export('pdf');
    }

    /**
    * Import the ledgers from an Excel file and save the data to the database.
    *
    */
    public static function importFromExcel(Buyer $buyer, Property $property)
    {
        if(Input::hasFile('ledger_excel')){
            $ledger = InstallmentAccountLedger::getLedgerOfProperty($property);
            $path = Input::file('ledger_excel')->getRealPath();
            $data = Excel::selectSheetsByIndex(0)->load($path, function($reader) {
                $reader->formatDates(false);
            })->get();
            
            $return = InstallmentAccountLedger::importLedger($ledger, $data);

            if($return["success"]){
                return redirect(route('ledger', array($buyer->id,$property->slug)))->withSuccess('Ledger entries for <i>'.$property->name.'</i> were successfully imported');
            } else {
                return redirect(route('ledger', array($buyer->id,$property->slug)))->withDanger('Ledger entries for <i>'.$property->name.'</i> were unsuccessfully imported');
            }
        } else {
            return redirect(route('ledger', array($buyer->id,$property->slug)))->withDanger('Ledger entries for <i>'.$property->name.'</i> were unsuccessfully imported');
        }
    }

    /**
     * Display the penalty calculator.
     *
     * For now, input the monthly amortization then later change it to a dropdown of 
     * ongoing and finished ledgers.
     *
     */
    public function showPenaltyCalculator()
    {
        return view('developers.installment_account_ledgers.penalty_calculator', compact('ledger_properties','buyer'));
    }

}
