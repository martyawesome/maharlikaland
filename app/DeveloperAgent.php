<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class DeveloperAgent extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'developers_agents';

    /**
    * Accredit an agent to a developer.
    *
    */
    public static function addAgentToDeveloper($developer_id, $user_id)
    {
    	$developer_agent = DeveloperAgent::whereRaw(DB::raw('developer_id = '.$developer_id.' and user_id = '.$user_id))->first();
    	if($developer_agent) {
    		$return["success"] = 3;
    	} else {
    		$developer_agent = new DeveloperAgent();
    		$developer_agent->developer_id = $developer_id;
    		$developer_agent->user_id = $user_id;
    		if($developer_agent->touch()) {
    			$return["success"] = 1;
    		} else {
    			$return["success"] = 2;
    		}
    	}
    	return $return;
    }

    /**
    * Get all of the developer's agents.
    *
    */
    public static function getAllDevelopersAgents()
    {
    	$developer = Developer::getCurrentDeveloper();
    	return DeveloperAgent::selectRaw(DB::raw('developers_agents.id, users.first_name, users.last_name, users.id as user_id, users.address, users.contact_number, users.email, user_types.user_type'))
            ->leftJoin('users','users.id','=','developers_agents.user_id')
            ->leftJoin('user_types','user_types.id','=','users.user_type_id')
            ->whereRaw(DB::raw('developers_agents.developer_id = '.$developer->id))
            ->get(); 
    }

    /**
    * Remove an agent from the developer's list.
    *
    */
    public static function removeDeveloperAgent(DeveloperAgent $developer_agent)
    {
    	DB::beginTransaction();

    	$return["success"] = DeveloperAgent::whereId($developer_agent->id)->delete();

    	if($return["success"]) {
    		DB::commit();
    	} else {
    		DB::rollback();
    	}

    	return $return;
    }

}
