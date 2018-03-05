<?php

namespace App;

use App\Http\Requests\AddEditWaterBillRequest;

use Illuminate\Database\Eloquent\Model;

use App\Property;
use Auth;
use DB;
use Excel;

class BillWaterSourceDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bills_water_source_details';

    /**
    * Get all of the properties' bills of a project water bill.
    *
    */
    public static function getAllByProjectBillWater(BillWaterSource $water_bill)
    {
        return BillWaterSourceDetail::select(DB::raw('bills_water_source_details.* , properties.name as property, properties.slug as property_slug, properties.id'))
    		->leftJoin('properties','properties.id','=','bills_water_source_details.property_id')
    		->whereRaw('bills_water_source_id = '.$water_bill->id.' and properties.id is not null')
            ->orderByRaw(DB::raw('cast(property as SIGNED) asc'))
            ->get();
    }

    /**
    * Delete properties' previous water bills when importing.
    *
    */
    public static function deleteForImporting(Project $project, $date_covered)
    {
    	if(BillWaterSourceDetail::hasToDeleteForImporting($project, $date_covered)) {
            $deleted =  BillWaterSourceDetail::leftJoin('properties','properties.id','=','bills_water_source_details.property_id')
            ->whereRaw('bills_water_source_details.date_covered = "'.$date_covered.'" and properties.project_id = '.$project->id)
            ->delete(); 

            if($deleted) {
                return true;
            } else {
                return false;
            }
        } else {
            $property_bills_deleted =  true;
        }
    }

    /**
    * Delete properties' previous water bills when importing.
    *
    */
    public static function hasToDeleteForImporting(Project $project, $date_covered)
    {
        $water_bills = BillWaterSourceDetail::leftJoin('properties','properties.id','=','bills_water_source_details.property_id')
        ->whereRaw('bills_water_source_details.date_covered = "'.$date_covered.'" and properties.project_id = '.$project->id)
        ->get();

        if(count($water_bills) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Update the property's water bill
    *
    */
    public static function updateWaterBill(Property $property, BillWaterSourceDetail $water_bill, AddEditWaterBillRequest $request)
    {
    	DB::beginTransaction();

    	$water_bill->consumption = str_replace(',','',$request->get('consumption'));
    	$water_bill->bill = str_replace(',','',$request->get('bill'));
    	$water_bill->payment = str_replace(',','',$request->get('payment'));
    	$water_bill->date_payment = $request->get('date_payment');
    	$water_bill->remarks = $request->get('remarks');
    	$return["success"] = $water_bill->touch();

    	if($return["success"]) {
    		DB::commit();
    	} else {
    		DB::rollback();
    	}

    	return $return;
    }

    /**
    * Import the project's monthly water bills.
    *
    */
    public static function importMonthlyBills(Project $project, BillWaterSource $water_bill, $data)
    {
        DB::beginTransaction();

        $water_bills = BillWaterSourceDetail::getAllByProjectBillWater($water_bill);
        $water_bills_deleted = BillWaterSourceDetail::whereBillsWaterSourceId($water_bill->id)->delete();
        
        if(count($water_bills) == $water_bills_deleted){
            $water_bills_deleted = true;
        } else {
            $water_bills_deleted = false;
        }

        $data_count = 0;
        $counter = 0;
        $return["message"] = "";

        if($water_bills_deleted) {
            foreach($data as $datum) {
                $property = Property::whereName($datum->property)->first();
                if($property != null) {
                    $data_count++;

                    $water_bill_detail = new BillWaterSourceDetail();
                    $water_bill_detail->property_id = $property->id;
                    $water_bill_detail->bills_water_source_id = $water_bill->id;
                    $water_bill_detail->date_covered = $datum->date_covered;
                    $water_bill_detail->consumption = str_replace(',','',$datum->consumption);
                    $water_bill_detail->bill = (($water_bill->bill / $water_bill->consumption) * config('constants.WATER_SOURCE_PERCENTAGE')) * $water_bill_detail->consumption;
                    $water_bill_detail->payment = $datum->payment;
                    $water_bill_detail->date_payment = $datum->date_payment;
                    $water_bill_detail->remarks = $datum->remarks;
                    
                    if($water_bill_detail->touch()) {
                        $counter++;
                    }
                } else {
                    if($datum->property != null){
                        $return["message"] .= 'Property <i>'.$datum->property.'</i> not found';
                    }
                }
            }
        }

        //dd('water_bills_deleted: '.$water_bills_deleted.';data_count: '.$data_count.' ; counter: '.$counter);

        if($data_count == $counter) {
            $return["success"] = true;
            $return["message"] .= 'Water bills for <i>'.$project->name.'</i> were successfully imported';
            DB::commit();
        } else {
            $return["success"] = false;
            $return["message"] /= 'Water bills for <i>'.$project->name.'</i> were unsuccessfully imported';
            DB::rollback();
        }

        return $return;
    }

    /**
    * For the monthly water bills to export as an Excel file.
    *
    */
    public static function formatMonthlyBillsToExcelExport(Project $project, BillWaterSource $water_bill)
    {
        return Excel::create($water_bill->date_covered.' '.$project->name, function($excel) use ($project, $water_bill) {
            $excel->setTitle($water_bill->date_covered.' '.$project->name);

            $developer = Developer::find(Auth::user()->developer_id);
            $excel->setCompany($developer->name);

            $excel->setDescription("Water bills for ".$project->name.' '.$water_bill->date_covered);
            $excel->sheet($water_bill->date_covered, function($sheet) use ($project, $water_bill) {
                
                $sheet->row(1, array('PROPERTY','DATE COVERED','CONSUMPTION','BILL','PAYMENT','DATE','REMARKS'));

                $sheet->cells('A1:G1', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });

                $properties = Property::leftJoin('bills_water_source_details','bills_water_source_details.property_id','=','properties.id')
                ->whereRaw(DB::raw('bills_water_source_details.bills_water_source_id = '.$water_bill->id))
                ->get();

                $start = 2;
                for($i=$start;$i<count($properties)+$start;$i++){
                    $sheet->row($i, array(
                        $properties[$i-$start]->name,
                        $properties[$i-$start]->date_covered,
                        number_format($properties[$i-$start]->consumption, 4, '.', ','),
                        number_format($properties[$i-$start]->bill, 4, '.', ','),
                        number_format($properties[$i-$start]->payment, 4, '.', ','),
                        ($properties[$i-$start]->date_payment != '0000-00-00') ? $properties[$i-$start]->date_payment : "",
                        $properties[$i-$start]->remarks));

                    $sheet->cells('A'.$i.':G'.$i, function($cells){
                        $cells->setAlignment('center');
                    });
                }
            });
        });
    }

    /**
    * For the monthly water bills to export as an Excel file.
    *
    */
    public static function formatMonthlyBillsToPdfExport(Project $project, BillWaterSource $water_bill)
    {
        return Excel::create($water_bill->date_covered.' '.$project->name, function($excel) use ($project, $water_bill) {
            $excel->setTitle($water_bill->date_covered.' '.$project->name);

            $developer = Developer::find(Auth::user()->developer_id);
            $excel->setCompany($developer->name);

            $excel->setDescription("Water bills for ".$project->name.' '.$water_bill->date_covered);
            $excel->sheet($water_bill->date_covered, function($sheet) use ($project, $water_bill) {
                
                $properties = Property::leftJoin('bills_water_source_details','bills_water_source_details.property_id','=','properties.id')
                ->whereRaw(DB::raw('bills_water_source_details.bills_water_source_id = '.$water_bill->id))
                ->get();

                $sheet->setPageMargin(array(
                    0.25, 0.10, 0.25, 0.10
                ));

                $sheet->setBorder("A1:F".(count($properties)+2), 'none');
                
                $sheet->row(1, array($project->name.' '.$water_bill->date_covered, "","","","",""));
                $sheet->row(2, array('PROPERTY','BILL','PAYMENT','DATE','REMARKS'));

                $sheet->cells('A2:E2', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });

                $start = 3;
                for($i=$start;$i<count($properties)+$start;$i++){
                    $sheet->row($i, array(
                        $properties[$i-$start]->name,
                        number_format($properties[$i-$start]->bill, 4, '.', ','),
                        number_format($properties[$i-$start]->payment, 4, '.', ','),
                        ($properties[$i-$start]->date_payment != '0000-00-00') ? $properties[$i-$start]->date_payment : "",
                        $properties[$i-$start]->remarks));

                    $sheet->cells('A'.$i.':E'.$i, function($cells){
                        $cells->setAlignment('center');
                    });
                }
            });
        });
    }

    /**
    * Get all the unpaid bills.
    *
    */
    public static function getUnpaidBills(Project $project)
    {
        $developer = Developer::getCurrentDeveloper();
        return BillWaterSourceDetail::selectRaw(DB::raw("sum(bills_water_source_details.bill) - sum(bills_water_source_details.payment) as amount, properties.name as property, CONCAT(buyers.first_name,' ',buyers.middle_name,' ',buyers.last_name) as buyer"))  
                ->leftJoin('bills_water_source','bills_water_source_details.bills_water_source_id','=','bills_water_source.id')
                ->leftJoin('projects','projects.id','=','bills_water_source.project_id')
                ->leftJoin('properties','properties.id','=','bills_water_source_details.property_id')
                ->leftJoin('buyers','buyers.id','=','properties.buyer_id')
                ->whereRaw('projects.developer_id = 1')
                ->groupBy('bills_water_source_details.property_id')
                ->havingRaw('amount > 0')
                ->get(); 
    }

}
