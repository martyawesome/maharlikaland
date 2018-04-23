<?php

namespace App;

use App\Http\Requests\AddEditJournalTypeRequest;

use Illuminate\Database\Eloquent\Model;


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
    	return JournalType::orderBy('type','asc')
        ->get();
    }

    /**
    * Get all the journal types of a developer for forms.
    *
    */
    public static function getJournalTypesList()
    {
        return JournalType::orderBy('type','asc')
        ->lists('type','id');
    }

    /**
    * Save a journal type.
    *
    */
    public static function addEditJournalType(JournalType $journal_type, AddEditJournalTypeRequest $request)
    {
    	DB::beginTransaction();

    	try {
    		$journal_type->type = $request->get('type');

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
