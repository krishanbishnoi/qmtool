<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('test_function','HomeController@test_function');
Route::get('developerTest','HomeController@developerTest');
// Cron job routes ---------------------------------------------------
Route::get('welcome','WebsiteController@welcome');
Route::get('setPassword/{email}','WebsiteController@setPassword');
Route::post('updatePassword','WebsiteController@updatePassword');
Route::post('sendPasswordResetMail','WebsiteController@sendPasswordResetMail');

Route::get('rawDataPurge','CronController@rawDataPurge');
Route::get('tempToAuditPool','CronController@tempToAuditPool');
// Cron job routes end here ------------------------------------------
Route::post('test_html','HomeController@test_html');
Route::get('test_html_new_get','HomeController@test_html_new_get');
Route::post('test_html_new_get','HomeController@test_html_new_get');


Route::get('delete_audit/{call_id}','HomeController@delete_audit');

Route::post('save_png','HomeController@save_png');
Route::get('save_png','HomeController@get_png');

Route::get('/','WebsiteController@welcome');

Auth::routes(['verify' => true]);
Route::post('email/resend','Auth\VerificationController@resend');

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/verify_otp','Auth\LoginController@verify_otp');

//authentication layer
Route::middleware(['auth'])->group(function () {

	Route::resource('audit_cycle','AuditcycleController');
	Route::resource('allocationdel','AllocationController');


	Route::get('logged_in_user_total_notifications','HomeController@logged_in_user_total_notifications');
	Route::post('mark_read_notification','HomeController@mark_read_notification');

	Route::get('profile','UserController@profile');
	Route::patch('update_profile/{id}','UserController@updateProfile');
	
	Route::get('sendAddOnFeatureMail','UserController@sendAddOnFeatureMail');

	Route::get('permission','RoleController@list_permissions');
	Route::resource('role','RoleController');
	Route::post('role/assign/permission', 'RoleController@assign_permission')->name('assign_permission');
	Route::get('role/detach/permission/{role_id}/{perm_id}', 'RoleController@detach_permission');
	Route::resource('user', 'UserController');
	Route::get('user/status/{user_id}/{status}','UserController@change_user_status');

	Route::resource('company','CompanyController');
	
	Route::get('settings/subscription/plan','SubscriptionController@plan_details');

	Route::resource('audit_alert_box','AuditAlertBoxController');
	Route::resource('client','ClientController');
	Route::resource('process','ProcessController');
	Route::resource('region','RegionController');
	Route::resource('language','LanguageController');

	Route::get('client/{client_id}/partner','ClientController@clients_partner');
	Route::get('client/{client_id}/create/partner','PartnerController@create');
	Route::post('client/partner/store','PartnerController@store')->name('client_partner_store');
	Route::resource('partner','PartnerController');
	Route::get('partner/{partner_id}/add/spocs','PartnerController@add_spocs');
	Route::post('partner/store/spocs','PartnerController@store_spocs')->name('store_partners_spocs');
	Route::delete('partner/process/spoc/delete/{id}','PartnerController@partner_process_spoc_delete')->name('partner_process_spoc_delete');

	Route::resource('qm_sheet','QmSheetController');
	Route::get('qm_sheet/{sheet_id}/add_parameter','QmSheetController@add_parameter');
	Route::get('qm_sheet/{sheet_id}/list_parameter','QmSheetController@list_parameter');
	Route::get('qm_sheet/{sheet_id}/parameter','QmSheetController@list_parameter');
	Route::post('qm_sheet/store_parameters','QmSheetController@store_parameters')->name('store_parameters');
	Route::get('qm_sheet/re_label/{id}','QmSheetController@re_label');
	Route::post('re_label_update','QmSheetController@re_label_update');
	Route::delete('delete_parameter/{id}','QmSheetController@delete_parameter')->name('delete_parameter');
	Route::get('parameter/{id}/edit','QmSheetController@edit_parameter');
	Route::post('update_parameter','QmSheetController@update_parameter')->name('update_parameter');
	Route::get('delete_sub_parameter/{id}','QmSheetController@delete_sub_parameter');



	Route::get('allocation/qtl_qa','AllocationController@qtl_qa');

	Route::get('allocation/qa_target','AllocationController@qa_target');
	Route::get('allocation/raw_data_batch','AllocationController@batch_data');
	Route::get('allocation/pendingList/{batch_id}','AllocationController@pendingList');
	Route::get('allocation/reassign','AllocationController@reassign');

	Route::get('batch/status/{batch_id}/{status}','AllocationController@change_visiblity_status');

	Route::post('upload_qa_target','AllocationController@upload_qa_target')->name('upload_qa_target');

	Route::get('get_company_qtl_list','AllocationController@get_company_qtl_list');
	Route::get('get_qtl_team_list/{qtl_id}','AllocationController@get_qtl_team_list');
	Route::get('remove_qa_from_qtl_team/{qa_id}','AllocationController@remove_qa_from_qtl_team');
	Route::get('get_company_qa_list','AllocationController@get_company_qa_list');
	Route::post('allocation/store_qtl_qa','AllocationController@store_qtl_qa');

	Route::get('allocation/qa_sheet','AllocationController@qa_sheet');
	Route::get('get_company_client_list','AllocationController@get_company_client_list');
	Route::get('get_qtls_by_client/{client_id}','AllocationController@get_qtls_by_client');
	Route::get('get_sheets_by_client/{client_id}','AllocationController@get_sheets_by_client');
	Route::get('get_qtl_team/{qtl_id}','AllocationController@get_qtl_team');
	Route::get('get_qm_sheet_associated_qa/{sheet_id}','AllocationController@get_qm_sheet_associated_qa');
	Route::post('allocation/store_qa_sheet','AllocationController@store_qa_sheet');

	Route::get('allocation/dump_uploader','AllocationController@dump_uploader');
	Route::get('get_client_partner/{client_id}','AllocationController@get_client_partner');
	Route::get('get_partners_process/{partner_id}','AllocationController@get_partners_process');
	Route::get('get_partners_location/{partner_id}','AllocationController@get_partners_location');
	Route::post('upload_raw_data_dump','AllocationController@upload_raw_data_dump')->name('upload_raw_data_dump');


	Route::get('allocation/upload_report/{batch_id}','AllocationController@upload_report');
	Route::get('purge_data','AllocationController@data_purge')->name('data_purge');
	// Route::get('allocation/qa_agent','AllocationController@qa_agent');
	// Route::post('allocation/get_available_agents_to_allocate','AllocationController@get_available_agents_to_allocate');
	// Route::post('allocation/update_qa_agent','AllocationController@update_qa_agent');
	// Route::post('get_alloted_partners_agent_by_qa','AllocationController@get_alloted_partners_agent_by_qa');
	// Route::post('remove_agent_from_qa_team','AllocationController@remove_agent_from_qa_team');

	Route::resource('rca','RcaController');
	Route::resource('rca2','Rca2Controller');


	Route::get('audit_sheet/{qm_sheet_id}','AuditController@render_audit_sheet');
	Route::get('audit_sheet_dev/{qm_sheet_id}','AuditController@render_audit_sheet_dev');
	Route::get('get_qm_sheet_details_for_audit/{qm_sheet_id}','AuditController@get_qm_sheet_details_for_audit');
	Route::get('get_raw_data_for_audit/{comm_instance_id}','AuditController@get_raw_data_for_audit');
	Route::post('get_raw_data_on_data_range','AuditController@get_raw_data_on_data_range');

	Route::get('audited_list/{qm_sheet_id}','AuditController@audited_list');
	Route::get('tmp_audited_list/{qm_sheet_id}','AuditController@tmp_audited_list');
	Route::post('allocation/store_audit','AuditController@store_audit');
	Route::get('get_reasons_by_type/{type_id}','AuditController@get_reasons_by_type');

	Route::resource('reason','ReasonController');
	Route::get('delete_reason_by_id/{reason_id}','ReasonController@delete_reason_by_id');
	Route::resource('calibration','CalibrationController');
	Route::get('calibration/my_request/list','CalibrationController@my_request');
	Route::get('get_client_all_process/{client_id}','ClientController@get_client_all_process');
	Route::post('get_client_process_based_qm_sheet','QmSheetController@get_client_process_based_qm_sheet');
	Route::get('delete_calibrator/{cid}','CalibrationController@delete_calibrator');
	Route::get('calibrate/{calibrator_id}','CalibrationController@render_calibration_sheet');
	Route::get('get_qm_sheet_details_for_calibration/{qm_sheet_id}/{calibration_id}','CalibrationController@get_qm_sheet_details_for_calibration');
	Route::post('store_calibration','CalibrationController@store_calibration');
	Route::get('calibration/{calibration_id}/result','CalibrationController@calibration_result');
	Route::post('store_feedback','PartnerController@store_feedback')->name('store_feedback'); 

	Route::prefix('qc')->group(function () {
		Route::get('audits','QcController@audits');
		Route::get('single_audit_detail/{audit_id}','QcController@single_audit_detail');
		Route::post('raise_qc','QcController@raise_qc')->name('raise_qc');
		Route::post('get_details_for_update_audit_sub_parameter','QcController@get_details_for_update_audit_sub_parameter');
		Route::post('update_basic_audit_data','QcController@update_basic_audit_data');
		Route::post('update_sp_data','QcController@update_sp_data');
		Route::post('update_qc_status','QcController@update_qc_status');		
	});



	Route::prefix('dashboard')->group(function () {
		Route::get('get_client_welcome_data','HomeController@get_client_welcome_data');
		Route::post('get_client_welcome_data_cycle','HomeController@get_client_welcome_data_cycle');
		
        Route::post('/test_excel','HomeController@testExcel');

		Route::get('client_detail','HomeController@client_detail_dashboard');
		Route::get('get_loged_in_client_partners','HomeController@get_loged_in_client_partners');
		Route::get('get_partner_process1/{partner_id}','HomeController@get_partner_process1');
		Route::get('get_partner_process/{partner_id}','HomeController@get_partner_process');
		Route::get('get_partner_locations/{partner_id}','HomeController@get_partner_locations');
		Route::get('get_partner_locations1/{partner_id}','HomeController@get_partner_locations1');
		Route::post('get_client_partner_dashboard_data','HomeController@get_client_partner_dashboard_data');
		Route::get('get_qtl_process','HomeController@get_qtl_process');
		Route::get('get_agent_report','HomeController@get_agent_report');	
		Route::get('get_partner_lob/{partner_id}','HomeController@get_partner_lob');	
		Route::get('get_partner_audit_cycle/{process_id}','HomeController@get_partner_audit_cycle');	
		Route::get('get_partner_lob_1/{partner_id}','HomeController@get_partner_lob_1');
		Route::get('get_partner_locations/{partner_id}','HomeController@get_partner_locations');
		Route::post('get_qa_dashboard_data','HomeController@get_qa_dashboard_data');
		Route::post('get_qtl_dashboard_data','HomeController@get_qtl_dashboard_data');
	});
	Route::prefix('report')->group(function () {
		Route::get('parameter_compliance','HomeController@report_disposition_wise_report');
		Route::post('get_client_disposition_wise_report_data','HomeController@get_client_disposition_wise_report_data');
		Route::get('agent_compliance','HomeController@report_agent_wise_report');
		Route::post('get_client_agent_wise_report_data','HomeController@get_client_agent_wise_report_data');
		Route::get('parameter_trending_report','HomeController@parameter_trending_report');
		Route::post('get_parameter_trending_report_data','HomeController@get_parameter_trending_report_data');
		Route::get('raw_dump_with_audit_report','HomeController@raw_dump_with_audit_report');
		Route::get('raw_dump_with_audit_report_temp','HomeController@raw_dump_with_audit_report_temp');

       		// raw dump report filters starts
		Route::get('get_rdr_client_list','HomeController@get_rdr_client_list');
		Route::get('get_rdr_client_partner_list/{client_id}','HomeController@get_rdr_client_partner_list');
		Route::get('get_rdr_client_partner_location_list/{partner_id}/{client_id}','HomeController@get_rdr_client_partner_location_list');
		Route::get('get_rdr_client_partner_location_process_list/{partner_id}/{location_id}','HomeController@get_rdr_client_partner_location_process_list');
		Route::get('get_rdr_client_process_qmsheeet_list/{client_id}/{process_id}','HomeController@get_rdr_client_process_qmsheeet_list');

		Route::get('rebuttal_status_report','HomeController@rebuttal_status_report');

		// Routes created by QD1346
		Route::get('agent_performance_report','HomeController@agent_performance_report_view');
        Route::get('monthly_trending_report','HomeController@monthly_trending_report_view');
        Route::post('mtr_data_parameter_wise','HomeController@mtr_data_parameter_wise');
        Route::post('mtr_data_sub_parameter_wise','HomeController@mtr_data_sub_parameter_wise');
        Route::post('mtr_data_agent_wise','HomeController@mtr_data_agent_wise');
        Route::get('qc_report','HomeController@qc_report');
        Route::get('new_agent_compliance','HomeController@new_agent_compliance');
        Route::post('new_agent_compliance','HomeController@new_agent_compliance_report');

        Route::get('rebuttal_summary','HomeController@rebuttal_summary_view');
        //ajax route for rebuttal summary report
        Route::post('rebuttal_disposition_wise','HomeController@rebuttal_disposition_wise');
        Route::post('rebuttal_agent_wise','HomeController@rebuttal_agent_wise');
        Route::post('rebuttal_parameter_wise','HomeController@rebuttal_parameter_wise');
        Route::post('rebuttal_overall','HomeController@rebuttal_overall');	

        Route::get('new_parameter_compliance','HomeController@new_parameter_compliance');
        Route::post('new_parameter_compliance','HomeController@new_parameter_compliance_report');	
	});

	Route::prefix('partner')->group(function () {
		Route::get('audit/completed','PartnerController@audit_completed');
		Route::get('single_audit_detail/{audit_id}','PartnerController@single_audit_detail');
		Route::post('raise_rebuttal','RebuttalController@raise_rebuttal')->name('raise_rebuttal');
	});

	Route::get('raised_rebuttal_list','RebuttalController@raised_rebuttal_list');
	Route::get('rebuttal_status/{rebuttal_id}','RebuttalController@rebuttal_status');
	Route::get('get_para_and_sub_para_for_rebuttal_status/{rebuttal_id}','RebuttalController@get_para_subpara_rebuttal_status');
	Route::post('rebuttal/update_basic_audit_data','RebuttalController@update_basic_audit_data');
	
	Route::post('reply_rebuttal','RebuttalController@reply_rebuttal');

	Route::resource('rca_type','RcaTypeController');
	Route::get('get_rca1_by_rca_mode_id/{rca_mode_id}','RcaController@get_rca1_by_rca_mode_id');
	Route::get('get_rca2_by_rca1_id/{rca1_id}','RcaController@get_rca2_by_rca1_id');
	Route::get('get_rca3_by_rca2_id/{rca2_id}','RcaController@get_rca3_by_rca2_id');

	Route::get('get_type_2_rca1_by_rca_mode_id/{rca_mode_id}','RcaController@get_type_2_rca1_by_rca_mode_id');
	Route::get('get_type_2_rca2_by_rca1_id/{rca1_id}','RcaController@get_type_2_rca2_by_rca1_id');
	Route::get('get_type_2_rca3_by_rca2_id/{rca2_id}','RcaController@get_type_2_rca3_by_rca2_id');


	Route::get('get_rcatwo1_by_rca_mode2_id/{rca_mode2_id}','Rca2Controller@get_rcatwo1_by_rca_mode2_id');
	Route::get('get_rcatwo2_by_rcatwo1_id/{rcatwo1_id}','Rca2Controller@get_rcatwo2_by_rcatwo1_id');
	Route::get('get_rcatwo3_by_rcatwo2_id/{rcatwo2_id}','Rca2Controller@get_rcatwo3_by_rcatwo2_id');
	Route::get('checkholiday','HomeController@fetch_date');
	Route::get('update_Audit','HomeController@get_date');

	Route::get('temp_audit/edit/{audit_id}','TempAuditController@audit_detail');
	Route::post('temp_audit/update_basic_audit_data','TempAuditController@update_basic_audit_data');
	Route::post('temp_audit/get_details_for_update_audit_sub_parameter','TempAuditController@get_details_for_update_audit_sub_parameter');
	Route::post('temp_audit/update_sp_data','TempAuditController@update_sp_data');
	Route::post('transfer_audit_from_temp_to_main_pool','TempAuditController@transfer_audit_from_temp_to_main_pool');
	Route::resource('cluster','ClusterController');
	Route::get('delete_circle_by_id/{circle_id}','ClusterController@delete_circle_by_id');


	Route::get('/edit_rca_mode','RcaController@edit');
	Route::patch('edit_rca_mode/{rca_mode_id}','RcaController@update');
	Route::get('/delete_rca_mode','RcaController@destroy');
	Route::get('/add_custom_rca','RcaController@custom_form');
	Route::post('add_custom_rca','RcaController@custom_add');


	Route::get('/edit_rca2_mode','Rca2Controller@edit');
	Route::patch('edit_rca2_mode/{rca_mode2_id}','Rca2Controller@update');
	Route::get('/delete_rca2_mode','Rca2Controller@destroy');
	Route::get('/add_custom_rca2','Rca2Controller@custom_form');
	Route::post('add_custom_rca2','Rca2Controller@custom_add');

// Monthly target routing start
 
Route::get('allocation/month_target_uploader','AllocationController@month_target_uploader');
Route::post('upload_month_target','AllocationController@upload_month_target')->name('upload_month_target');
Route::get('allocation/list_month_target','AllocationController@list_month_target');

// Monthly target routing ends

	Route::prefix('target')->group(function () {
		Route::get('set','TargetController@index');
		Route::get('get_client/{company_id}/{process_owner_id}','TargetController@get_client');
		Route::get('get_process/{company_id}','TargetController@get_process');	
		
		Route::get('get_partners/{client_id}/{company_id}','TargetController@get_partners');
		Route::get('get_location/{client_id}/{company_id}','TargetController@get_location');

		Route::get('get_audit_cycle/{client_id}/{process_id}','TargetController@get_audit_cycle');
		Route::get('get_lob/{partner_id}','TargetController@get_lob');
		Route::get('get_brand/{partner_id}','TargetController@get_brand');
		Route::get('get_circle/{partner_id}','TargetController@get_circle');
		Route::post('save_target','TargetController@save_target')->name('save_target');
		
		Route::get('list','TargetController@getList');
		Route::get('{target_id}/edit','TargetController@single_target');
		Route::post('update_target','TargetController@updae_target')->name('update_target');


	});

	Route::get('welcome_dashboard_new','HomeController@welcome_dashboard_new')->name('welcome_dashboard_new');
	Route::post('welcome_dashboard_new','HomeController@welcome_dashboard_new');
	Route::get('get_qrc_lob_wise_welcome_dashboard/{process_id}/{start}/{end}','HomeController@get_qrc_lob_wise_welcome_dashboard');

	Route::post('password/email','ForgetPasswordController@index');

	Route::get('update_location','TargetController@location');

	Route::get('change_location','TargetController@change_location');
	Route::get('new_loc/{audit_id}/{raw_id}/{partner_id}/{location}','TargetController@edit_to_loc');
	Route::post('update_loc_par','TargetController@update_loc_par')->name('update_loc_par');

	Route::get('change_location_agent','TargetController@change_location_agent');
	Route::post('change_location_agent','TargetController@change_location_agent')->name('change_location_agent');

	Route::get('new_loc_agent/{month}/{process_id}/{agent}/{location}','TargetController@new_loc_agent');
	Route::post('update_loc_par_agent','TargetController@update_loc_par_agent')->name('update_loc_par_agent');

	Route::post('allocation/reassign_multiple','AllocationController@reassign_multiple')->name('reassign_multiple');
	Route::post('allocation/reassign_search','AllocationController@reassign_search')->name('reassign_search');

	Route::get('/delete_audit_page','DataPurgeController@delete_audit_page');
	Route::get('/delete_audit/{raw_data_id}/{audit_id}','DataPurgeController@delete_audit');

	Route::prefix('target')->group(function () {
		Route::get('set','TargetController@index');
		Route::get('get_client/{company_id}/{process_owner_id}','TargetController@get_client');
		Route::get('get_process/{company_id}','TargetController@get_process');	
		
		Route::get('get_partners/{client_id}/{company_id}','TargetController@get_partners');
		Route::get('get_location/{client_id}/{company_id}','TargetController@get_location');

		Route::get('get_audit_cycle/{client_id}/{process_id}','TargetController@get_audit_cycle');
		Route::get('get_lob/{partner_id}','TargetController@get_lob');
		Route::get('get_brand/{partner_id}','TargetController@get_brand');
		Route::get('get_circle/{partner_id}','TargetController@get_circle');
		Route::post('save_target','TargetController@save_target')->name('save_target');
		
		Route::get('list','TargetController@getList');
		Route::get('{target_id}/edit','TargetController@single_target');
		Route::post('update_target','TargetController@updae_target')->name('update_target');


	});

	Route::prefix('qa_target')->group(function () {
		Route::get('set','QaTargetController@index');
		Route::get('get_client/{company_id}/{process_owner_id}','QaTargetController@get_client');
		Route::get('get_process/{company_id}','QaTargetController@get_process');		
		Route::get('get_partners/{client_id}/{company_id}','QaTargetController@get_partners');
		Route::get('get_audit_cycle/{client_id}/{process_id}','QaTargetController@get_audit_cycle');
		Route::get('get_lob/{partner_id}','QaTargetController@get_lob');
		Route::get('get_brand/{partner_id}','QaTargetController@get_brand');
		Route::get('get_circle/{partner_id}','QaTargetController@get_circle');
		Route::post('save_qa_target','QaTargetController@save_target')->name('qa_save_target');
		
		Route::get('list','QaTargetController@getList');
		Route::get('{target_id}/edit','QaTargetController@single_target');
		Route::post('update_qa_target','QaTargetController@updae_target')->name('update_qa_target');
		Route::get('qa_report','QaTargetController@search_report');
		Route::post('qa_report','QaTargetController@search_report')->name('qa_report');
		Route::get('qtl_report','QaTargetController@search_qtl_report');
		Route::post('qtl_report','QaTargetController@search_qtl_report')->name('qtl_report');

		Route::get('qa_performance','QaTargetController@qa_performance');
		Route::post('qa_performance','QaTargetController@qa_performance')->name('qa_performance');

		Route::get('qa_performance_matrix','QaTargetController@qa_performance_matrix');
		Route::post('qa_performance_matrix','QaTargetController@qa_performance_matrix')->name('qa_performance_matrix');
		
	});

	Route::prefix('qc_qa')->group(function () {
		Route::get('audits','QcController@qc_feedback_audits');
		Route::get('single_audit_detail/{audit_id}','QcController@single_audit_detail_feedback');
		Route::post('raise_qc','QcController@raise_qc')->name('raise_qc');
		Route::post('get_details_for_update_audit_sub_parameter','QcController@get_details_for_update_audit_sub_parameter');
		Route::post('update_basic_audit_data','QcController@update_basic_audit_data');
		Route::post('update_sp_data','QcController@update_sp_data');
		Route::post('update_qc_status','QcController@update_qc_status');		
	});

	Route::prefix('qa_daily_target')->group(function () {
		Route::get('set','QaTargetController@set_qa_daily_target');
		
		Route::get('list','QaTargetController@getList_daily_target');
		Route::get('{user_id}/view_full','QaTargetController@single_user_target');
		Route::post('update_qa_target','QaTargetController@updae_target')->name('update_qa_target');
		
	});

	Route::prefix('trainning_pkt')->group(function () {

		Route::post('upload_trainining_pkt','AllocationController@upload_trainining_pkt')->name('upload_trainining_pkt');

		Route::get('set','TrainningPktController@index');
		
		Route::get('list','TrainningPktController@get_list');
		Route::get('{user_id}/view_full','QaTargetController@single_user_target');
		Route::post('update_qa_target','QaTargetController@updae_target')->name('update_qa_target');
		
	});

	Route::post('upload_qa_target_month','AllocationController@upload_qa_target_month')->name('upload_qa_target_month');
	Route::post('upload_qa_target_daily','AllocationController@upload_qa_target_daily')->name('upload_qa_target_daily');
	
	Route::get('getDataProcess/{client_id}','ClientController@getDataProcess');
	Route::get('getDataLocation/{client_id}','ClientController@getDataLocation');
	Route::get('getDataPartner/{client_id}','ClientController@getDataPartner');
	Route::get('getClientList/{process_owner}','ClientController@getClientList');

});

Route::prefix('api')->namespace('Api')->group(function () {
   
    
	Route::post('login','UserController@login');
	Route::post('reset_password','UserController@reset_password');

	Route::post('welcome_dashboard','ClientController@welcome_dashboard');
	Route::post('detail_dashboard','ClientController@detail_dashboard');

	Route::post('get_qrc_lob_wise_welcome_dashboard','ClientController@get_qrc_lob_wise_welcome_dashboard');
	
	// filters 
	Route::post('get_partner_list_detail_dash','FileterController@get_partner_list_detail_dash');
	Route::post('get_location_lob_process_of_partner','FileterController@get_location_lob_process_of_partner');
	Route::post('get_process_cycle','FileterController@get_process_cycle');
	// filters
	//Route::post('add_product','api\ProcuctController@add_product');

	Route::get('client_detail','HomeController@client_detail_dashboard');
	Route::get('get_loged_in_client_partners','HomeController@get_loged_in_client_partners');
	Route::get('get_partner_process1/{partner_id}','HomeController@get_partner_process1');
	Route::get('get_partner_process/{partner_id}','HomeController@get_partner_process');
	Route::get('get_partner_locations/{partner_id}','HomeController@get_partner_locations');
	Route::get('get_partner_locations1/{partner_id}','HomeController@get_partner_locations1');
	Route::post('get_client_partner_dashboard_data','HomeController@get_client_partner_dashboard_data');
	
	Route::post('get_qtl_dashboard_data','HomeController@get_qtl_dashboard_data');
	Route::get('get_agent_report','HomeController@get_agent_report');	
	Route::get('get_partner_lob/{partner_id}','HomeController@get_partner_lob');	
	Route::get('get_partner_lob_1/{partner_id}','HomeController@get_partner_lob_1');
	
	Route::get('get_client_details/{company_id}','HomeController@get_client_details');
});
