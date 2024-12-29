<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                Log::info('RedirectIfAuthenticated: User is authenticated', ['user_id' => $user ? $user->id : 'null']);
                
                if ($user && $user->hasVerifiedEmail()) {
                    Log::info('RedirectIfAuthenticated: User email is verified, redirecting to home');
                    return redirect(RouteServiceProvider::HOME);
                } elseif ($user) {
                    Log::info('RedirectIfAuthenticated: User email is not verified, logging out and redirecting to login');
                    Auth::guard($guard)->logout();
                    return redirect()->route('login')
                        ->with('warning', 'Anda harus memverifikasi email Anda sebelum login. Silakan cek email Anda untuk link verifikasi.');
                }
            } else {
                Log::info('RedirectIfAuthenticated: User is not authenticated');
            }
        }

        return $next($request);
    }
}

