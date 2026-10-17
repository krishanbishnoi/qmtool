<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\LoginAttempt;
use App\LoggedUser;
use App\Token;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     */
    protected string $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => __('auth.throttle', ['seconds' => $seconds, 'minutes' => ceil($seconds / 60)]),
            ]);
        }

        if (Auth::validate(['email' => $request->email, 'password' => $request->password])) {
            $user_first = User::where('email', $request->email)->first();

            if ($user_first->two_auth == 1) {
                if ($user_first->session_id && $user_first->session_id !== session()->getId()) {
                    session()->flush();
                    Auth::logout();
                }
            }

            $user_first = User::where('email', $request->email)->first();
            $uniqueKey  = 'session_key_' . uniqid() . '_' . Str::random(10);
            session()->put('uniqueKey', $uniqueKey);
            $value = session('uniqueKey');

            $user = Auth::getProvider()->retrieveByCredentials(
                ['email' => $request->email, 'password' => $request->password]
            );

            if ($user_first->two_auth == 1 && $user_first->status == 1) {
                $otp = rand(100000, 999999);

                $user_email = $user_first->email;
                Mail::send('mail.otp_mail', ['otp' => $otp], function ($message) use ($user_email) {
                    $message->from('qaviews-noreply@qdegrees.com', 'Qdegrees')
                            ->to($user_email)
                            ->subject('Verification OTP');
                });

                $token           = new Token;
                $token->user_id  = $user_first->id;
                $token->token    = $otp;
                $token->save();

                $result = json_decode(sendOtp($user_first->mobile, $otp), true);
                if ($result && isset($result['type']) && $result['type'] == 'success') {
                    $token2          = new Token;
                    $token2->user_id = $user_first->id;
                    $token2->token   = $otp;
                    $token2->save();
                    $user_first->session_id = $value;
                    $user_first->save();
                }

                return response()->json(['status' => 201], 201);
            } else {
                if ($user_first->status == 1 && Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
                    if ($user_first->two_auth == 0) {
                        $user_first->session_id = $value;
                        $user_first->save();
                    }

                    RateLimiter::clear($throttleKey);
                    $request->session()->regenerate();

                    $loggeduser             = new LoggedUser;
                    $loggeduser->user_id    = Auth::user()->id;
                    $loggeduser->logged_ip  = $request->getClientIp();
                    $loggeduser->save();

                    return redirect()->intended($this->redirectTo);
                } else {
                    RateLimiter::hit($throttleKey);

                    if ($user_first->status == 0) {
                        return response()->json(['status' => 423, 'message' => 'Email not verified, please verify your email...'], 423);
                    }
                    if ($user_first->status == 99) {
                        return response()->json(['status' => 424, 'message' => 'Your account has been deactivated by admin, please contact QuoteNow Admin.'], 424);
                    }
                }
            }
        }

        RateLimiter::hit($throttleKey);

        $loginAttempt             = new LoginAttempt;
        $loginAttempt->email      = $request->email;
        $loginAttempt->ip_address = $request->getClientIp();
        $loginAttempt->save();

        throw ValidationException::withMessages([
            'email' => [__('auth.failed')],
        ]);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Verify OTP for two-factor login.
     */
    public function verify_otp(Request $request)
    {
        $user = Token::where('token', $request->otp)->with('user')->get();

        if (count($user) === 0) {
            return response()->json(['massage' => 'We Did not Verify You'], 200);
        }

        $data = $user[0];
        Token::where('token', $request->otp)->delete();
        Auth::loginUsingId($data->user_id, true);

        $uniqueKey = 'session_key_' . uniqid() . '_' . Str::random(10);
        session()->put('uniqueKey', $uniqueKey);
        $value                  = session('uniqueKey');
        $data->user->session_id = $value;
        $data->user->save();

        return response()->json(['status' => 201], 201);
    }
}
