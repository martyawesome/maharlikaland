<?php

namespace App\Http\Controllers\Developers;

use Illuminate\Http\Request;
use App\Http\Requests\AddEditAccountTitleRequest;
use App\Http\Requests\AddEditVoucherRequest;
use App\Http\Requests\AddEditVoucherDetailRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\AccountTitle;
use App\Voucher;
use App\VoucherDetail;
use App\Developer;
use App\Project;
use App\Property;
use Hash;

class AccountingController extends Controller
{
    
    /**
    * List all of the account titles.
    *
    */
    public function showAccountTitles() {
        $account_titles = AccountTitle::orderBy('title')->get();
        return view('developers.accounting.account_titles.all', compact('account_titles'));
    }

    /**
    * Show the form for adding an account title.
    *   
    */
    public function showAddAccountTitle()
    {  
        $account_title = new AccountTitle();
        return view('developers.accounting.account_titles.add', compact('account_title'));
    }

    /**
    * Save a newly added account title.
    *
    */
    public function addAccountTitle(AddEditAccountTitleRequest $request)
    {
        $return = AccountTitle::updateAccountTitle(new AccountTitle(), $request);

        if($return["success"]){
            return redirect(route('account_titles', array()))->withSuccess('Account title <i>'.$request->get('account_title').'</i> was successfully added');
        } else {
            return redirect(route('account_titles', array()))->withDanger('Account title for <i>'.$request->get('account_title').'</i> was unsuccessfully added');
        }
    }

    /**
    * Show the form for editing an account title.
    *
    */
    public function showEditAccountTitle(AccountTitle $account_title)
    {
        return view('developers.accounting.account_titles.edit', compact('account_title'));
    }

    /**
    * Save a newly added account title.
    *
    */
    public function editAccountTitle(AccountTitle $account_title, AddEditAccountTitleRequest $request)
    {
        $return = AccountTitle::updateAccountTitle($account_title, $request);

        if($return["success"]){
            return redirect(route('account_titles', array()))->withSuccess('Account title <i>'.$request->get('account_title').'</i> was successfully edited');
        } else {
            return redirect(route('account_titles', array()))->withDanger('Account title for <i>'.$request->get('account_title').'</i> was unsuccessfully edited');
        }
    }

    /**
    * Delete an account title.
    *
    */
    public function deleteAccountTitle(AccountTitle $account_title, Request $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $deleted = AccountTitle::whereId($account_title->id)->delete();
            if($deleted) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        }
    }

    /**
     * Display all projects
     *
     */
    public function showVouchersProjects()
    {
        $projects = Project::getAll();
        return view('developers.accounting.vouchers.projects', compact('projects'));
    }

    /**
    * List all of the vouchers.
    *
    */
    public function showVouchers()
    {
        $vouchers = Voucher::getAll();
        return view('developers.accounting.vouchers.all', compact('vouchers'));
    }

    /**
    * Show the form for adding a voucher.
    *
    */
    public function showAddVoucher()
    {
        $voucher = new Voucher();
        return view('developers.accounting.vouchers.add', compact('voucher'));
    }

    /**
    * Add a voucher.
    *
    */
    public function addVoucher(AddEditVoucherRequest $request)
    {
        $return = Voucher::updateVoucher(new Voucher(), $request);

        if($return["success"]){
            return redirect(route('vouchers'))->withSuccess('Voucher was successfully added');
        } else {
            return redirect(route('vouchers'))->withDanger('Voucher was unsuccessfully added');
        }
    }

    /**
    * Show the form for editing a voucher.
    *
    */
    public function showEditVoucher(Voucher $voucher)
    {
        return view('developers.accounting.vouchers.edit', compact('voucher'));
    }

    /**
    * Edit a voucher.
    *
    */
    public function editVoucher(Voucher $voucher, AddEditVoucherRequest $request)
    {
        $return = Voucher::updateVoucher($voucher, $request);

        if($return["success"]){
            return redirect(route('vouchers'))->withSuccess('Voucher was successfully edited');
        } else {
            return redirect(route('vouchers'))->withDanger('Voucher was unsuccessfully edited');
        }
    }

    /**
    * Show the voucher and its particulars
    *
    */
    public function showVoucher(Voucher $voucher)
    {
        $voucher_amount  = VoucherDetail::getTotalAmount($voucher);
        $voucher_details = VoucherDetail::getAll($voucher);
        return view('developers.accounting.vouchers.view', compact('voucher','voucher_amount','voucher_details'));
    }

    /**
    * Delete a projet's a voucher and voucher details.
    *
    */
    public function deleteVoucher(Voucher $voucher, Request $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = Voucher::deleteVoucher($voucher);
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
    * Show the form for adding a voucher detail.
    *
    */
    public function showAddVoucherDetail(Voucher $voucher)
    {
        $voucher_detail = new VoucherDetail();
        $account_titles = AccountTitle::getAllForForm();
        $properties = Property::lists('name', 'id');
        $properties->prepend('None');
        return view('developers.accounting.voucher_details.add', compact('voucher','voucher_detail','account_titles','properties'));
    }

    /**
    * Add a voucher detail.
    *
    */
    public function addVoucherDetail(Voucher $voucher, AddEditVoucherDetailRequest $request)
    {
        $return = VoucherDetail::updateVoucherDetail($voucher, new VoucherDetail(), $request);

        if($return["success"]){
            return redirect(route('voucher', $voucher->voucher_number))->withSuccess('Particular for voucher number <i>'.$voucher->voucher_number.'</i> was successfully added');
        } else {
            return redirect(route('voucher', $voucher->voucher_number))->withDanger('Particular for voucher number <i>'.$voucher->voucher_number.'</i> was unsuccessfully added');
        }
    }

    /**
    * Edit a voucher detail.
    *
    */
    public function showEditVoucherDetail(Voucher $voucher, VoucherDetail $voucher_detail)
    {
        $account_titles = AccountTitle::getAllForForm();
        $properties = Property::lists('name', 'id');
        $properties->prepend('None');
        return view('developers.accounting.voucher_details.edit', compact('voucher','voucher_detail','account_titles','properties'));
    }

    /**
    * Edit a voucher detail.
    *
    */
    public function editVoucherDetail(Voucher $voucher, VoucherDetail $voucher_detail, AddEditVoucherDetailRequest $request)
    {
        $return = VoucherDetail::updateVoucherDetail($voucher, $voucher_detail, $request);

        if($return["success"]){
            return redirect(route('voucher', array($voucher->voucher_number)))->withSuccess('Particular for voucher number <i>'.$voucher->voucher_number.'</i> was successfully added');
        } else {
            return redirect(route('voucher', array($voucher->voucher_number)))->withDanger('Particular for voucher number <i>'.$voucher->voucher_number.'</i> was unsuccessfully added');
        }
    }

    /**
    * Delete voucher details.
    *
    */
    public function deleteVoucherDetail(Voucher $voucher, VoucherDetail $voucher_detail, Request $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = VoucherDetail::deleteVoucherDetail($voucher_detail);
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
