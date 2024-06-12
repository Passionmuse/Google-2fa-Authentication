<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function authenticated(Request $request, $user)
{
    if ($user->uses_two_factor_auth) {
        $google2fa = new Google2FA();

        if ($request->session()->has('2fa_passed')) {
            $request->session()->forget('2fa_passed');
        }

        $request->session()->put('2fa:user:id', $user->id);
        $request->session()->put('2fa:auth:attempt', true);
        $request->session()->put('2fa:auth:remember', $request->has('remember'));

        $otp_secret = $user->google2fa_secret;
        $one_time_password = $google2fa->getCurrentOtp($otp_secret);

        return redirect()->route('2fa')->with('one_time_password', $one_time_password);
    }

    return redirect()->intended($this->redirectPath());
}
}
