<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user  = Auth::user();

        /**
         * PERBAIKAN UTAMA:
         * Database kamu TIDAK punya kolom `role`,
         * jadi role kita tentukan dari email (sesuai data yang kamu punya).
         *
         * Kalau nanti kamu sudah punya kolom role, tinggal ganti logika ini.
         */
        $email = strtolower($user->email ?? '');

        // SUPERADMIN: jangan pakai intended (biar selalu ke landing yang kamu mau)
        if ($email === 'superadmin@nebeng.com') {
            $request->session()->forget('url.intended');
            return redirect('/superadmin');
        }

        // Role lain boleh pakai intended
        if ($email === 'admin@nebeng.com') {
            return redirect()->intended('/admin');
        }

        if ($email === 'finance@nebeng.com') {
            return redirect()->intended('/finance');
        }

        // default (breeze)
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // setelah logout selalu ke login
        return redirect('/login');
    }
}
