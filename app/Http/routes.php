<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::filter('auth_admin', function()
{
	if (Auth::check() or Auth::viaRemember()){
        if(!App\User::hasYadaAccess()) {
            Auth::logout();
            return redirect(route('admin_login'))->withDanger("Account has no Yada admin access");
        }
    } else {
     	//return redirect(route('admin_login'))->withDanger("Login required");
    	return redirect(route('admin_login'));
    }
});

Route::filter('auth_admin_already_logged_in', function()
{
	if (Auth::check() or Auth::viaRemember()){
        if(App\User::hasYadaAccess()) {
            return redirect(route('admin_dashboard'));
        }
    }
});

Route::filter('auth_agent', function()
{
	if (Auth::check() or Auth::viaRemember()){
        if(!App\User::hasAgentAccess()) {
            Auth::logout();
            return redirect(route('agent_login'))->withDanger("Account has no agent admin access");
        }
    } else {
    	//return redirect(route('agent_login'))->withDanger("Login required");
     	return redirect(route('agent_login'));
    }
});

Route::filter('auth_developer', function()
{
	if (Auth::check() or Auth::viaRemember()){
        if(!App\User::hasDeveloperAccess()) {
            Auth::logout();
            return redirect(route('developer_login'))->withDanger("Account has no developer admin access");
        }
    } else {
    	//return redirect(route('developer_login'))->withDanger("Login required");
     	return redirect(route('developer_login'));
    }
});

Route::filter('auth_agent_already_logged_in', function()
{
	if (Auth::check() or Auth::viaRemember()){
        if(App\User::hasAgentAccess()) {
            return redirect(route('agent_dashboard'));
        }
    }
});

Route::filter('auth_developer_already_logged_in', function()
{
	if (Auth::check() or Auth::viaRemember()){
        if(App\User::hasDeveloperAccess()) {
            return redirect(route('developer_dashboard'));
        }
    }
});




Route::bind('user',function($username)
{
	$user =  App\User::whereUsername($username)->first();
	if($user == null) $user = new App\User();
	return $user;
});


Route::bind('developer',function($datum)
{
	$developer =  App\Developer::find($datum);
	if($developer == null) $developer = new App\Developer();
	return $developer;
});


Route::bind('agent',function($prc_license_number)
{
	return App\Agent::wherePrcLicenseNumber($prc_license_number)->first();
});

Route::bind('property', function($slug){
	$property = App\Property::whereSlug($slug)->first();
	if($property == null) $property = new App\Property();
	return $property;
});

Route::bind('project', function($slug){
	$project = App\Project::whereSlug($slug)->first();
	if($project == null) $project = new App\Project();
	return $project;
});

Route::bind('block', function ($block_number, $route) {
	$project = $route->parameter('project');
	$property = App\Property::join('property_locations','properties.id','=','property_locations.property_id')
        ->whereRaw('properties.project_id = '.$project->id.' and property_locations.block_number = '.$block_number)
        ->first();

    return $property;
});

Route::bind('joint_venture', function($slug){
	$joint_venture = App\JointVenture::whereSlug($slug)->first();
	if($joint_venture == null) $joint_venture = new App\JointVenture();
	return $joint_venture;
});

Route::bind('amenity', function($slug){
	$amenity = App\Amenity::whereSlug($slug)->first();
	if($amenity == null) $amenity = new App\Amenity();
	return $amenity;
});

Route::bind('nearby_establishment', function($slug){
	$nearby_establishment = App\NearbyEstablishment::whereSlug($slug)->first();
	if($nearby_establishment == null) $nearby_establishment = new App\NearbyEstablishment();
	return $nearby_establishment;
});

Route::bind('incentive', function($slug){
	$incentive = App\Incentive::whereSlug($slug)->first();
	if($incentive == null) $incentive = new App\Incentive();
	return $incentive;
});

Route::bind('property_photo_ids', function($photoIds) {
	$property_gallery = array();
	$ids = json_decode(base64_decode($photoIds));
	for ($i = 0; $i < count($ids); $i++) {
		$property_gallery[$i] = App\PropertyGallery::whereId($ids[$i])->first();
	}
	return $property_gallery;
});

Route::bind('property_photo_id', function($photoId) {
	return App\PropertyGallery::whereId($photoId)->first();
});

Route::bind('project_photo_id', function($photoId) {
	return App\ProjectGallery::whereId($photoId)->first();
});

Route::bind('project_photo_ids', function($photoIds) {
	$project_gallery = array();
	$ids = json_decode(base64_decode($photoIds));
	for ($i = 0; $i < count($ids); $i++) {
		$project_gallery[$i] = App\ProjectGallery::whereId($ids[$i])->first();
	}
	return $project_gallery;
});

Route::bind('promotional_materials', function($photoIds) {
	$promotional_materials = array();
	$ids = json_decode(base64_decode($photoIds));
	for ($i = 0; $i < count($ids); $i++) {
		$promotional_materials[$i] = App\PromotionalMaterial::selectRaw(DB::raw('projects.name as project_name, projects.slug as project_slug, promotional_materials.*'))
    	->leftJoin('projects','projects.id','=','promotional_materials.project_id')
		->whereRaw(DB::raw('promotional_materials.id = '.$ids[$i]))->first();
	}
	return $promotional_materials;
});


Route::bind('buyer', function($id){
	$buyer = App\Buyer::find($id);
	if($buyer == null) $buyer = new App\Buyer();
	return $buyer;
});

Route::bind('prospect_buyer', function($id){
	$prospect_buyer = App\ProspectBuyer::find($id);
	if($prospect_buyer ==  null) $prospect_buyer = new App\ProspectBuyer();
	return $prospect_buyer;
});

Route::bind('ledger', function($id){
	$ledger = App\InstallmentAccountLedger::find($id);
	if($ledger != null) return $ledger;
});

Route::bind('ledger_detail', function($id){
	$ledger_detail = App\InstallmentAccountLedgerDetail::find($id);
	if($ledger_detail != null) return $ledger_detail;
});

Route::bind('bill_water', function ($date_covered, $route) {
	$project = $route->parameter('project');
	$bill_water = App\BillWaterSource::whereProjectId($project->id)
		->whereDateCovered($date_covered)
        ->first();

    if($bill_water == null) {
    	$bill_water = new App\BillWaterSource();
    }

    return $bill_water;
});

Route::bind('bill_water_detail', function ($date_covered, $route) {
	$property = $route->parameter('property');
	$bill_water_detail = App\BillWaterSourceDetail::wherePropertyId($property->id)
	->whereDateCovered($date_covered)->first();

    if($bill_water_detail == null) {
    	$bill_water_detail = new App\BillWaterSourceDetail();
    }

    return $bill_water_detail;
});

Route::bind('account_title', function($slug){
	$account_title = App\AccountTitle::whereSlug($slug)->first();
	if($account_title != null) return $account_title;
});

Route::bind('voucher', function($voucher_number){
	$voucher = App\Voucher::whereVoucherNumber($voucher_number)->first();
	if($voucher != null) return $voucher;
});

Route::bind('voucher_detail', function($id){
	$voucher_detail = App\VoucherDetail::find($id);
	if($voucher_detail != null) return $voucher_detail;
});

Route::bind('developer_agent', function($id){
	$developer_agent = App\DeveloperAgent::find($id);
	if($developer_agent != null) return $developer_agent;
});

Route::bind('attendance', function($id){
	$attendance = App\Attendance::find($id);
	if($attendance != null) return $attendance;
});

Route::bind('journal_type', function($id){
	$journal_type = App\JournalType::find($id);
	if($journal_type != null) return $journal_type;
});

Route::bind('journal', function($id){
	$journal = App\Journal::find($id);
	if($journal != null) return $journal;
});

Route::bind('salary_rate', function($id){
	$salary_rate = App\SalaryRate::find($id);
	if($salary_rate != null) return $salary_rate;
});

Route::bind('cash_advance', function($id){
	$cash_advance = App\CashAdvance::find($id);
	if($cash_advance != null) return $cash_advance;
});

Route::bind('cash_advance_payment', function($id){
	$cash_advance_payment = App\CashAdvancePayment::find($id);
	if($cash_advance_payment != null) return $cash_advance_payment;
});

Route::bind('holiday', function($id){
	$holiday = App\Holiday::find($id);
	if($holiday != null) return $holiday;
});

Route::bind('payroll_deduction', function($id){
	$payroll_deduction = App\PayrollDeduction::find($id);
	if($payroll_deduction != null) return $payroll_deduction;
});

Route::bind('payroll_addition', function($id){
	$payroll_addition = App\PayrollAddition::find($id);
	if($payroll_addition != null) return $payroll_addition;
});

Route::bind('param', function($param){
	return $param;
});

Route::group(['namespace' => 'Admin'], function()  {
	Route::group(['prefix' => 'manage'], function ()  {
		Route::group(['prefix' => 'admin'], function ()  {

			Route::group(['before' => 'auth_admin_already_logged_in'], function ()  {
				Route::get('/login', ['as' => 'admin_login','uses' => 'BaseController@showLogin']);
				Route::post('/login', 'BaseController@login');
			});
			Route::get('/logout', ['as' => 'admin_logout','uses' => 'BaseController@logout']);
			
			Route::group(['before' => 'auth_admin'], function ()  {
				Route::get('/', ['as' => 'admin_dashboard','uses' => 'AdminController@index']);
				Route::group(['prefix' => 'admin'], function ()  {
					Route::get('/create_account', ['as' => 'admin_create_account_admin','uses' => 'AdminController@showCreateAccount']);
					Route::get('/edit_account/{user}', ['as' => 'admin_edit_account_admin', 'uses' => 'AdminController@showEditAccount']);
					Route::get('/delete_account/{user}', ['as' => 'admin_delete_account_admin', 'uses' => 'AdminController@deleteAccount']);
					Route::get('/all_accounts', ['as' => 'admin_all_accounts_admin','uses' => 'AdminController@showAllAccounts']); 
					Route::post('/create_account', 'AdminController@createAccount');
					Route::post('/edit_account/{user}', 'AdminController@editAccount');
				});
				Route::group(['prefix' => 'agents'], function ()  {
					Route::get('/non_agents', ['as' => 'admin_non_agents','uses' => 'AgentsController@showNonAgents']);
			    	Route::get('/create_account/{user}', ['as' => 'admin_create_account_agent','uses' => 'AgentsController@showCreateAccount']);
					Route::get('/all_accounts', ['as' => 'admin_all_accounts_agent','uses' => 'AgentsController@showAllAccounts']); 
					Route::get('/edit_account/{user}', ['as' => 'admin_edit_account_agent', 'uses' => 'AgentsController@showEditAccount']);
					Route::get('/delete_account/{user}', ['as' => 'admin_delete_account_agent', 'uses' => 'AgentsController@deleteAccount']);
					Route::post('/create_account/{user}', 'AgentsController@createAccount');
					Route::post('/edit_account/{user}', 'AgentsController@editAccount');
				});
				Route::group(['prefix' => 'developers'], function ()  {
			    	// Developers
			    	Route::get('/all', ['as' => 'admin_all_developers','uses' => 'DevelopersController@showAll']); 
					Route::get('/create_account', ['as' => 'admin_create_developer','uses' => 'DevelopersController@showCreateAccount']);
					Route::get('/edit/{developer}', ['as' => 'admin_edit_developer', 'uses' => 'DevelopersController@showEditDeveloper']);
					
					// Admin accounts
					Route::get('/admin_accounts/{developer}', ['as' => 'admin_developer_accounts','uses' => 'DevelopersController@showAdminAccounts']); 
					Route::get('/admin_accounts/{developer}/create', ['as' => 'admin_create_developer_account', 'uses' => 'DevelopersController@showCreateAccount']);
					Route::get('/admin_accounts/{developer}/edit/{user}', ['as' => 'admin_edit_developer_account', 'uses' => 'DevelopersController@showEditAdminAccount']);
					Route::get('/delete_account/{user}', ['as' => 'admin_delete_developer_account', 'uses' => 'DevelopersController@deleteAccount']);
					
					// Developers
					Route::post('/create_account', 'DevelopersController@createAccount');
					Route::post('/edit/{developer}', 'DevelopersController@editDeveloper');

					// Admin accounts
					Route::post('/admin_accounts/{developer}/edit/{user}', 'DevelopersController@editAdminAccount');
				});
			});
		});
	});
});

/*Route::group(['namespace' => 'Agents'], function()  {
	Route::group(['prefix' => 'manage'], function ()  {
		Route::group(['prefix' => 'agents'], function ()  {

			Route::group(['before' => 'auth_agent_already_logged_in'], function ()  {
				Route::get('/login', ['as' => 'agent_login','uses' => 'BaseController@showLogin']);
				Route::post('/login', 'BaseController@login');
			});
			Route::get('/logout', ['as' => 'agent_logout','uses' => 'BaseController@logout']);
			
			Route::group(['before' => 'auth_agent'], function ()  {
				Route::get('/', ['as' => 'agent_dashboard','uses' => 'BaseController@dashboard']);
				Route::get('/about_me', ['as' => 'agent_about_me','uses' => 'UserController@showAboutMe']);
				Route::get('/my_account/{user}', ['as' => 'agent_my_account','uses' => 'UserController@showMyAccount']);
				Route::get('/add_property', ['as' => 'agent_create_property','uses' => 'PropertiesController@showAddProperty']);
				Route::get('/all_properties', ['as' => 'agent_all_properties','uses' => 'PropertiesController@allProperties']);
				Route::get('/{property}', ['as' => 'agent_view_property','uses' => 'PropertiesController@viewProperty']);
				Route::get('/edit_property/{property}', ['as' => 'agent_edit_property','uses' => 'PropertiesController@showEditProperty']);
				Route::get('/upload_images/{property}', ['as' => 'property_upload_images','uses' => 'PropertiesController@showUploadImages']);
				Route::get('/gallery/{property}',['as' => 'property_gallery','uses' => 'PropertiesController@showGallery']);
				Route::get('/delete/{property}',['as' => 'delete_property','uses' => 'PropertiesController@delete']);
				Route::get('/gallery/{property}/delete_photos', ['as' => 'show_delete_property_photos', 'uses' => 'PropertiesController@showDeletePropertyPhotos']);
				Route::get('/gallery/{property}/delete_photos/{property_photo_ids}', ['as' => 'delete_property_photos', 'uses' => 'PropertiesController@deletePropertyPhotos']);
				Route::get('/gallery/main_photo/{property}', ['as' => 'show_choose_property_main_photo', 'uses' => 'PropertiesController@showChooseMainPhoto']);
				Route::get('/gallery/main_photo/{property}/{photoId}', ['as' => 'choose_property_main_photo', 'uses' => 'PropertiesController@chooseMainPhoto']);
				
				Route::post('/about_me','UserController@editAboutMe');
				Route::post('/my_account/{user}','UserController@editMyAccount');
				
				Route::post('/create_property', 'PropertiesController@addProperty');
				Route::post('/edit_property/{property}', 'PropertiesController@editProperty');
				Route::post('/upload_images/{property}', 'PropertiesController@uploadImages');
			});
		});
	});
});*/

Route::group(['namespace' => 'Developers'], function()  {
	Route::group(['prefix' => 'manage'], function ()  {

			Route::group(['before' => 'auth_developer_already_logged_in'], function ()  {
				Route::get('/login', ['as' => 'developer_login','uses' => 'BaseController@showLogin']);
				Route::post('/login', 'BaseController@login');
			});
			Route::get('/logout', ['as' => 'developer_logout','uses' => 'BaseController@logout']);
			
			Route::group(['before' => 'auth_developer'], function ()  {
				// Dashboard
				Route::get('/', ['as' => 'developer_dashboard','uses' => 'BaseController@dashboard']);

				// Projects
				Route::get('/projects/add', ['as' => 'add_project','uses' => 'ProjectsController@showAddProject']);
				Route::get('/projects', ['as' => 'projects','uses' => 'ProjectsController@showAllProjects']);
				Route::get('/projects/{project}', ['as' => 'project','uses' => 'ProjectsController@viewProject']);
				Route::get('/projects/{project}/delete', ['as' => 'delete_project','uses' => 'ProjectsController@deleteProject']);
				Route::get('/projects/{project}/block/{block?}',['as' => 'project_block','uses' => 'ProjectsController@viewProjectLots']);
				Route::get('/projects/{project}/edit/basic_info', ['as' => 'project_edit_basic_info','uses' => 'ProjectsController@showEditBasicInfo']);
				Route::get('/projects/{project}/edit/joint_ventures', ['as' => 'project_edit_joint_ventures','uses' => 'ProjectsController@showEditJointVentures']);
				Route::get('/projects/{project}/edit/joint_ventures/{joint_venture}', ['as' => 'project_edit_joint_venture','uses' => 'ProjectsController@showEditJointVenture']);
				Route::get('/projects/{project}/edit/amenities', ['as' => 'project_edit_amenities','uses' => 'ProjectsController@showEditAmenities']);
				Route::get('/projects/{project}/edit/amenities/{amenity}', ['as' => 'project_edit_amenity','uses' => 'ProjectsController@showEditAmenity']);
				Route::get('/projects/{project}/edit/incentives', ['as' => 'project_edit_incentives','uses' => 'ProjectsController@showEditIncentives']);
				Route::get('/projects/{project}/edit/incentives/{incentive}', ['as' => 'project_edit_incentive','uses' => 'ProjectsController@showEditIncentive']);
				Route::get('/projects/{project}/edit/location', ['as' => 'project_edit_location','uses' => 'ProjectsController@showEditLocation']);
				Route::get('/projects/{project}/edit/nearby_establishments', ['as' => 'project_edit_nearby_establishments','uses' => 'ProjectsController@showEditNearbyEstablishments']);
				Route::get('/projects/{project}/edit/nearby_establishments/{nearby_establishment}', ['as' => 'project_edit_nearby_establishment','uses' => 'ProjectsController@showEditNearbyEstablishment']);
				Route::get('/projects/{project}/edit/sources', ['as' => 'project_edit_sources','uses' => 'ProjectsController@showEditSources']);
				Route::get('/projects/{project}/edit/subd_plan', ['as' => 'project_edit_subd_plan','uses' => 'ProjectsController@showEditSubdPlan']);
				Route::get('/projects/{project}/edit/vicinity_map', ['as' => 'project_edit_vicinity_map','uses' => 'ProjectsController@showEditVicinityMap']);
				Route::get('/projects/{project}/edit/gallery', ['as' => 'project_gallery','uses' => 'ProjectsController@showProjectGallery']);
				Route::get('/projects/{project}/upload_photos', ['as' => 'project_upload_images', 'uses' => 'ProjectsController@showUploadImages']);
				Route::get('/projects/{project}/delete_photos', ['as' => 'show_delete_project_images', 'uses' => 'ProjectsController@showDeleteProjectImages']);
				Route::get('/projects/{project}/delete_photos/{project_photo_ids}', ['as' => 'delete_project_images', 'uses' => 'ProjectsController@deleteProjectImages']);
				
				// Projects
				Route::post('/projects/add', 'ProjectsController@addProject');
				Route::post('/projects/{project}/edit/basic_info', 'ProjectsController@editBasicInfo');
				Route::post('/projects/{project}/edit/location', 'ProjectsController@editLocation');
				Route::post('/projects/{project}/edit/sources', 'ProjectsController@editSources');
				Route::post('/projects/{project}/edit/joint_ventures', 'ProjectsController@editJointVentures');
				Route::post('/projects/{project}/edit/joint_ventures/{joint_venture}', 'ProjectsController@editJointVenture');
				Route::post('/projects/{project}/edit/amenities', 'ProjectsController@editAmenities');
				Route::post('/projects/{project}/edit/amenities/{amenity}', 'ProjectsController@editAmenity');
				Route::post('/projects/{project}/edit/nearby_establishments', 'ProjectsController@editNearbyEstablishments');
				Route::post('/projects/{project}/edit/nearby_establishments/{nearby_establishment}', 'ProjectsController@editNearbyEstablishment');
				Route::post('/projects/{project}/edit/incentives', 'ProjectsController@editIncentives');
				Route::post('/projects/{project}/edit/incentives/{incentive}', 'ProjectsController@editIncentive');
				Route::post('/projects/{project}/delete/joint_ventures/{joint_venture}', 'ProjectsController@deleteJointVenture');
				Route::post('/projects/{project}/delete/amenities/{amenity}', 'ProjectsController@deleteAmenity');
				Route::post('/projects/{project}/delete/incentives/{incentive}', 'ProjectsController@deleteIncentive');
				Route::post('/projects/{project}/delete/nearby_establishments/{nearby_establishment}','ProjectsController@deleteNearbyEstablishment');
				Route::post('/projects/{project}/edit/vicinity_map', 'ProjectsController@editVicinityMap');
				Route::post('/projects/{project}/edit/subd_plan', 'ProjectsController@editSubdPlan');
				Route::post('/projects/{project}/upload_photos', 'ProjectsController@uploadImages');
				Route::post('/projects/{project}/delete', 'ProjectsController@deleteProject');

				//  Project Properties
				Route::get('/projects/{project}/{property}', ['as' => 'property','uses' => 'PropertiesController@viewProperty']);
				Route::get('/projects/{project}/{property}/edit', ['as' => 'edit_property','uses' => 'PropertiesController@showEditProperty']);
				Route::get('/projects/{project}/{property}/gallery', ['as' => 'property_gallery','uses' => 'PropertiesController@showGallery']);
				Route::get('/projects/{project}/{property}/upload_photos', ['as' => 'property_upload_images', 'uses' => 'PropertiesController@showUploadImages']);
				Route::get('/projects/{project}/{property}/main_photo', ['as' => 'show_choose_property_main_photo', 'uses' => 'PropertiesController@showChooseMainPhoto']);
				Route::get('/projects/{project}/{property}/main_photo/{photoId}', ['as' => 'choose_property_main_photo', 'uses' => 'PropertiesController@chooseMainPhoto']);
				Route::get('/projects/{project}/{property}/delete_photos', ['as' => 'show_delete_property_photos', 'uses' => 'PropertiesController@showDeletePropertyPhotos']);
				Route::get('/projects/{project}/{property}/delete_photos/{property_photo_ids}', ['as' => 'delete_property_photos', 'uses' => 'PropertiesController@deletePropertyPhotos']);
				Route::get('/projects/{project}/{property}/split', ['as' => 'show_split', 'uses' => 'PropertiesController@showSplitProperty']);
				
				//  Project Properties
				Route::post('/projects/{project}/{block?}',['as' => 'developer_edit_project_block','uses' => 'ProjectsController@editProjectLot']);
				Route::post('/projects/{project}/{property}/edit', 'PropertiesController@editProperty');
				Route::post('/projects/{project}/{property}/upload_photos', 'PropertiesController@uploadImages');
				Route::post('/projects/{project}/{property}/split/validate', 'PropertiesController@splitPropertyValidateSecurityCode');
				Route::post('/projects/{project}/{property}/split', 'PropertiesController@splitProperty');
				Route::post('/projects/{project}/{property}/delete', 'PropertiesController@deleteProperty');

				// Agents
				Route::get('/agents/add_agent', ['as' => 'add_agent','uses' => 'AgentsController@showAddAgent']);
				Route::get('/agents', ['as' => 'developers_agents','uses' => 'AgentsController@showDevelopersAgent']);
				Route::post('/agents/search', 'AgentsController@searchAgent');
				Route::post('/agents/add', 'AgentsController@addAgent');
				Route::post('/agents/add_agent', 'AgentsController@importAgents');
				Route::post('/agents/remove/{developer_agent}','AgentsController@removeDeveloperAgent');

				// Buyers
				Route::get('/buyers/add', ['as' => 'add_buyer','uses' => 'BuyersController@showAddBuyer']);
				Route::get('/buyers', ['as' => 'buyers','uses' => 'BuyersController@showBuyers']);
				Route::get('/buyers/{buyer}', ['as' => 'buyer','uses' => 'BuyersController@showBuyer']);
				Route::get('/buyers/{buyer}/edit', ['as' => 'edit_buyer','uses' => 'BuyersController@showEditBuyer']);
				Route::get('/buyers/{buyer}/create_user_account', ['as' => 'create_buyer_user_account','uses' => 'BuyersController@showCreateBuyerUserAccount']);
				
				// Buyers
				Route::post('/buyers/add', 'BuyersController@addBuyer');
				Route::post('/buyers/{buyer}/edit', 'BuyersController@editBuyer');
				Route::post('/buyers/{buyer}/delete', 'BuyersController@deleteBuyer');
				Route::post('/buyers/{buyer}/create_user_account', 'BuyersController@createBuyerUserAccount');
				Route::post('/buyers','BuyersController@importFromExcel');

				// Prospect Buyers
				Route::get('/prospect_buyers/add', ['as' => 'add_prospect_buyer','uses' => 'ProspectBuyersController@showAddProspectBuyer']);
				Route::get('/prospect_buyers', ['as' => 'prospect_buyers','uses' => 'ProspectBuyersController@showProspectBuyers']);
				Route::get('/prospect_buyer/{prospect_buyer}', ['as' => 'prospect_buyer','uses' => 'ProspectBuyersController@showProspectBuyer']);
				Route::get('/prospect_buyer/{prospect_buyer}/edit', ['as' => 'edit_prospect_buyer','uses' => 'ProspectBuyersController@showEditProspectBuyer']);
				Route::get('/prospect_buyer/{prospect_buyer}/upgrade', ['as' => 'upgrade_prospect_buyer','uses' => 'ProspectBuyersController@showUpgradeProspectBuyer']);
				
				// Prospect Buyers
				Route::post('/prospect_buyers/add', 'ProspectBuyersController@addProspectBuyer');
				Route::post('/prospect_buyer/{prospect_buyer}/edit', 'ProspectBuyersController@editProspectBuyer');
				Route::post('/prospect_buyer/{prospect_buyer}/upgrade', 'ProspectBuyersController@upgradeProspectBuyer');
				Route::post('/prospect_buyer/{prospect_buyer}/delete', 'ProspectBuyersController@deleteProspectBuyer');
				
				// Ledger Accounts
				Route::get('/ledgers/add', ['as' => 'new_ledger_buyers','uses' => 'InstallmentAccountLedgersController@showBuyers']);
				Route::get('/ledgers/add/{buyer}', ['as' => 'new_ledger','uses' => 'InstallmentAccountLedgersController@showAddLedger']);
				Route::get('/ledgers', ['as' => 'ledgers_buyers','uses' => 'InstallmentAccountLedgersController@showLedgersBuyers']);
				Route::get('/ledgers/{buyer}', ['as' => 'ledger_properties','uses' => 'InstallmentAccountLedgersController@showLedgerProperties']);
				Route::get('/ledgers/{buyer}/{property}', ['as' => 'ledger','uses' => 'InstallmentAccountLedgersController@showLedger']);
				Route::get('/ledgers/{buyer}/edit/{ledger}', ['as' => 'edit_ledger','uses' => 'InstallmentAccountLedgersController@showEditLedger']);
				// Ledger Accounts - Entries
				Route::get('/ledgers/{buyer}/edit/{ledger}/add_entry', ['as' => 'add_ledger_entry','uses' => 'InstallmentAccountLedgersController@showAddEntry']);
				Route::get('/ledgers/{buyer}/edit/{ledger}/edit_entry/{ledger_detail}', ['as' => 'edit_ledger_entry','uses' => 'InstallmentAccountLedgersController@showEditEntry']);
				Route::get('/ledgers/{buyer}/edit/{ledger}/delete_entry/{ledger_detail}', ['as' => 'delete_ledger_entry','uses' => 'InstallmentAccountLedgersController@deleteEntry']);
				// Ledger Accounts - Export
				Route::get('/ledger/{buyer}/ledger/{ledger}/export_to_excel', ['as' => 'export_ledger_to_excel', 'uses' => 'InstallmentAccountLedgersController@exportToExcel']);
				Route::get('/ledger/{buyer}/ledger/{ledger}/export_to_pdf', ['as' => 'export_ledger_to_pdf', 'uses' => 'InstallmentAccountLedgersController@exportToPdf']);

				// Ledger Accounts - Penalty Calculator
				Route::get('/penalty_calculator', ['as' => 'penalty_calculator', 'uses' => 'InstallmentAccountLedgersController@showPenaltyCalculator']);

				// Ledger Accounts
				Route::post('/ledgers/add/{buyer}','InstallmentAccountLedgersController@addLedger');
				Route::post('/ledgers/{buyer}/edit/{ledger}','InstallmentAccountLedgersController@editLedger');
				Route::post('/ledgers/{buyer}/delete/{ledger}', 'InstallmentAccountLedgersController@deleteLedger');
				// Ledger Accounts - Entries
				Route::post('/ledgers/{buyer}/edit/{ledger}/add_entry','InstallmentAccountLedgersController@addEntry');
				Route::post('/ledgers/{buyer}/edit/{ledger}/edit_entry/{ledger_detail}', 'InstallmentAccountLedgersController@editEntry');
				// Ledger Accounts - Import
				Route::post('/ledgers/{buyer}/{property}', 'InstallmentAccountLedgersController@importFromExcel');
			
				// Electric Bill - Project
				Route::get('/bills/electricity', ['as' => 'bills_electricity_project','uses' => 'BaseController@unavailableFeature']);
				
				// Water Bill - Project
				Route::get('/bills/water/', ['as' => 'bills_water_projects','uses' => 'BillsWaterSourceController@showProjects']);
				Route::get('/bills/water/{project}', ['as' => 'bills_water_project','uses' => 'BillsWaterSourceController@showProjectBills']);
				Route::get('/bills/water/{project}/add', ['as' => 'add_bill_water_project','uses' => 'BillsWaterSourceController@showAddProjectBill']);
				Route::get('/bills/water/{project}/{bill_water?}', ['as' => 'view_bill_water_project','uses' => 'BillsWaterSourceController@showProjectBill']);
				Route::get('/bills/water/{project}/{bill_water?}/edit', ['as' => 'edit_bill_water_project','uses' => 'BillsWaterSourceController@showEditProjectBill']);
				
				// Water Bill - Project Export
				Route::get('/bills/water/{project}/export/excel', ['as' => 'export_project_water_bills_to_excel','uses' => 'BillsWaterSourceController@exportProjectBillsToExcel']);
				Route::get('/bills/water/{project}/export/pdf', ['as' => 'export_project_water_bills_to_pdf','uses' => 'BillsWaterSourceController@exportProjectBillsToPdf']);
				// Water Bill - Property
				Route::get('/bills/water/{project}/{property}/{bill_water_detail?}/edit', ['as' => 'edit_bill_water_monthly','uses' => 'BillsWaterSourceController@showEditPropertyBill']);
				// Water Bill - Property Export
				Route::get('/bills/water/{project}/{bill_water?}/export/excel', ['as' => 'export_monthly_water_bills_to_excel','uses' => 'BillsWaterSourceController@exportMonthlyBillsToExcel']);	
				Route::get('/bills/water/{project}/{bill_water?}/export/pdf', ['as' => 'export_monthly_water_bills_to_pdf','uses' => 'BillsWaterSourceController@exportMonthlyBillsToPdf']);		

				// Water Bill - Project
				Route::post('/bills/water/{project}/add','BillsWaterSourceController@addProjectBill');
				Route::post('/bills/water/{project}/{bill_water?}/edit','BillsWaterSourceController@editProjectBill');
				Route::post('/bills/water/{project}', 'BillsWaterSourceController@importProjectBillsFromExcel');
				// Water Bill - Property
				Route::post('/bills/water/{project}/{property}/{bill_water_detail?}/edit','BillsWaterSourceController@editPropertyBill');
				Route::post('/bills/water/{project}/{bill_water?}','BillsWaterSourceController@importMonthlyBillsFromExcel');
				Route::post('/bills/water/{project}/{bill_water?}/delete', 'BillsWaterSourceController@deleteProjectBill');

				// Accounting - Account Titles
				Route::get('/accounting/account_titles', ['as' => 'account_titles','uses' => 'AccountingController@showAccountTitles']);
				Route::get('/accounting/account_titles/add', ['as' => 'add_account_title','uses' => 'AccountingController@showAddAccountTitle']);
				Route::get('/accounting/account_titles/{account_title}', ['as' => 'edit_account_title','uses' => 'AccountingController@showEditAccountTitle']);								
				
				// Accounting - Account Titles
				Route::post('/accounting/account_titles/add', 'AccountingController@addAccountTitle');
				Route::post('/accounting/account_titles/{account_title}', 'AccountingController@editAccountTitle');
				Route::post('/accounting/account_titles/{account_title}/delete', 'AccountingController@deleteAccountTitle');	

				// Vouchers
				//Route::get('/accounting/vouchers', ['as' => 'vouchers_projects','uses' => 'AccountingController@showVouchersProjects']);
				Route::get('/accounting/vouchers', ['as' => 'vouchers','uses' => 'AccountingController@showVouchers']);
				Route::get('/accounting/vouchers/add', ['as' => 'add_voucher','uses' => 'AccountingController@showAddVoucher']);
				Route::get('/accounting/vouchers/{voucher}', ['as' => 'voucher','uses' => 'AccountingController@showVoucher']);		
				Route::get('/accounting/vouchers/{voucher}/edit', ['as' => 'edit_voucher','uses' => 'AccountingController@showEditVoucher']);
				/*Route::get('/accounting/vouchers/{project}', ['as' => 'vouchers','uses' => 'AccountingController@showVouchers']);
				Route::get('/accounting/vouchers/{project}/add', ['as' => 'add_voucher','uses' => 'AccountingController@showAddVoucher']);
				Route::get('/accounting/vouchers/{project}/{voucher}', ['as' => 'voucher','uses' => 'AccountingController@showVoucher']);				
				Route::get('/accounting/vouchers/{project}/{voucher}/edit', ['as' => 'edit_voucher','uses' => 'AccountingController@showEditVoucher']);	*/			
				// Voucher Details
				Route::get('/accounting/vouchers/{voucher}/add_particular', ['as' => 'add_voucher_detail','uses' => 'AccountingController@showAddVoucherDetail']);				
				Route::get('/accounting/vouchers/{voucher}/{voucher_detail}/edit', ['as' => 'edit_voucher_detail','uses' => 'AccountingController@showEditVoucherDetail']);				

				// Vouchers
				Route::post('/accounting/vouchers/add', 'AccountingController@addVoucher');
				Route::post('/accounting/vouchers/{voucher}/edit', 'AccountingController@editVoucher');
				/*Route::post('/accounting/vouchers/{project}/add', 'AccountingController@addVoucher');*/
				// Voucher Details
				Route::post('/accounting/vouchers/{voucher}/add_particular', 'AccountingController@addVoucherDetail');
				Route::post('/accounting/vouchers/{voucher}/{voucher_detail}/edit', 'AccountingController@editVoucherDetail');
				Route::post('/accounting/vouchers/{voucher}/{voucher_detail}/delete', 'AccountingController@deleteVoucherDetail');

				// Attendances
				Route::get('/attendance', ['as' => 'attendance', 'uses' => 'AttendanceController@showCalendar']);
				Route::get('/attendance/{param}', ['as' => 'attendances_date', 'uses' => 'AttendanceController@showAttendancesOnDay']);
				Route::get('/attendance/{param}/add', ['as' => 'add_attendance', 'uses' => 'AttendanceController@showAddAttendance']);
				Route::get('/attendance/{param}/{attendance}/edit', ['as' => 'edit_attendance', 'uses' => 'AttendanceController@showEditAttendance']);
				
				Route::post('/attendance/{param}','AttendanceController@importFromExcel');
				Route::post('/attendance/{param}/add','AttendanceController@addAttendance');
				Route::post('/attendance/{param}/{attendance}/edit','AttendanceController@editAttendance');	
				Route::post('/attendance/{param}/{attendance}/delete','AttendanceController@deleteAttendance');					

				// Promotional Materials - Images
				Route::get('/marketing/promotional_materials/images', ['as' => 'promotional_images','uses' => 'MarketingController@showPromotionalImages']);
				Route::get('/marketing/promotional_materials/images/projects', ['as' => 'promotional_images_projects','uses' => 'MarketingController@uploadPromotionalImagesSelectProject']);
				Route::get('/marketing/promotional_materials/images/{project}/upload', ['as' => 'upload_promotional_images', 'uses' => 'MarketingController@showUploadPromotionalImages']);
				Route::get('/marketing/promotional_materials/images/delete', ['as' => 'show_delete_promotional_images', 'uses' => 'MarketingController@showDeletePromotionalImages']);
				// Promotional Materials - Videos
				Route::get('/marketing/promotional_materials/videos', ['as' => 'promotional_videos','uses' => 'MarketingController@showPromotionalVideos']);
				Route::get('/marketing/promotional_materials/videos/projects', ['as' => 'promotional_videos_projects','uses' => 'MarketingController@uploadPromotionalVideosSelectProject']);
				Route::get('/marketing/promotional_materials/videos/{project}/upload', ['as' => 'upload_promotional_videos', 'uses' => 'MarketingController@showUploadPromotionalVideos']);
				Route::get('/marketing/promotional_materials/videos/delete', ['as' => 'show_delete_promotional_videos', 'uses' => 'MarketingController@showDeletePromotionalVideos']);
				
				// Promotional Materials - Images
				Route::post('/marketing/promotional_materials/images/{project}/upload', 'MarketingController@uploadPromotionalImages');
				Route::post('/marketing/promotional_materials/images/delete/{promotional_materials}', 'MarketingController@deletePromotionalImages');
				// Promotional Materials - Videos
				Route::post('/marketing/promotional_materials/videos/{project}/upload', 'MarketingController@uploadPromotionalVideos');
				Route::post('/marketing/promotional_materials/videos/delete/{promotional_materials}', 'MarketingController@deletePromotionalVideos');
			
				// Payroll Generation
				Route::get('/payroll/generate', ['as' => 'generate_payroll', 'uses' => 'PayrollController@showSelectDateRange']);
				// Salary Rates
				Route::get('/payroll/salary_rates', ['as' => 'salary_rates', 'uses' => 'PayrollController@showSalaryRates']);
				Route::get('/payroll/salary_rates/add', ['as' => 'add_salary_rate', 'uses' => 'PayrollController@showAddSalaryRate']);
				Route::get('/payroll/salary_rates/edit/{salary_rate}', ['as' => 'edit_salary_rate', 'uses' => 'PayrollController@showEditSalaryRate']);
				// Cash Advances
				Route::get('/payroll/cash_advances', ['as' => 'cash_advances', 'uses' => 'PayrollController@showCashAdvances']);
				// Cash Advance Credits
				Route::get('/payroll/cash_advances/credit', ['as' => 'cash_advances_credit', 'uses' => 'PayrollController@showCashAdvancesCredit']);
				Route::get('/payroll/cash_advances/credit/add', ['as' => 'add_cash_advance_credit', 'uses' => 'PayrollController@showAddCashAdvanceCredit']);
				Route::get('/payroll/cash_advances/credit/edit/{cash_advance}', ['as' => 'edit_cash_advance_credit', 'uses' => 'PayrollController@showEditCashAdvanceCredit']);
				// Cash Advance Payments
				Route::get('/payroll/cash_advances/payments', ['as' => 'cash_advances_payments', 'uses' => 'PayrollController@showCashAdvancesPayments']);
				Route::get('/payroll/cash_advances/payments/add', ['as' => 'add_cash_advance_payment', 'uses' => 'PayrollController@showAddCashAdvancePayment']);
				Route::get('/payroll/cash_advances/payments/edit/{cash_advance_payment}', ['as' => 'edit_cash_advance_payment', 'uses' => 'PayrollController@showEditCashAdvancePayment']);
				// Holidays
				Route::get('/payroll/holidays', ['as' => 'holidays', 'uses' => 'PayrollController@showHolidays']);
				Route::get('/payroll/holidays/add', ['as' => 'add_holiday', 'uses' => 'PayrollController@showAddHoliday']);
				Route::get('/payroll/holidays/edit/{holiday}', ['as' => 'edit_holiday', 'uses' => 'PayrollController@showEditHoliday']);
				// Payroll Deductions
				Route::get('/payroll/deductions', ['as' => 'payroll_deductions', 'uses' => 'PayrollController@showDeductions']);
				Route::get('/payroll/deductions/add', ['as' => 'add_payroll_deduction', 'uses' => 'PayrollController@showAddDeduction']);
				Route::get('/payroll/deductions/edit/{payroll_deduction}', ['as' => 'edit_payroll_deduction', 'uses' => 'PayrollController@showEditDeduction']);
				// Payroll Additions
				Route::get('/payroll/additions', ['as' => 'payroll_additions', 'uses' => 'PayrollController@showAdditions']);
				Route::get('/payroll/additions/add', ['as' => 'add_payroll_addition', 'uses' => 'PayrollController@showAddAddition']);
				Route::get('/payroll/additions/edit/{payroll_addition}', ['as' => 'edit_payroll_addition', 'uses' => 'PayrollController@showEditAddition']);
				
				// Payroll Generation
				Route::post('/payroll/generate', 'PayrollController@generatePayroll');
				// Salary Rates
				Route::post('/payroll/salary_rates/add','PayrollController@addSalaryRate');
				Route::post('/payroll/salary_rates/edit/{salary_rate}','PayrollController@editSalaryRate');
				Route::post('/payroll/salary_rates/delete/{salary_rate}','PayrollController@deleteSalaryRate');
				Route::post('/payroll/salary_rates','PayrollController@importSalaryRatesFromExcel');
				// Cash Advances
				Route::post('/payroll/cash_advances/credit/add','PayrollController@addCashAdvanceCredit');
				Route::post('/payroll/cash_advances/credit/edit/{cash_advance}','PayrollController@editCashAdvanceCredit');
				Route::post('/payroll/cash_advances/credit/delete/{cash_advance}','PayrollController@deleteCashAdvanceCredit');
				Route::post('/payroll/cash_advances/payments/add','PayrollController@addCashAdvancePayment');
				Route::post('/payroll/cash_advances/payments/edit/{cash_advance}','PayrollController@editCashAdvancePayment');
				Route::post('/payroll/cash_advances/payments/delete/{cash_advance}','PayrollController@deleteCashAdvancePayment');
				Route::post('/payroll/cash_advances/credit', 'PayrollController@importCaCreditFromExcel');
				Route::post('/payroll/cash_advances/payments', 'PayrollController@importCaPaymentsFromExcel');
				// Holidays
				Route::post('/payroll/holidays/add','PayrollController@addHoliday');
				Route::post('/payroll/holidays/edit/{holiday}','PayrollController@editHoliday');
				Route::post('/payroll/holidays/delete/{holiday}','PayrollController@deleteHoliday');
				Route::post('/payroll/holidays/sync','PayrollController@syncHolidays');
				// Deductions
				Route::post('/payroll/deductions/add','PayrollController@addDeduction');
				Route::post('/payroll/deductions/edit/{payroll_deduction}','PayrollController@editDeduction');
				Route::post('/payroll/deductions/delete/{payroll_deduction}','PayrollController@deleteDeduction');
				Route::post('/payroll/deductions', 'PayrollController@importDeductionsFromExcel');
				// Additions
				Route::post('/payroll/additions/add','PayrollController@addAddition');
				Route::post('/payroll/additions/edit/{payroll_addition}','PayrollController@editAddition');
				Route::post('/payroll/additions/delete/{payroll_addition}','PayrollController@deleteAddition');
				Route::post('/payroll/additions', 'PayrollController@importAdditionsFromExcel');
				
				// Journals
				Route::get('/journals', ['as' => 'journals', 'uses' => 'JournalsController@showJournals']);
				Route::get('/journals/add', ['as' => 'add_journal', 'uses' => 'JournalsController@showAddJournal']);
				Route::get('/journals/edit/{journal}', ['as' => 'edit_journal', 'uses' => 'JournalsController@showEditJournal']);
				// Journal Types
				Route::get('/journals/types', ['as' => 'journal_types', 'uses' => 'JournalsController@showJournalTypes']);
				Route::get('/journals/types/add', ['as' => 'add_journal_type', 'uses' => 'JournalsController@showAddJournalType']);
				Route::get('/journals/types/edit/{journal_type}', ['as' => 'edit_journal_type', 'uses' => 'JournalsController@showEditJournalType']);
				
				// Journals
				Route::post('/journals/add', 'JournalsController@addJournal');
				Route::post('/journals/edit/{journal}', 'JournalsController@editJournal');
				Route::post('/journals/delete/{journal}', 'JournalsController@deleteJournal');
				// Journal Types
				Route::post('/journals/types/add', 'JournalsController@addJournalType');
				Route::post('/journals/types/edit/{journal_type}', 'JournalsController@editJournalType');
				Route::post('/journals/types/delete/{journal_type}', 'JournalsController@deleteJournalType');
				
				// My Account
				Route::get('/my_account/{user}', ['as' => 'my_account', 'uses' => 'UsersController@showMyAccount']);
				Route::get('/my_account/admin/{user}', ['as' => 'my_admin_account', 'uses' => 'UsersController@showMyAdminAccount']);
				Route::get('/my_account/{user}/edit', ['as' => 'edit_account', 'uses' => 'UsersController@showEditUser']);
				Route::get('/my_account/admin/{user}/edit', ['as' => 'edit_admin_account', 'uses' => 'UsersController@showEditUser']);
				
				// My Account
				Route::post('/my_account/{user}/edit', 'UsersController@editMyAccount');
				Route::post('/my_account/admin/{user}/edit', 'UsersController@editMyAdminAccount');

				// Users
				Route::get('/users', ['as' => 'users', 'uses' => 'UsersController@showUsers']);
				Route::get('/users/add', ['as' => 'add_user', 'uses' => 'UsersController@showAddUser']);
				Route::get('/user/{user}', ['as' => 'user', 'uses' => 'UsersController@showUser']);
				
				// Users - Broker
				Route::get('/user/{user}/broker/new', ['as' => 'user_new_broker', 'uses' => 'UsersController@showNewBroker']);
				Route::get('/user/{user}/broker/{agent}', ['as' => 'user_edit_broker', 'uses' => 'UsersController@showEditBroker']);
				
				// Users
				Route::post('/users/add', 'UsersController@addUser');
				Route::post('/user/{user}', 'UsersController@editUser');
				Route::post('/user/{user}/broker/new', 'UsersController@newBroker');
				Route::post('/user/{user}/broker/{agent}', 'UsersController@editBroker');
				Route::post('/user/{user}/delete', 'UsersController@deleteUser');
				Route::post('/users', 'UsersController@importFromExcel');
			});
	});
});


Route::group(['prefix' => 'manage'], function ()  {
	Route::group(['prefix' => 'api'], function ()  {
		Route::get('/get_agent_profile', ['as' => 'api_get_agent_profile','uses' => 'ApiController@getAgentProfile']);
		Route::get('/get_about_me', ['as' => 'api_get_about_me','uses' => 'ApiController@getAboutMe']);
		Route::get('/get_properties', ['as' => 'api_get_properties','uses' => 'ApiController@getProperties']);
		Route::get('/get_properties_of_agent', ['as' => 'api_get_properties_of_agent','uses' => 'ApiController@getPropertiesOfAgent']);
		Route::get('/get_property', ['as' => 'api_get_property','uses' => 'ApiController@getProperty']);	
	});
});


