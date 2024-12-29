<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        Log::info('Login attempt', ['email' => $credentials['email']]);
        
        $user = User::where('email', $credentials['email'])->first();
        if ($user) {
            if (!$user->hasVerifiedEmail()) {
                Log::info('Login attempt by unverified user', ['user_id' => $user->id]);
                return 'unverified';
            }

            if (Hash::check($credentials['password'], $user->password)) {
                Auth::login($user, $request->filled('remember'));
                Log::info('Login successful', ['user_id' => $user->id]);
                return true;
            } else {
                Log::info('Password mismatch', ['user_id' => $user->id]);
                return 'invalid';
            }
        } else {
            Log::info('User not found');
            return 'invalid';
        }
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $result = $this->attemptLogin($request);

        Log::info('Login result', ['result' => $result]);

        if ($result === 'unverified') {
            return redirect()->route('login')
                ->withInput($request->only($this->username(), 'remember'))
                ->with('warning', 'Anda harus memverifikasi email Anda sebelum login. Silakan cek email Anda untuk link verifikasi.');
        } elseif ($result === 'invalid') {
            return redirect()->route('login')
                ->withInput($request->only($this->username(), 'remember'))
                ->withErrors([
                    $this->username() => [trans('auth.failed')],
                ]);
        } elseif ($result === true) {
            return redirect()->intended($this->redirectPath());
        }

        return $this->sendLockoutResponse($request);
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user) {
            Log::info('Login successful and email verified', ['user_id' => $user->id]);
        } else {
            Log::error('Authenticated method called with null user');
        }
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $result = $this->attemptLogin($request);

        if ($result === true) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return redirect()->route('login')
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => [trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ])],
            ]);
    }
}

