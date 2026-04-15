<?php

namespace App\Http\Controllers\MLM;

use App\Http\Controllers\Controller;
use App\Mail\MlmActivationMail;
use App\Models\MlmUser;
use App\Models\SpillingPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MLMUserController extends Controller
{
    /**
     * Display MLM users list
     */
    public function index()
    {
        $users = MlmUser::with(['sponsor', 'tree'])
            ->where('is_deleted', false)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.pages.mlm.register-users', compact('users'));
    }

    /**
     * Store new MLM user + send activation email
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sponsor_username' => 'required|string|exists:mlm_users,user_name',
            'user_name' => 'required|string|max:255|unique:mlm_users,user_name',
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|email|max:255|unique:mlm_users,email',
            'phone' => 'required|digits:10|unique:mlm_users,phone',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted',
        ], [
            'sponsor_username.exists' => 'Sponsor username not found.',
        ]);

        DB::beginTransaction();
        try {
            // 1️⃣ Find Sponsor
            $sponsor = MlmUser::where('user_name', $validated['sponsor_username'])
                ->where('is_active', true)
                ->where('is_deleted', false)
                ->firstOrFail();

            // 2️⃣ Generate Track ID
            $trackId = 'TRK' . date('Y') . strtoupper(Str::random(6)) . time();

            // 3️⃣ Create MLM User (inactive until email verified)
            $mlmUser = MlmUser::create([
                'user_name' => $validated['user_name'],
                'track_id' => $trackId,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'sponsor_id' => $sponsor->id,
                'position_in_sponsor_leg' => 'none',
                'membership_type' => 'CUSTOMER',
                'is_active' => false,
                'is_verified' => false,
                'is_deleted' => false,
                'verification_token' => Str::random(60),
                'verification_expires' => now()->addHours(24),
            ]);

            // 4️⃣ Default Spilling Preference
            SpillingPreference::create([
                'mlm_user_id' => $mlmUser->id,
                'preference' => 'HOLDING_TANK',
            ]);

            // 5️⃣ Generate Activation URL
            $activationUrl = route('mlm.activate', ['token' => $mlmUser->verification_token]);

            // 6️⃣ Send Activation Email (Direct, NOT queued - for testing)
            try {
                Mail::to($mlmUser->email)->send(new MlmActivationMail($mlmUser, $activationUrl));
                Log::info("Activation email sent to {$mlmUser->email}");
            } catch (\Exception $e) {
                Log::error("Email failed for {$mlmUser->email}: " . $e->getMessage());
                // Don't rollback - user still created, admin can resend later
            }

            DB::commit();

            return redirect()->route('mlm-users.index')
                ->with('success', "✅ User registered! Activation email sent to {$mlmUser->email}");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MLM Registration Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Update MLM user
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|email|max:255|unique:mlm_users,email,'.$id,
            'phone' => 'required|digits:10|unique:mlm_users,phone,'.$id,
            'membership_type' => 'in:CUSTOMER,PREFERRED_CUSTOMER,DIRECT_SELLER',
            'is_active' => 'boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $mlmUser = MlmUser::findOrFail($id);
        
        $updateData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'membership_type' => $validated['membership_type'],
            'is_active' => $validated['is_active'] ?? $mlmUser->is_active,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $mlmUser->update($updateData);

        return redirect()->route('mlm-users.index')
            ->with('success', "✅ User updated: {$mlmUser->user_name}");
    }

    /**
     * Soft delete MLM user
     */
    public function destroy($id)
    {
        $mlmUser = MlmUser::findOrFail($id);
        $mlmUser->update(['is_deleted' => true, 'is_active' => false]);

        return redirect()->route('mlm-users.index')
            ->with('success', "✅ User soft-deleted: {$mlmUser->user_name}");
    }

    /**
     * Resend activation email
     */
    public function resendActivation($id)
    {
        try {
            $mlmUser = MlmUser::findOrFail($id);
            
            if ($mlmUser->is_verified) {
                return response()->json(['success' => false, 'message' => 'User already activated.']);
            }

            // Refresh token
            $mlmUser->update([
                'verification_token' => Str::random(60),
                'verification_expires' => now()->addHours(24),
            ]);

            $activationUrl = route('mlm.activate', ['token' => $mlmUser->verification_token]);

            Mail::to($mlmUser->email)->send(new MlmActivationMail($mlmUser, $activationUrl));

            return response()->json(['success' => true, 'message' => 'Activation email sent.']);
            
        } catch (\Exception $e) {
            Log::error('Resend activation failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to send email.'], 500);
        }
    }

    /**
     * Activate user via email link
     */
    public function activate(Request $request, $token)
    {
        $user = MlmUser::where('verification_token', $token)
            ->where('verification_expires', '>', now())
            ->first();

        if (!$user) {
            return view('admin.pages.mlm.activation-error', [
                'message' => 'Invalid or expired activation link. Please contact admin.'
            ]);
        }

        // Activate user
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