<?php
namespace App\Http\Controllers\MLM;

use App\Http\Controllers\Controller;
use App\Mail\MlmActivationMail;
use App\Models\MlmUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MLMActivationController extends Controller
{
    public function resend($id)
    {
        try {
            $user = MlmUser::findOrFail($id);
            if ($user->is_verified) {
                return response()->json(['success' => false, 'message' => 'Already activated.']);
            }
            
            $user->update([
                'verification_token' => Str::random(60),
                'verification_expires' => now()->addHours(24),
            ]);
            
            Mail::to($user->email)->send(new MlmActivationMail(
                $user, 
                route('mlm.activate', ['token' => $user->verification_token])
            ));
            
            return response()->json(['success' => true, 'message' => 'Activation email sent.']);
        } catch (\Exception $e) {
            Log::error('Resend activation failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to send email.'], 500);
        }
    }

    public function activate(Request $request, $token)
    {
        $user = MlmUser::where('verification_token', $token)
            ->where('verification_expires', '>', now())->first();

        if (!$user) {
            return view('admin.pages.mlm.activation-error', ['message' => 'Invalid or expired link.']);
        }

        $user->update([
            'is_verified' => true,
            'is_active' => true,
            'verification_token' => null,
            'verification_expires' => null,
        ]);

        return view('admin.pages.mlm.activation-success', [
            'userName' => $user->user_name,
            'email' => $user->email
        ]);
    }
}