<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [ 'dashboard/test_excel',
                            '/test_html','login',
                            'api/login',
                            'api/reset_password',
                            'api/welcome_dashboard',
                            'api/get_partner_list_detail_dash',
                            'api/get_location_lob_process_of_partner',
                            'api/get_process_cycle','api/detail_dashboard',
                            'api/get_qrc_lob_wise_welcome_dashboard',
                            'api/update_basic_data','api/update_edit_data' ,
                            'api/transfer_audit_from_temp_to_main_pool' , 
                            'api/reply_rebuttal','api/recording_list',
                            'api/listenning_call_filters','api/searching',
                            'api/listning_call_feedback','api/loginMail',
                            'api/notification_feedback',
                            'api/parameter_wise_details',
                            'api/accepted',
                            'api/plan_of_action',
                            'api/Rebuttal',
                            'api/parameter_wise_plan_of_action',
                            'api/agent_dashboard',
                            'api/auditor_dashboard',
                            'api/auditor_process_list',
                            'api/audit_page_agent_list',
                            'api/audit_page_render_sheet',
                            'api/store_audit',
                            'api/get_rca1_by_rca_mode_id',
                            'api/get_rca2_by_rca1_id',
                            'api/get_rca3_by_rca2_id',
                            'api/getTotalData',
                            'api/auditors_audit',
                            'api/diactivate_users',
                            'allocation/store_audit',
                            'verify_otp',
                            
        //
    ];
}
