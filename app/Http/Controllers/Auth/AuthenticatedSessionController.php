<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
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
        // Check if running on localhost
        $isLocalhost = in_array($request->getHost(), ['localhost', '127.0.0.1', '::1']);
        
        // Only verify CAPTCHA if NOT on localhost
        if (!$isLocalhost) {
            // 1️⃣ Validate Turnstile CAPTCHA
            $request->validate([
                'cf-turnstile-response' => 'required',
            ]);

            // 2️⃣ Verify CAPTCHA with Cloudflare
            $response = Http::timeout(10)->asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => config('services.turnstile.secret'), // ✅ Fixed: use config()
                'response' => $request->input('cf-turnstile-response'),
                'remoteip' => $request->ip(),
            ]);
Log::info('Login Attempt:', [
    'email' => $request->input('email'),
    'captcha_token' => $request->input('cf-turnstile-response') ? 'Present' : 'MISSING',
    'is_localhost' => $isLocalhost,
]);
            $captchaResult = $response->json();
            if (!($captchaResult['success'] ?? false)) {
                               
                return back()->withErrors(['captcha' => 'CAPTCHA verification failed.'])->withInput();
            }
        }

        // 3️⃣ Attempt authentication
        $request->authenticate();

        // 4️⃣ Regenerate session to prevent fixation
        $request->session()->regenerate();

        // 5️⃣ Redirect to intended page or dashboard
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