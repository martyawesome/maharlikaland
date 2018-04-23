<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Http\Requests\AddEditJournalRequest;

use App\User;

use DB;
use Auth;

class Journal extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'journals';

    /**
    * Get all of the journals
    *
    */
    public static function getAll()
    {
        return Journal::selectRaw(DB::raw("journal_types.type, journals.id ,journals.date, journals.date,
                         journals.entry, , users.id, CONCAT(users.first_name,' ',users.middle_name,' ',users.last_name) as user_name"))
                        ->leftJoin('journal_types','journals.journal_type_id','=','journal_types.id')
                        ->leftJoin('users','journals.user_id','=','users.id')
                        ->get();
    }

    /**
    * Get all of the journals of the current user.
    *
    */
    public static function getAllOfUser()
    {
        return Journal::selectRaw(DB::raw("journal_types.type, journals.id ,journals.date, journals.date,journals.entry"))
                        ->leftJoin('journal_types','journals.journal_type_id','=','journal_types.id')
                        ->whereRaw(DB::raw('journals.user_id = '.Auth::user()->id))
                        ->get();
    }


    /**
    * Get all of the journal on the current date.
    *
    */
    public static function getJournalsOnDayCurrentUser($date)
    {
    	return Journal::selectRaw(DB::raw('journal_types.type, journals.id ,journals.date, journals.date, journals.entry'))
				    	->leftJoin('journal_types','journals.journal_type_id','=','journal_types.id')
				    	->whereRaw(DB::raw('journals.date = "'.$date.'" and journals.user_id = '.Auth::user()->id))
				    	->get();
    }

    /**
    * Add or edit a journal.
    *
    */
    public static function addEditJournal(Journal $journal, AddEditJournalRequest $request)
    {
    	DB::beginTransaction();
    	
    	try {
    		$journal->date = $request->get('date');
    		$journal->journal_type_id = $request->get('type');
    		$journal->entry = $request->get('entry');
    		$journal->user_id = Auth::user()->id;

    		$return['success'] = $journal->touch();

    		if($return['success']) {
    			DB::commit();
    		} else {
    			DB::rollback();
    		}
    	} catch(Excepton $e) {
    		DB::rollback();
    		$return['success'] = false;	
    	}

    	return $return;
    }

    /**
    * Delete a journal_type.
    *
    */
    public static function deleteJournal(Journal $journal)
    {
        DB::beginTransaction();

        try {
            $return['success'] = Journal::find($journal->id)->delete();

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
