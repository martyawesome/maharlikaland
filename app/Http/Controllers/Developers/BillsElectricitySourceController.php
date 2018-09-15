<?php

namespace App\Http\Controllers\Developers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\AddEditElectricityBillRequest;

use App\Project;
use App\BillElectricitySource;
use App\BillElectricitySourceDetail;
use App\Property;
use App\Developer;
use Hash;
use Input;
use Excel;

class BillsElectricitySourceController extends Controller
{
    /**
     * Display all projects
     *
     */
    public function showProjects()
    {
        $projects = Project::getAll();
        return view('developers.bills.electricity.projects', compact('projects'));
    }

    /**
     * Display the project's electricity bills.
     *
     */
    public function showProjectBills(Project $project)
    {
        $electricity_bills = BillElectricitySource::getAllByProject($project);
        $unpaid_bills = BillElectricitySourceDetail::getUnpaidBills($project);
        return view('developers.bills.electricity.projects.project', compact('project', 'electricity_bills','unpaid_bills'));
    }

    /**
    * Add a electricity bill for a project.
    *
    */
    public function showAddProjectBill(Project $project)
    {
        $electricity_bill = new BillElectricitySource();
        return view('developers.bills.electricity.projects.add', compact('project','electricity_bill'));
    }

    /**
    * Save the electricity bill for the project.
    *
    */
    public function addProjectBill(Project $project, AddEditElectricityBillRequest $request)
    {
        $return = BillElectricitySource::createElectricityBill(new BillElectricitySource(), $project, $request);

        if($return["success"]){
            return redirect(route('bills_electricity_project', array($project->slug)))->withSuccess('electricity bill for <i>'.$request->get('date_covered').'</i> was successfully added');
        } else {
            return redirect(route('bills_electricity_project', array($project->slug)))->withDanger('electricity bill for <i>'.$request->get('date_covered').'</i> was unsuccessfully added');
        }
    }

    /**
    * Show the details of a project's electricity bill
    *
    */
    public function showProjectBill(Project $project, BillElectricitySource $electricity_bill)
    {   
        $electricity_bill = BillElectricitySource::getDetailedBill($electricity_bill);
        $electricity_bill_details = BillElectricitySourceDetail::getAllByProjectBill($electricity_bill);
        return view('developers.bills.electricity.projects.view', compact('project','electricity_bill','electricity_bill_details'));
    }

    /**
    * Show the form for editing a project's electricity bill.
    *
    */
    public function showEditProjectBill(Project $project, BillElectricitySource $electricity_bill)
    {
        return view('developers.bills.electricity.projects.edit', compact('project','electricity_bill'));
    }

    /**
    * Save the electricity bill for the project.
    *
    */
    public function editProjectBill(Project $project, BillElectricitySource $electricity_bill, AddEditElectricityBillRequest $request)
    {
        $return = BillElectricitySource::updateelectricityBill($electricity_bill, $project, $request);

        if($return["success"]){
            return redirect(route('view_bill_electricity_project', array($project->slug, $electricity_bill->date_covered)))->withSuccess('electricity bill for <i>'.$request->get('date_covered').'</i> was successfully added');
        } else {
            return redirect(route('view_bill_electricity_project', array($project->slug, $electricity_bill->date_covered)))->withDanger('electricity bill for <i>'.$request->get('date_covered').'</i> was unsuccessfully added');
        }
    }

    /**
    * Import the electricity bills of a project from an Excel file and save the data to the database.
    *
    */
    public function importProjectBillsFromExcel(Project $project)
    {
        if(Input::hasFile('bills_excel')){
            $path = Input::file('bills_excel')->getRealPath();
            $data = Excel::selectSheetsByIndex(0)->load($path, function($reader) {
                $reader->formatDates(false);
            })->get();
            
            $return = BillElectricitySource::importProjectBills($project, $data);

            if($return["success"]){
                return redirect(route('bills_electricity_project', array($project->slug)))->withSuccess($return['message']);
            } else {
                return redirect(route('bills_electricity_project', array($project->slug)))->withDanger($return['message']);
            }
        } else {
            return redirect(route('bills_electricity_project', array($project->slug)))->withDanger('electricity bills for <i>'.$project->name.'</i> was unsuccessfully imported');
        }
    }

    /**
    * Export the electricity bills to an excel file.
    *
    */
    public function exportProjectBillsToExcel(Project $project)
    {   
        $excel = BillElectricitySource::formatProjectBillsToExcelExport($project);
        $excel->export('xlsx');
    }

    /**
    * Export the ledger details to a PDF file.
    *
    */
    public function exportProjectBillsToPdf(Project $project)
    {
        $excel = BillElectricitySource::formatProjectBillsToPdfExport($project);
        $excel->export('pdf');
    }

    /**
    * Delete a projet's a electricity bill.
    *
    */
    public function deleteProjectBill(Project $project, BillElectricitySource $electricity_bill, Request $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = BillElectricitySource::deleteBill($project, $electricity_bill);
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
    * Show the form for adding a property's electricity bill.
    *
    */
    public function showEditPropertyBill(Project $project, Property $property, BillElectricitySourceDetail $electricity_bill_detail)
    {
        $electricity_bill = BillElectricitySource::whereId($electricity_bill_detail->bills_electricity_source_id)->first();
        return view('developers.bills.electricity.properties.edit', compact('project','property','electricity_bill_detail','electricity_bill'));
    }

    /**
    * Add a property's electricity bill.
    *
    */
    public function editPropertyBill(Project $project, Property $property, BillElectricitySourceDetail $electricity_bill_detail, AddEditelectricityBillRequest $request)
    {
        $return = BillElectricitySourceDetail::updateelectricityBill($property, $electricity_bill_detail, $request);

        if($return["success"]){
            return redirect(route('view_bill_electricity_project', array($project->slug,$electricity_bill_detail->date_covered)))->withSuccess('electricity bill for <i>'.$project->name.' '.$electricity_bill_detail->date_covered.'</i> was successfully updated');
        } else {
            return redirect(route('view_bill_electricity_project', array($project->slug,$electricity_bill_detail->date_covered)))->withDanger('electricity bill for <i>'.$project->name.' '.$electricity_bill_detail->date_covered.'</i> was unsuccessfully updated');
        }
    }

    /**
    * Import the electricity bills of a project from an Excel file and save the data to the database.
    *
    */
    public function importMonthlyBillsFromExcel(Project $project, BillElectricitySource $electricity_bill)
    {
        if(Input::hasFile('bills_excel')){
            $path = Input::file('bills_excel')->getRealPath();
            $data = Excel::selectSheetsByIndex(0)->load($path, function($reader) {
                $reader->formatDates(false);
            })->get();
            
            $return = BillElectricitySourceDetail::importMonthlyBills($project, $electricity_bill, $data);

            if($return["success"]){
                return redirect(route('view_bill_electricity_project', array($project->slug, $electricity_bill->date_covered)))->withSuccess($return['message']);
            } else {
                $message = ($return["message"] == ("" or null) ? 'electricity bills for <i>'.$project->name.'</i> was unsuccessfully imported' : $return["message"]);
                return redirect(route('view_bill_electricity_project', array($project->slug, $electricity_bill->date_covered)))->withDanger($message);
            }
        } else {
            return redirect(route('view_bill_electricity_project', array($project->slug, $electricity_bill->date_covered)))->withDanger('electricity bills for <i>'.$project->name.'</i> was unsuccessfully imported');
        }
    }

    /**
    * Export the electricity bills to an excel file.
    *
    */
    public function exportMonthlyBillsToExcel(Project $project, BillElectricitySource $electricity_bill)
    {   
        $excel = BillElectricitySourceDetail::formatMonthlyBillsToExcelExport($project, $electricity_bill);
        $excel->export('xlsx');
    }

    /**
    * Export the electricity bills to an excel file.
    *
    */
    public function exportMonthlyBillsToPdf(Project $project, BillElectricitySource $electricity_bill)
    {   
        $excel = BillElectricitySourceDetail::formatMonthlyBillsToPdfExport($project, $electricity_bill);
        $excel->export('pdf');
    }
}
