<?php

namespace App;

use App\Http\Requests\AddEditWaterBillRequest;

use Illuminate\Database\Eloquent\Model;

use App\Developer;
use App\Project;
use App\Property;
use App\BillWaterSourceDetail;
use Auth;
use DB;
use Excel;

class BillWaterSource extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bills_water_source';

    /**
    * Get all of the bills of a project.
    *
    */
    public static function getAllByProject(Project $project)
    {
    	return BillWaterSource::leftJoin(DB::raw('(select bills_water_source_id, sum(consumption) as details_consumption, sum(bill) as details_bill, sum(payment) as details_payment from bills_water_source_details group by bills_water_source_id) as b'),'b.bills_water_source_id','=','bills_water_source.id')
        ->get();
    }

    /**
    * Get all of the bills of a project.
    *
    */
    public static function getDetailedBill(BillWaterSource $water_bill)
    {
        return BillWaterSource::leftJoin(DB::raw('(select bills_water_source_id, sum(consumption) as details_consumption, sum(bill) as details_bill, sum(payment) as details_payment from bills_water_source_details group by bills_water_source_id) as b'),'b.bills_water_source_id','=','bills_water_source.id')
        ->whereId($water_bill->id)
        ->first();
    }

    /**
    * Update the project's water bill
    *
    */
    public static function createWaterBill(BillWaterSource $water_bill, Project $project, AddEditWaterBillRequest $request)
    {
    	DB::beginTransaction();

    	$water_bill->project_id = $project->id;
    	$water_bill->date_covered = $request->get('date_covered');
    	$water_bill->consumption = str_replace(',','',$request->get('consumption'));
    	$water_bill->bill = str_replace(',','',$request->get('bill'));
    	$water_bill->remarks = $request->get('remarks');
    	$success_project_water_bill = $water_bill->touch();

    	$properties = Property::getFromProject($project);

    	$counter = 0;
    	foreach($properties as $property){
    		$property_water_bill = new BillWaterSourceDetail();
    		$property_water_bill->bills_water_source_id = $water_bill->id;
	    	$property_water_bill->property_id = $property->id;
	    	$property_water_bill->date_covered = $request->get('date_covered');

    		if($property_water_bill->touch()) {
    			$counter++;
    		}
    	}

    	if($success_project_water_bill  and $counter == count($properties)) {
    		$return["success"] = true;
    		DB::commit();
    	} else {
    		$return["success"] = false;
    		DB::rollback();
    	}

    	return $return;
    }

    /**
    * Update the project's water bill
    *
    */
    public static function updateWaterBill(BillWaterSource $water_bill, Project $project, AddEditWaterBillRequest $request)
    {
    	DB::beginTransaction();

    	$water_bill->project_id = $project->id;
    	$water_bill->date_covered = $request->get('date_covered');
    	$water_bill->consumption = str_replace(',','',$request->get('consumption'));
    	$water_bill->bill = str_replace(',','',$request->get('bill'));
    	$water_bill->remarks = $request->get('remarks');
    	$success_water_bill = $water_bill->touch();

        $water_bill_details = BillWaterSourceDetail::getAllByProjectBillWater($water_bill);

        $cost_per_consumption = $water_bill->bill / $water_bill->consumption;

        $counter = 0;
        foreach ($water_bill_details as $water_bill_detail) {
            $water_bill_detail->bill = $water_bill_detail->consumption * $cost_per_consumption;

            if($water_bill_detail->touch()) {
                $counter++;
            }
        }

    	if($success_water_bill and $counter == count($water_bill_details)) {
            $return["success"] = true;
    		DB::commit();
    	} else {
            $return["success"] = false;
    		DB::rollback();
    	}

    	return $return;
    }

    /**
    * Import the project's water bills.
    *
    */
    public static function importProjectBills(Project $project, $data)
    {
    	DB::beginTransaction();

        $project_bills_count = BillWaterSource::whereProjectId($project->id)->get();
        /*if(count($project_bills_count) > 0){
            $project_bills_deleted = BillWaterSource::whereProjectId($project->id)->delete();
        } else {
            $project_bills_deleted = true;
        }*/
    	
    	$data_count = 0;
    	$counter = 0;
        $return["message"] = "";

        foreach($data as $datum) {
            if($datum->date_covered != null and $datum->consumption != null){
                $data_count++;
                /*if(BillWaterSourceDetail::hasToDeleteForImporting($project, $datum->date_covered)) {
                    $property_bills_deleted = BillWaterSourceDetail::deleteForImporting($project, $datum->date_covered);
                } else {
                    $property_bills_deleted =  true;
                }*/

                $water_bill = BillWaterSource::whereRaw('project_id = '.$project->id.' and date_covered = "'.$datum->date_covered.'"')->first();
        		
                // Skip existing water bills
                if($water_bill){
                    $counter++;
                    $return["message"] = "Duplicate months will not be updated. ";
                    continue;
                }

                $water_bill = new BillWaterSource();
        		$water_bill->project_id = $project->id;
    	    	$water_bill->date_covered = $datum->date_covered;
    	    	$water_bill->consumption = str_replace(',','',$datum->consumption);
    	    	$water_bill->bill = str_replace(',','',$datum->bill);
    	    	$water_bill->remarks = $datum->remarks;
    	    	$success_project_water_bill = $water_bill->touch();

                //if($success_project_water_bill and $property_bills_deleted) {
                if($success_project_water_bill) {
        	    	$properties = Property::getFromProject($project);

        	    	$properties_counter = 0;
        	    	foreach($properties as $property){
        	    		$property_water_bill = new BillWaterSourceDetail();
        	    		$property_water_bill->bills_water_source_id = $water_bill->id;
        		    	$property_water_bill->property_id = $property->id;
        		    	$property_water_bill->date_covered = $datum->date_covered;
                        $property_water_bill->payment = 0;

        	    		if($property_water_bill->touch()) {
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

                $water_bills = BillWaterSource::getAllByProject($project);
                $start = 2;
                for($i=$start;$i<count($water_bills)+$start;$i++){
                    $sheet->row($i, array(
                    	$water_bills[$i-$start]->date_covered,
                        number_format($water_bills[$i-$start]->consumption, 4, '.', ','),
                        number_format($water_bills[$i-$start]->bill, 4, '.', ','),
                        $water_bills[$i-$start]->remarks));

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

            $excel->setDescription("Water bills for ".$project->name);
            $excel->sheet($project->name, function($sheet) use ($project) {
                
                $sheet->row(1, array($project->name));
                $sheet->row(2, array('DATE COVERED','CONSUMPTION','BILL','REMARKS'));

                $sheet->cells('A2:D2', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });

                $water_bills = BillWaterSource::getAllByProject($project);
                $start = 3;
                for($i=$start;$i<count($water_bills)+$start;$i++){
                    $sheet->row($i, array(
                        $water_bills[$i-$start]->date_covered,
                        number_format($water_bills[$i-$start]->consumption, 4, '.', ','),
                        number_format($water_bills[$i-$start]->bill, 4, '.', ','),
                        $water_bills[$i-$start]->remarks));

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
    public static function deleteBill(Project $project, BillWaterSource $water_bill)
    {
        DB::beginTransaction();

        $water_bill_details_deleted = BillWaterSourceDetail::deleteForImporting($project, $water_bill->zdate_covered);
        $water_bill_deleted = BillWaterSource::whereId($water_bill->id)->delete();

        if($water_bill_details_deleted and $water_bill_deleted) {
            $return["success"] = true;
            DB::commit();
        } else {
            $return["success"] = false;
            DB::rollback();
        }

        return $return;
    }

}
