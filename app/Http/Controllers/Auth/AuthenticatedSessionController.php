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

        $role = $request->user()->role;

        return match ($role) {
            'admin' => redirect()->intended(route('admin', absolute: false)),
            'director' => redirect()->intended(route('director', absolute: false)),
            'secretaria' => redirect()->intended(route('secretaria', absolute: false)),
            'docente' => redirect()->intended(route('docente', absolute: false)),
            'alumno' => redirect()->intended(route('alumno', absolute: false)),
            'padre' => redirect()->intended(route('padre', absolute: false)),
            'psicologo' => redirect()->intended(route('psicologo', absolute: false)),
            default => redirect()->intended(route('dashboard', absolute: false)),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
