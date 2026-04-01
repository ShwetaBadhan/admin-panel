<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
        // Validate CAPTCHA response is present
        $request->validate([
            'cf-turnstile-response' => 'required',
        ]);

        // Verify CAPTCHA with Cloudflare Turnstile
        $response = Http::timeout(10)->asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => config('services.turnstile.secret'),
            'response' => $request->input('cf-turnstile-response'),
            'remoteip' => $request->ip(),
        ]);

        $result = $response->json();

        // Check if CAPTCHA verification succeeded
        if (!($result['success'] ?? false)) {
            return back()
                ->withErrors(['captcha' => 'CAPTCHA verification failed. Please try again.'])
                ->withInput();
        }

        // Attempt to authenticate the user
        $request->authenticate();

        // Regenerate session to prevent fixation attacks
        $request->session()->regenerate();

        // Redirect to intended page or dashboard
        return redirect()->intended('/dashboard');
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