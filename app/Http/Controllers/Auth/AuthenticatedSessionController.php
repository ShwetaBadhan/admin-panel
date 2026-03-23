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
        return view('auth.login'); // Blade view for login page
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1️⃣ Validate Turnstile CAPTCHA
        $request->validate([
            'cf-turnstile-response' => 'required',
        ]);

        // 2️⃣ Verify CAPTCHA with Cloudflare
        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => env('TURNSTILE_SECRET'),
            // Add your secret in .env
            'response' => $request->input('cf-turnstile-response'),
            'remoteip' => $request->ip(),
        ]);

        $captchaResult = $response->json();
        if (!($captchaResult['success'] ?? false)) {
            return back()->withErrors(['captcha' => 'CAPTCHA verification failed.']);
        }
        //         $captchaResult = $response->json();
// dd($captchaResult);


        // 3️⃣ Attempt authentication
        $request->authenticate();

        // 4️⃣ Regenerate session to prevent fixation
        $request->session()->regenerate();

        // 5️⃣ Redirect to intended page or dashboard
        return redirect()->intended('/dashboard'); // Make sure route /dashboard exists
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Redirect to homepage after logout
    }
}
