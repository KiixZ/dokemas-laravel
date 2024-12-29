<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
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
        $user = User::where('email', $request->email)->first();
        
        Log::info('Login attempt', [
            'email' => $request->email,
            'user_exists' => $user ? 'Yes' : 'No',
            'password_correct' => $user && Hash::check($request->password, $user->password) ? 'Yes' : 'No'
        ]);

        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    protected function authenticated(Request $request, $user)
    {
        Log::info('User authenticated', ['user_id' => $user->id, 'email' => $user->email]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        Log::info('User logging out', ['user_id' => $user->id, 'email' => $user->email]);
        
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/');
    }
}

