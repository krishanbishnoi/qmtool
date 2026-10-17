<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckUserSession
{
    public function handle($request, Closure $next)
    {
        // dd("dsfsdfs");
        if (Auth::check()) {
            $user = Auth::user();
            // Check if the session ID matches
            if ($user->session_id !== session()->get('uniqueKey')) {
                Auth::logout(); // Log out if the session ID doesn't match
                // Session::flash('flash_notice', 'Your session has expired due to a new login in another system or browser. Please log in again to continue.');
                return redirect('/')->with('error', 'Your session has expired due to a new login in another system or browser. Please log in again to continue.');
            }
        }

        return $next($request);
    }
}
