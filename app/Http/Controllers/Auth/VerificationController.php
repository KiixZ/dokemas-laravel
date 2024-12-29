<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    use VerifiesEmails;

    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            Log::error('User not found during email verification', ['user_id' => $request->route('id')]);
            return redirect($this->redirectPath())->with('error', 'User tidak ditemukan.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath())->with('warning', 'Email sudah diverifikasi sebelumnya.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect($this->redirectPath())->with('status', 'Email berhasil diverifikasi. Silakan login.');
    }

    protected function redirectTo()
    {
        return '/login?verified=1';
    }
}

