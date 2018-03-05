<?php

namespace App;

use App\Http\Requests\AddEditJournalTypeRequest;

use Illuminate\Database\Eloquent\Model;

use App\Developer;

use DB;

class JournalType extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'journal_types';

    /**
    * Get all the journal types of a developer.
    *
    */
    public static function getJournalTypes()
    {
    	$developer = Developer::getCurrentDeveloper();
    	return JournalType::whereDeveloperId($developer->id)
        ->orderBy('type','asc')
        ->get();
    }

    /**
    * Get all the journal types of a developer for forms.
    *
    */
    public static function getJournalTypesList()
    {
        $developer = Developer::getCurrentDeveloper();
        return JournalType::whereDeveloperId($developer->id)
        ->orderBy('type','asc')
        ->lists('type','id');
    }

    /**
    * Save a journal type.
    *
    */
    public static function addEditJournalType(JournalType $journal_type, AddEditJournalTypeRequest $request)
    {
    	DB::beginTransaction();

    	$developer = Developer::getCurrentDeveloper();
    	try {
    		$journal_type->type = $request->get('type');
    		$journal_type->developer_id = $developer->id;

    		$return['success'] = $journal_type->touch();

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
    * Delete a journal_type.
    *
    */
    public static function deleteJournalType(JournalType $journal_type)
    {
    	DB::beginTransaction();

    	$developer = Developer::getCurrentDeveloper();
    	try {
    		$return['success'] = JournalType::find($journal_type->id)->delete();

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

}
