<?php

namespace App;

use App\Http\Requests\AddEditElectricityBillRequest;
use Illuminate\Database\Eloquent\Model;

use App\Developer;
use App\Project;
use App\Property;
use App\BillElectricitySourceDetail;
use Auth;
use DB;
use Excel;

class BillElectricitySource extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bills_electricity_source';

    /**
    * Get all of the electricity bills of a project.
    *
    */
    public static function getAllByProject(Project $project)
    {
    	return BillElectricitySource::leftJoin(DB::raw('(select bills_electricity_source_id, sum(consumption) as details_consumption, sum(bill) as details_bill, sum(payment) as details_payment from bills_electricity_source_details group by bills_electricity_source_id) as b'),'b.bills_electricity_source_id','=','bills_electricity_source.id')
        ->get();
    }

    /**
    * Get all of the electricity bills of a project.
    *
    */
    public static function getDetailedBill(BillElectricitySource $electricity_bill)
    {
        return BillElectricitySource::leftJoin(DB::raw('(select bills_electricity_source_id, sum(consumption) as details_consumption, sum(bill) as details_bill, sum(payment) as details_payment from bills_electricity_source_details group by bills_electricity_source_id) as b'),'b.bills_electricity_source_id','=','bills_electricity_source.id')
        ->whereId($electricity_bill->id)
        ->first();
    }

    /**
    * Update the project's electricity bill
    *
    */
    public static function createElectricityBill(BillElectricitySource $electricity_bill, Project $project, AddEditElectricityBillRequest $request)
    {
    	DB::beginTransaction();

    	$electricity_bill->project_id = $project->id;
    	$electricity_bill->date_covered = $request->get('date_covered');
    	$electricity_bill->consumption = str_replace(',','',$request->get('consumption'));
    	$electricity_bill->bill = str_replace(',','',$request->get('bill'));
    	$electricity_bill->remarks = $request->get('remarks');
    	$success_project_electricity_bill = $electricity_bill->touch();

    	$properties = Property::getFromProject($project);

    	$counter = 0;
    	foreach($properties as $property){
    		$property_electricity_bill = new BillElectricitySourceDetail();
    		$property_electricity_bill->bills_electricity_source_id = $electricity_bill->id;
	    	$property_electricity_bill->property_id = $property->id;
	    	$property_electricity_bill->date_covered = $request->get('date_covered');

    		if($property_electricity_bill->touch()) {
    			$counter++;
    		}
    	}

    	if($success_project_electricity_bill  and $counter == count($properties)) {
    		$return["success"] = true;
    		DB::commit();
    	} else {
    		$return["success"] = false;
    		DB::rollback();
    	}

    	return $return;
    }

    /**
    * Update the project's electricity bill
    *
    */
    public static function updateElectricityBill(BillElectricitySource $electricity_bill, Project $project, AddEditElectricityBillRequest $request)
    {
    	DB::beginTransaction();

    	$electricity_bill->project_id = $project->id;
    	$electricity_bill->date_covered = $request->get('date_covered');
    	$electricity_bill->consumption = str_replace(',','',$request->get('consumption'));
    	$electricity_bill->bill = str_replace(',','',$request->get('bill'));
    	$electricity_bill->remarks = $request->get('remarks');
    	$success_electricity_bill = $electricity_bill->touch();

        $electricity_bill_details = BillElectricitySourceDetail::getAllByProjectBillWater($electricity_bill);

        $cost_per_consumption = $electricity_bill->bill / $electricity_bill->consumption;

        $counter = 0;
        foreach ($electricity_bill_details as $electricity_bill_detail) {
            $electricity_bill_detail->bill = $electricity_bill_detail->consumption * $cost_per_consumption;

            if($electricity_bill_detail->touch()) {
                $counter++;
            }
        }

    	if($success_electricity_bill and $counter == count($electricity_bill_details)) {
            $return["success"] = true;
    		DB::commit();
    	} else {
            $return["success"] = false;
    		DB::rollback();
    	}

    	return $return;
    }

    /**
    * Import the project's electricity bills.
    *
    */
    public static function importProjectBills(Project $project, $data)
    {
    	DB::beginTransaction();

        $project_bills_count = BillElectricitySource::whereProjectId($project->id)->get();
        /*if(count($project_bills_count) > 0){
            $project_bills_deleted = BillElectricitySource::whereProjectId($project->id)->delete();
        } else {
            $project_bills_deleted = true;
        }*/
    	
    	$data_count = 0;
    	$counter = 0;
        $return["message"] = "";

        foreach($data as $datum) {
            if($datum->date_covered != null and $datum->consumption != null){
                $data_count++;
                /*if(BillElectricitySourceDetail::hasToDeleteForImporting($project, $datum->date_covered)) {
                    $property_bills_deleted = BillElectricitySourceDetail::deleteForImporting($project, $datum->date_covered);
                } else {
                    $property_bills_deleted =  true;
                }*/

                $electricity_bill = BillElectricitySource::whereRaw('project_id = '.$project->id.' and date_covered = "'.$datum->date_covered.'"')->first();
        		
                // Skip existing electricity bills
                if($electricity_bill){
                    $counter++;
                    $return["message"] = "Duplicate months will not be updated.";
                    continue;
                }

                $electricity_bill = new BillElectricitySource();
        		$electricity_bill->project_id = $project->id;
    	    	$electricity_bill->date_covered = $datum->date_covered;
    	    	$electricity_bill->consumption = str_replace(',','',$datum->consumption);
    	    	$electricity_bill->bill = str_replace(',','',$datum->bill);
    	    	$electricity_bill->remarks = $datum->remarks;
    	    	$success_project_electricity_bill = $electricity_bill->touch();

                //if($success_project_electricity_bill and $property_bills_deleted) {
                if($success_project_electricity_bill) {
        	    	$properties = Property::getFromProject($project);

        	    	$properties_counter = 0;
        	    	foreach($properties as $property){
        	    		$property_electricity_bill = new BillElectricitySourceDetail();
        	    		$property_electricity_bill->bills_electricity_source_id = $electricity_bill->id;
        		    	$property_electricity_bill->property_id = $property->id;
        		    	$property_electricity_bill->date_covered = $datum->date_covered;
                        $property_electricity_bill->payment = 0;

        	    		if($property_electricity_bill->touch()) {
        	    			$properties_counter++;
        	    		}
        	    	}

        	    	if($properties_counter == count($properties)) {
        				$counter++;
        	    	}
                }
            }

    	}

        //dd('project_bills_deleted: '.$project_bills_deleted.' ; property_bills_deleted : '.$property_bills_deleted.' ; data_count: '.$data_count.' ; counter: '.$counter);

        if($data_count == $counter) {
        //if($project_bills_deleted and $property_bills_deleted and $data_count == $counter) {
    		$return["success"] = true;
            $return["message"] .= "Water bills for <i>$project->name</i> were successfully imported";
    		DB::commit();
    	} else {
			$return["success"] = false;
            $return["message"] .= "Water bills for <i>$project->name</i> were unsuccessfully imported";
    		DB::rollback();
    	}

    	return $return;
    }

    /**
    * For the water bills to export as an Excel file.
    *
    */
    public static function formatProjectBillsToExcelExport(Project $project)
    {
    	return Excel::create($project->name, function($excel) use ($project) {
            $excel->setTitle($project->name);

            $developer = Developer::getCurrentDeveloper();
            $excel->setCompany($developer->name);

            $excel->setDescription("Water bills for ".$project->name);
            $excel->sheet($project->name, function($sheet) use ($project) {
                
                $sheet->row(1, array('DATE COVERED','CONSUMPTION','BILL','REMARKS'));

                $sheet->cells('A1:D1', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });

                $electricity_bills = BillElectricitySource::getAllByProject($project);
                $start = 2;
                for($i=$start;$i<count($electricity_bills)+$start;$i++){
                    $sheet->row($i, array(
                    	$electricity_bills[$i-$start]->date_covered,
                        number_format($electricity_bills[$i-$start]->consumption, 4, '.', ','),
                        number_format($electricity_bills[$i-$start]->bill, 4, '.', ','),
                        $electricity_bills[$i-$start]->remarks));

                    $sheet->cells('A'.$i.':D'.$i, function($cells){
                        $cells->setAlignment('center');
                    });
                }
            });
        });
    }

    /**
    * For the water bills to export as an PDF file.
    *
    */
    public static function formatProjectBillsToPdfExport(Project $project)
    {
        return Excel::create($project->name, function($excel) use ($project) {
            $excel->setTitle($project->name);

            $developer = Developer::getCurrentDeveloper();
            $excel->setCompany($developer->name);

            $excel->setDescription("Electricity bills for ".$project->name);
            $excel->sheet($project->name, function($sheet) use ($project) {
                
                $sheet->row(1, array($project->name));
                $sheet->row(2, array('DATE COVERED','CONSUMPTION','BILL','REMARKS'));

                $sheet->cells('A2:D2', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });

                $electricity_bills = BillElectricitySource::getAllByProject($project);
                $start = 3;
                for($i=$start;$i<count($electricity_bills)+$start;$i++){
                    $sheet->row($i, array(
                        $electricity_bills[$i-$start]->date_covered,
                        number_format($electricity_bills[$i-$start]->consumption, 4, '.', ','),
                        number_format($electricity_bills[$i-$start]->bill, 4, '.', ','),
                        $electricity_bills[$i-$start]->remarks));

                    $sheet->cells('A'.$i.':D'.$i, function($cells){
                        $cells->setAlignment('center');
                    });
                }
            });
        });
    }

    /**
    * Delete a project's water bill.
    *
    */
    public static function deleteBill(Project $project, BillElectricitySource $electricity_bill)
    {
        DB::beginTransaction();

        $electricity_bill_details_deleted = BillElectricitySourceDetail::deleteForImporting($project, $electricity_bill->date_covered);
        $electricity_bill_deleted = BillElectricitySource::whereId($electricity_bill->id)->delete();

        if($electricity_bill_details_deleted and $electricity_bill_deleted) {
            $return["success"] = true;
            DB::commit();
        } else {
            $return["success"] = false;
            DB::rollback();
        }

        return $return;
    }
}
