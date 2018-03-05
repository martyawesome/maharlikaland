<?php

namespace App\Http\Controllers\Developers;

use Illuminate\Http\Request;
use App\Http\Requests\AddEditJournalTypeRequest;
use App\Http\Requests\AddEditJournalRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Journal;
use App\JournalType;
use App\Developer;

use Hash;
use DateTime;
use Auth;

class JournalsController extends Controller
{
	///////////////////////////////////////// Journals Types ///////////////////////////////////////////

    /**
    * Show all journal types.
    *
    */ 
    public function showJournalTypes()
    {
    	$journal_types = JournalType::getJournalTypes();
    	return view('developers.journals.journal_types.all', compact('journal_types'));
    }

    /**
    * Show form for adding journal types.
    *
    */ 
    public function showAddJournalType()
    {
    	$journal_type = new JournalType();
    	return view('developers.journals.journal_types.add', compact('journal_type'));
    }

    /**
    * Add journal types.
    *
    */ 
    public function addJournalType(AddEditJournalTypeRequest $request)
    {
    	$return = JournalType::addEditJournalType(new JournalType(), $request);

    	if($return["success"]) {
            return redirect(route('journal_types'))->withSuccess('Journal type <i>'.$request->get('type').' </i> was successfully edited');
        } else {
            return redirect(route('add_journal_type'))->withDanger('Journal type <i>'.$request->get('type').' </i> was unsuccessfully edited');
        }
    }

    /**
    * Show form for editing journal types.
    *
    */ 
    public function showEditJournalType(JournalType $journal_type)
    {
    	return view('developers.journals.journal_types.edit', compact('journal_type'));
    }

    /**
    * Edit journal types.
    *
    */ 
    public function editJournalType(JournalType $journal_type, AddEditJournalTypeRequest $request)
    {
    	$return = JournalType::addEditJournalType($journal_type, $request);

    	if($return["success"]) {
            return redirect(route('journal_types'))->withSuccess('Journal type <i>'.$request->get('type').' </i> was successfully edited');
        } else {
            return redirect(route('edit_journal_type', $journal_type->id))->withDanger('Journal type <i>'.$request->get('type').' </i> was unsuccessfully edited');
        }
    }

    /**
    * Delete a journal type.
    *
    */
    public function deleteJournalType(JournalType $journal_type, Request $request)
    {
    	$developer = Developer::getCurrentDeveloper();
        if(Hash::check($request['security_code'],$developer->security_code)) {
            $return = JournalType::deleteJournalType($journal_type);
            if($return["success"]) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        }   
    }

    ///////////////////////////////////////// Journals //////////////////////////////////////////////

    /**
    * Show all journal types.
    *
    */ 
    public function showJournalCalendar()
    {
    	return view('developers.journals.calendar');
    }

    /**
    * Show the journals
    *
    */
    public function showJournals()
    {
        if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN') 
            or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY')){
            $journals = Journal::getAll();
        } else {
            $journals = Journal::getAllOfUser();
        }

        return view('developers.journals.all', compact('date','formatted_date','journals'));
    }

    /**
    * Show the journals on the selected date.
    *
    */
    public function showJournalCalendarOnDay($date)
    {
    	$journals = Journal::getJournalsOnDayCurrentUser($date);
    	$formatted_date = new DateTime($date);
    	$formatted_date = $formatted_date->format('F d, Y');

    	return view('developers.journals.all', compact('date','formatted_date','journals'));
    }

    /**
    * Show the form for adding a journal entry.
    * 
    */
    public function showAddJournal()
    {
    	$journal = new Journal();
        $journal->date = date('Y-m-d');
    	$journal_types = JournalType::getJournalTypesList();
    	return view('developers.journals.add', compact('journal','journal_types','date'));
    }

    /**
    * Add journal.
    *
    */ 
    public function addJournal(AddEditJournalRequest $request)
    {
    	$journal = new Journal();

    	$return = Journal::addEditJournal($journal, $request);

    	if($return["success"]) {
            return redirect(route('journals'))->withSuccess('Journal was successfully edited');
        } else {
            return redirect(route('add_journal'))->withDanger('Journal was unsuccessfully edited');
        }
    }

    /**
    * Show the form for editing a journal entry.
    * 
    */
    public function showEditJournal($journal)
    {
    	$journal_types = JournalType::getJournalTypesList();
    	return view('developers.journals.edit', compact('journal','journal_types'));
    }

    /**
    * Edit journal types.
    *
    */ 
    public function editJournal(ournal $journal, AddEditJournalRequest $request)
    {
    	$return = Journal::addEditJournal($journal, $request);

    	if($return["success"]) {
            return redirect(route('journals_date'))->withSuccess('Journal was successfully edited');
        } else {
            return redirect(route('edit_journal', array($journal->id)))->withDanger('Journal was unsuccessfully edited');
        }
    }

    /**
    * Delete a journal type.
    *
    */
    public function deleteJournal(Journal $journal, Request $request)
    {
        $return = Journal::deleteJournal($journal);
        if($return["success"]) {
            return 1;
        } else {
            return 2;
        }
    }

}
