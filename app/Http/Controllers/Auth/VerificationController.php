<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Where to redirect users after verification.
     */
    protected string $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('auth')->except(['verify', 'resend']);
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Show the email verification notice.
     */
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect($this->redirectTo)
            : view('auth.verify');
    }

    /**
     * Mark the authenticated user's email as verified.
     */
    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        if (! $user) {
            return abort(404);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectTo);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        $user->status = 1;
        $user->save();

        return redirect($this->redirectTo)->with('verified', true);
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json(['status' => 404, 'message' => 'User not found.'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectTo);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['status' => 200, 'message' => 'Link sent to your email.'], 200);
    }
}
