<?php

namespace App\Http\Controllers\Developers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\AddEditWaterBillRequest;

use App\Project;
use App\BillWaterSource;
use App\BillWaterSourceDetail;
use App\Property;
use App\Developer;
use Hash;
use Input;
use Excel;

class BillsWaterSourceController extends Controller
{
	/**
     * Display all projects
     *
     */
    public function showProjects()
    {
    	$projects = Project::getAll();
        return view('developers.bills.water.projects', compact('projects'));
    }

    /**
     * Display the project's water bills.
     *
     */
    public function showProjectBills(Project $project)
    {
    	$water_bills = BillWaterSource::getAllByProject($project);
        $unpaid_bills = BillWaterSourceDetail::getUnpaidBills($project);
        return view('developers.bills.water.projects.project', compact('project', 'water_bills','unpaid_bills'));
    }

    /**
    * Add a water bill for a project.
    *
    */
    public function showAddProjectBill(Project $project)
    {
    	$water_bill = new BillWaterSource();
    	return view('developers.bills.water.projects.add', compact('project','water_bill'));
    }

    /**
    * Save the water bill for the project.
    *
    */
    public function addProjectBill(Project $project, AddEditWaterBillRequest $request)
    {
    	$return = BillWaterSource::createWaterBill(new BillWaterSource(), $project, $request);

    	if($return["success"]){
            return redirect(route('bills_water_project', array($project->slug)))->withSuccess('Water bill for <i>'.$request->get('date_covered').'</i> was successfully added');
        } else {
            return redirect(route('bills_water_project', array($project->slug)))->withDanger('Water bill for <i>'.$request->get('date_covered').'</i> was unsuccessfully added');
        }
    }

    /**
    * Show the details of a project's water bill
    *
    */
    public function showProjectBill(Project $project, BillWaterSource $water_bill)
    {	
        $water_bill = BillWaterSource::getDetailedBill($water_bill);
    	$water_bill_details = BillWaterSourceDetail::getAllByProjectBillWater($water_bill);
        return view('developers.bills.water.projects.view', compact('project','water_bill','water_bill_details'));
    }

    /**
    * Show the form for editing a project's water bill.
    *
    */
    public function showEditProjectBill(Project $project, BillWaterSource $water_bill)
    {
    	return view('developers.bills.water.projects.edit', compact('project','water_bill'));
    }

    /**
    * Save the water bill for the project.
    *
    */
    public function editProjectBill(Project $project, BillWaterSource $water_bill, AddEditWaterBillRequest $request)
    {
    	$return = BillWaterSource::updateWaterBill($water_bill, $project, $request);

    	if($return["success"]){
            return redirect(route('view_bill_water_project', array($project->slug, $water_bill->date_covered)))->withSuccess('Water bill for <i>'.$request->get('date_covered').'</i> was successfully added');
        } else {
            return redirect(route('view_bill_water_project', array($project->slug, $water_bill->date_covered)))->withDanger('Water bill for <i>'.$request->get('date_covered').'</i> was unsuccessfully added');
        }
    }

    /**
    * Import the water bills of a project from an Excel file and save the data to the database.
    *
    */
    public function importProjectBillsFromExcel(Project $project)
    {
        if(Input::hasFile('bills_excel')){
            $path = Input::file('bills_excel')->getRealPath();
            $data = Excel::selectSheetsByIndex(0)->load($path, function($reader) {
                $reader->formatDates(false);
            })->get();
            
            $return = BillWaterSource::importProjectBills($project, $data);

            if($return["success"]){
                return redirect(route('bills_water_project', array($project->slug)))->withSuccess($return['message']);
            } else {
                return redirect(route('bills_water_project', array($project->slug)))->withDanger($return['message']);
            }
        } else {
            return redirect(route('bills_water_project', array($project->slug)))->withDanger('Water bills for <i>'.$project->name.'</i> was unsuccessfully imported');
        }
    }

    /**
    * Export the water bills to an excel file.
    *
    */
    public function exportProjectBillsToExcel(Project $project)
    {	
    	$excel = BillWaterSource::formatProjectBillsToExcelExport($project);
        $excel->export('xlsx');
    }

    /**
    * Export the ledger details to a PDF file.
    *
    */
    public function exportProjectBillsToPdf(Project $project)
    {
        $excel = BillWaterSource::formatProjectBillsToPdfExport($project);
        $excel->export('pdf');
    }

    /**
    * Delete a projet's a water bill.
    *
    */
    public function deleteProjectBill(Project $project, BillWaterSource $water_bill, Request $request)
    {
        $developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = BillWaterSource::deleteBill($project, $water_bill);
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
    * Show the form for adding a property's water bill.
    *
    */
    public function showEditPropertyBill(Project $project, Property $property, BillWaterSourceDetail $water_bill_detail)
    {
        $water_bill = BillWaterSource::whereId($water_bill_detail->bills_water_source_id)->first();
    	return view('developers.bills.water.properties.edit', compact('project','property','water_bill_detail','water_bill'));
    }

    /**
    * Add a property's water bill.
    *
    */
    public function editPropertyBill(Project $project, Property $property, BillWaterSourceDetail $water_bill_detail, AddEditWaterBillRequest $request)
    {
        $return = BillWaterSourceDetail::updateWaterBill($property, $water_bill_detail, $request);

        if($return["success"]){
            return redirect(route('view_bill_water_project', array($project->slug,$water_bill_detail->date_covered)))->withSuccess('Water bill for <i>'.$project->name.' '.$water_bill_detail->date_covered.'</i> was successfully updated');
        } else {
            return redirect(route('view_bill_water_project', array($project->slug,$water_bill_detail->date_covered)))->withDanger('Water bill for <i>'.$project->name.' '.$water_bill_detail->date_covered.'</i> was unsuccessfully updated');
        }
    }

    /**
    * Import the water bills of a project from an Excel file and save the data to the database.
    *
    */
    public function importMonthlyBillsFromExcel(Project $project, BillWaterSource $water_bill)
    {
        if(Input::hasFile('bills_excel')){
            $path = Input::file('bills_excel')->getRealPath();
            $data = Excel::selectSheetsByIndex(0)->load($path, function($reader) {
                $reader->formatDates(false);
            })->get();
            
            $return = BillWaterSourceDetail::importMonthlyBills($project, $water_bill, $data);

            if($return["success"]){
                return redirect(route('view_bill_water_project', array($project->slug, $water_bill->date_covered)))->withSuccess($return['message']);
            } else {
                $message = ($return["message"] == ("" or null) ? 'Water bills for <i>'.$project->name.'</i> was unsuccessfully imported' : $return["message"]);
                return redirect(route('view_bill_water_project', array($project->slug, $water_bill->date_covered)))->withDanger($message);
            }
        } else {
            return redirect(route('view_bill_water_project', array($project->slug, $water_bill->date_covered)))->withDanger('Water bills for <i>'.$project->name.'</i> was unsuccessfully imported');
        }
    }

    /**
    * Export the water bills to an excel file.
    *
    */
    public function exportMonthlyBillsToExcel(Project $project, BillWaterSource $water_bill)
    {   
        $excel = BillWaterSourceDetail::formatMonthlyBillsToExcelExport($project, $water_bill);
        $excel->export('xlsx');
    }

    /**
    * Export the water bills to an excel file.
    *
    */
    public function exportMonthlyBillsToPdf(Project $project, BillWaterSource $water_bill)
    {   
        $excel = BillWaterSourceDetail::formatMonthlyBillsToPdfExport($project, $water_bill);
        $excel->export('pdf');
    }

}
