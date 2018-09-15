<?php

namespace App;

use App\Http\Requests\AddEditElectricityBillRequest;

use Illuminate\Database\Eloquent\Model;

use App\Property;
use Auth;
use DB;
use Excel;


class BillElectricitySourceDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bills_electricity_source_details';

    /**
    * Get all of the properties' bills of a project electricity bill.
    *
    */
    public static function getAllByProjectBill(BillElectricitySource $electricity_bill)
    {
        return BillElectricitySourceDetail::select(DB::raw('bills_electricity_source_details.* , properties.name as property, properties.slug as property_slug, properties.id, buyers.last_name, buyers.first_name'))
    		->leftJoin('properties','properties.id','=','bills_electricity_source_details.property_id')
    		->leftJoin('buyers','buyers.id','=','properties.buyer_id')
            ->whereRaw('bills_electricity_source_id = '.$electricity_bill->id.' and properties.id is not null and properties.buyer_id != 0')
            ->orderByRaw(DB::raw('cast(property as SIGNED) asc'))
            ->get();
    }

    /**
    * Delete properties' previous electricity bills when importing.
    *
    */
    public static function deleteForImporting(Project $project, $date_covered)
    {
    	if(BillElectricitySourceDetail::hasToDeleteForImporting($project, $date_covered)) {
            $deleted =  BillElectricitySourceDetail::leftJoin('properties','properties.id','=','bills_electricity_source_details.property_id')
            ->whereRaw('bills_electricity_source_details.date_covered = "'.$date_covered.'" and properties.project_id = '.$project->id)
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
        $electricity_bills = BillElectricitySourceDetail::leftJoin('properties','properties.id','=','bills_electricity_source_details.property_id')
        ->whereRaw('bills_electricity_source_details.date_covered = "'.$date_covered.'" and properties.project_id = '.$project->id)
        ->get();

        if(count($electricity_bills) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Update the property's electricity bill
    *
    */
    public static function updateElectricityBill(Property $property, BillElectricitySourceDetail $electricity_bill, AddEditElectricityBillRequest $request)
    {
    	DB::beginTransaction();

    	$electricity_bill->consumption = str_replace(',','',$request->get('consumption'));
    	$electricity_bill->bill = str_replace(',','',$request->get('bill'));
    	$electricity_bill->payment = str_replace(',','',$request->get('payment'));
    	$electricity_bill->date_payment = $request->get('date_payment');
    	$electricity_bill->remarks = $request->get('remarks');
    	$return["success"] = $electricity_bill->touch();

    	if($return["success"]) {
    		DB::commit();
    	} else {
    		DB::rollback();
    	}

    	return $return;
    }

    /**
    * Import the project's monthly electricity bills.
    *
    */
    public static function importMonthlyBills(Project $project, BillElectricitySource $electricity_bill, $data)
    {
        DB::beginTransaction();

        $electricity_bills = BillElectricitySourceDetail::getAllByProjectBill($electricity_bill);
        $electricity_bills_deleted = BillElectricitySourceDetail::whereBillsElectricitySourceId($electricity_bill->id)->delete();
        
        if(count($electricity_bills) == $electricity_bills_deleted){
            $electricity_bills_deleted = true;
        } else {
            $electricity_bills_deleted = false;
        }

        $data_count = 0;
        $counter = 0;
        $return["message"] = "";

        if($electricity_bills_deleted) {
            foreach($data as $datum) {
                $property = Property::whereName($datum->property)->first();
                if($property != null) {
                    $data_count++;

                    $electricity_bill_detail = new BillElectricitySourceDetail();
                    $electricity_bill_detail->property_id = $property->id;
                    $electricity_bill_detail->bills_electricity_source_id = $electricity_bill->id;
                    $electricity_bill_detail->date_covered = $datum->date_covered;
                    $electricity_bill_detail->consumption = str_replace(',','',$datum->consumption);
                    $electricity_bill_detail->bill = (($electricity_bill->bill / $electricity_bill->consumption) * config('constants.ELECTRICITY_SOURCE_PERCENTAGE')) * $electricity_bill_detail->consumption;
                    $electricity_bill_detail->payment = $datum->payment;
                    $electricity_bill_detail->date_payment = $datum->date_payment;
                    $electricity_bill_detail->remarks = $datum->remarks;
                    
                    if($electricity_bill_detail->touch()) {
                        $counter++;
                    }
                } else {
                    if($datum->property != null){
                        $return["message"] .= 'Property <i>'.$datum->property.'</i> not found';
                    }
                }
            }
        }

        //dd('electricity_bills_deleted: '.$electricity_bills_deleted.';data_count: '.$data_count.' ; counter: '.$counter);

        if($data_count == $counter) {
            $return["success"] = true;
            $return["message"] .= 'Electricity bills for <i>'.$project->name.'</i> were successfully imported';
            DB::commit();
        } else {
            $return["success"] = false;
            $return["message"] /= 'Electricity bills for <i>'.$project->name.'</i> were unsuccessfully imported';
            DB::rollback();
        }

        return $return;
    }

    /**
    * For the monthly electricity bills to export as an Excel file.
    *
    */
    public static function formatMonthlyBillsToExcelExport(Project $project, BillElectricitySource $electricity_bill)
    {
        return Excel::create($electricity_bill->date_covered.' '.$project->name, function($excel) use ($project, $electricity_bill) {
            $excel->setTitle($electricity_bill->date_covered.' '.$project->name);

            $developer = Developer::getCurrentDeveloper();
            $excel->setCompany($developer->name);

            $excel->setDescription("Water bills for ".$project->name.' '.$electricity_bill->date_covered);
            $excel->sheet($electricity_bill->date_covered, function($sheet) use ($project, $electricity_bill) {
                
                $sheet->row(1, array('PROPERTY','DATE COVERED','CONSUMPTION','BILL','PAYMENT','DATE','REMARKS'));

                $sheet->cells('A1:G1', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });

                $properties = Property::leftJoin('bills_electricity_source_details','bills_electricity_source_details.property_id','=','properties.id')
                ->whereRaw(DB::raw('bills_electricity_source_details.bills_electricity_source_id = '.$electricity_bill->id))
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
    public static function formatMonthlyBillsToPdfExport(Project $project, BillElectricitySource $electricity_bill)
    {
        return Excel::create($electricity_bill->date_covered.' '.$project->name, function($excel) use ($project, $electricity_bill) {
            $excel->setTitle($electricity_bill->date_covered.' '.$project->name);

            $developer = Developer::getCurrentDeveloper();
            $excel->setCompany($developer->name);

            $excel->setDescription("Electricity bills for ".$project->name.' '.$electricity_bill->date_covered);
            $excel->sheet($electricity_bill->date_covered, function($sheet) use ($project, $electricity_bill) {
                
                $properties = Property::leftJoin('bills_electricity_source_details','bills_electricity_source_details.property_id','=','properties.id')
                ->whereRaw(DB::raw('bills_electricity_source_details.bills_electricity_source_id = '.$electricity_bill->id))
                ->get();

                $sheet->setPageMargin(array(
                    0.25, 0.10, 0.25, 0.10
                ));

                $sheet->setBorder("A1:F".(count($properties)+2), 'none');
                
                $sheet->row(1, array($project->name.' '.$electricity_bill->date_covered, "","","","",""));
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
        return BillElectricitySourceDetail::selectRaw(DB::raw("sum(bills_electricity_source_details.bill) - sum(bills_electricity_source_details.payment) as amount, properties.name as property, CONCAT(buyers.first_name,' ',buyers.middle_name,' ',buyers.last_name) as buyer"))  
                ->leftJoin('bills_electricity_source','bills_electricity_source_details.bills_electricity_source_id','=','bills_electricity_source.id')
                ->leftJoin('projects','projects.id','=','bills_electricity_source.project_id')
                ->leftJoin('properties','properties.id','=','bills_electricity_source_details.property_id')
                ->leftJoin('buyers','buyers.id','=','properties.buyer_id')
                ->groupBy('bills_electricity_source_details.property_id')
                ->havingRaw('amount > 0')
                ->get(); 
    }
}
