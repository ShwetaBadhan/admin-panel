<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CaptchaSetting;
use Illuminate\Http\Request;

class CaptchaSettingController extends Controller
{
    // app/Http/Controllers/CaptchaSettingController.php

    public function index()
    {
        $cloudflare = CaptchaSetting::firstOrCreate(
            ['type' => 'cloudflare'],
            ['site_key' => '', 'secret_key' => '', 'domain' => '', 'cloudflare_active' => false, 'google_active' => false]
        );

        $google = CaptchaSetting::firstOrCreate(
            ['type' => 'google'],
            ['site_key' => '', 'secret_key' => '', 'domain' => '', 'cloudflare_active' => false, 'google_active' => false]
        );

        return view('admin.pages.general-setting', compact('cloudflare', 'google'));
    }

    public function update(Request $request)
    {
        $type = $request->input('captcha_type');

        if (!in_array($type, ['cloudflare', 'google'])) {
            return back()->with('error', 'Invalid captcha type');
        }

        $request->validate([
            "{$type}_site_key"  => 'required|string',
            "{$type}_secret_key" => 'required|string',
            "{$type}_domain"    => 'nullable|string',
        ]);

        $data = [
            'site_key'  => $request->{"{$type}_site_key"},
            'secret_key' => $request->{"{$type}_secret_key"},
            'domain'    => $request->{"{$type}_domain"},
        ];

        // Save independent active status
        if ($type === 'cloudflare') {
            $data['cloudflare_active'] = $request->has('cloudflare_status');
        } else {
            $data['google_active'] = $request->has('google_status');
        }

        CaptchaSetting::updateOrCreate(['type' => $type], $data);

        return redirect()->route('general-setting')
            ->with('success', ucfirst($type) . ' captcha settings saved successfully!');
    }
}
