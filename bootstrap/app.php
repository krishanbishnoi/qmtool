<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        using: function () {
            // Load web routes with the App\Http\Controllers namespace so that
            // existing 'ControllerName@method' string-based routes continue to work.
            \Illuminate\Support\Facades\Route::middleware('web')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/web.php'));

            // Load api routes with prefix and namespace
            \Illuminate\Support\Facades\Route::middleware('api')
                ->prefix('api')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust proxies (replaces app/Http/Middleware/TrustProxies.php)
        $middleware->trustProxies(
            at: '*',
            headers: \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
                     \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
                     \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT |
                     \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO |
                     \Illuminate\Http\Request::HEADER_X_FORWARDED_AWS_ELB,
        );

        // CSRF exclusions (migrated from app/Http/Middleware/VerifyCsrfToken.php)
        $middleware->validateCsrfTokens(except: [
            'dashboard/test_excel',
            '/test_html',
            'login',
            'api/login',
            'api/reset_password',
            'api/welcome_dashboard',
            'api/get_partner_list_detail_dash',
            'api/get_location_lob_process_of_partner',
            'api/get_process_cycle',
            'api/detail_dashboard',
            'api/get_qrc_lob_wise_welcome_dashboard',
            'api/update_basic_data',
            'api/update_edit_data',
            'api/transfer_audit_from_temp_to_main_pool',
            'api/reply_rebuttal',
            'api/recording_list',
            'api/listenning_call_filters',
            'api/searching',
            'api/listning_call_feedback',
            'api/loginMail',
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
        ]);

        // Global middleware
        $middleware->append(\App\Http\Middleware\BlockMobileDevices::class);

        // Web middleware group additions
        $middleware->web(append: [
            \App\Http\Middleware\SetXFrameOptionsHeader::class,
            \App\Http\Middleware\SetHSTSHeader::class,
            \App\Http\Middleware\SetXContentTypeOptionsHeader::class,
            \App\Http\Middleware\SetReferrerPolicyHeader::class,
        ]);

        // Middleware aliases (replaces $routeMiddleware in the old Kernel)
        $middleware->alias([
            'auth'              => \App\Http\Middleware\Authenticate::class,
            'auth.basic'        => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'cache.headers'     => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can'               => \Illuminate\Auth\Middleware\Authorize::class,
            'guest'             => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'checkUserSession'  => \App\Http\Middleware\CheckUserSession::class,
            'signed'            => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle'          => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified'          => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Exceptions that should not be reported
        // $exceptions->dontReport([\Some\Exception::class]);

        // Inputs that are never flashed on validation exceptions
        $exceptions->dontFlash(['password', 'password_confirmation']);
    })
    ->create();
