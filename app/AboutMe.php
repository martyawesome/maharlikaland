<?php

namespace App;

use Illuminate\Http\Request;
use App\Http\Requests\AddEditAboutMeRequest;
use Illuminate\Database\Eloquent\Model;
use Auth;

use DB;

class AboutMe extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'about_me';

    /**
    * Update About Me of the agent.
    */
    public static function updateAboutMe(AboutMe $about_me, AddEditAboutMeRequest $request) {
    	DB::beginTransaction();

        try {
            $user = Auth::user();
            $agent = Agent::whereId($user->agent_id)->first();
            
            $about_me->agent_id = $user->agent_id;
            $about_me->header = $request->get('header');
            $about_me->content = $request->get('content');

            $return['success'] = $about_me->touch();

            if($return['success']){
                DB::commit();
            } else {
                DB::rollback();
            }
        } catch(Exception $e){
            DB::rollback();
        }

        return $return;
    }
}
