<?php

namespace App\Helpers;


use App\UserLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogHelper
{
    // Save generic logs
    public static function saveLog($action, $details = null)
    {
        UserLog::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'ip_address' => request()->ip(),
            'action' => $action,
            'details' => $details
        ]);
    }

    // Save login log with time
    public static function saveLoginLog()
    {
        $log = UserLog::create([
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'action' => 'User Login',
            'details' => 'User logged in successfully.',
            'log_in_time' => now()
        ]);

        // Store log ID in session to update later for logout
        Session::put('login_log_id', $log->id);
    }

    // Save logout log with time
    public static function saveLogoutLog()
    {
        if (Session::has('login_log_id')) {
            $log = UserLog::find(Session::get('login_log_id'));

            if ($log) {
                $log->update(['log_out_time' => now(), 'action' => 'User Logout']);
            }
            
            // Remove session data
            Session::forget('login_log_id');
        }
    }
}
