<?php

namespace App;

use App\Http\Requests\AddEditHolidayRequest;

use Illuminate\Database\Eloquent\Model;

use App\Developer;

use DB;

class Holiday extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'holidays';

    /**
    * Get all of the holidays for the developer.
    *
    */
    public static function getAll()
    {
    	$developer = Developer::getCurrentDeveloper();
    	return Holiday::whereDeveloperId($developer->id)->get();
    }

    /**
    * Get the holiday object of the given date.
    *
    */
    public static function getHoliday($date)
    {
        $developer = Developer::getCurrentDeveloper();
        return Holiday::whereRaw(DB::raw('date = "'.$date.'" and developer_id = '.$developer->id))->first();
    }

    /**
    * Save or update a holiday.
    *
    */
    public static function addEditHoliday(Holiday $holiday, AddEditHolidayRequest $request)
    {
    	DB::beginTransaction();
    	$developer = Developer::getCurrentDeveloper();

    	try {
    		$holiday->developer_id = $developer->id;
    		$holiday->date = $request->get('date');
    		$holiday->name = $request->get('name');
    		$holiday->type = $request->get('type');

    		$return['success'] = $holiday->touch();

    		if($return['success']) {
    			DB::commit();
    		} else {
    			DB::rollback();
    		}
    	} catch(Exception $e) {
    		$return['success'] = false;
    		DB::rollback();
    	}

    	return $return;
    }

    /**
    * Delete a holiday.
    *
    */
    public static function deleteHoliday(Holiday $holiday)
    {
        DB::beginTransaction();

        try {
            $return['success'] = $holiday->delete();

            if($return['success']) {
                DB::commit();
            } else {
                DB::rollback();
            }
        } catch(Exception $e) {
            DB::rollback();
            $return['success'] = false;
        }

        return $return;
    }

    /**
    * Get the count of the holidays of the developer then delete them. If all of them are deleted, save
    * all of the holidays retrieved from the Google API.
    *
    */
    public static function syncHolidays($data)
    {
    	DB::beginTransaction();

    	$counter = 0;
    	try {
	    	$developer = Developer::getCurrentDeveloper();

	    	$holidays_count = Holiday::whereDeveloperId($developer->id)->count();
	    	$holidays_deleted = Holiday::whereDeveloperId($developer->id)->delete();

	    	if($holidays_deleted == $holidays_count){
		        foreach($data->items as $item) {
		            $new_holiday = new Holiday();
		            $new_holiday->developer_id = $developer->id;
		            $new_holiday->name = $item->summary;
		            $new_holiday->date = $item->start->date;
		            $new_holiday->type = config('constants.HOLIDAY_REGULAR');

		            if($new_holiday->touch()) {
		            	$counter++;
		            }
		        }

		        if($counter == count($data->items)) {
		        	DB::commit();
		        	$return['success'] = true;
		        } else {
		        	DB::rollback();
	    			$return['success'] = false;
		        }
	    	} else {
	    		DB::rollback();
	    		$return['success'] = false;
	    	}
    	} catch(Exception $e) {
    		DB::rollback();
    		$return['success'] = false;
    	}

    	return $return;
    }
}
