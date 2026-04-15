<?php

namespace App\Http\Controllers\MLM;

use App\Http\Controllers\Controller;
use App\Models\MlmUser;
use App\Models\MLMTree;
use App\Models\MLMTreeClosure;
use App\Models\SpillingPreference;
use App\Services\MLMTreePlacementService;
use App\Services\MLMClosureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function __construct(
        protected MLMTreePlacementService $placementService,
        protected MLMClosureService $closureService
    ) {}

    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        return view('admin.pages.mlm.register-user');
    }

    /**
     * Register new MLM user
     */
    public function register(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'user_name' => 'required|string|max:255|unique:mlm_users,user_name',
            'sponsor_username' => 'required|string|exists:mlm_users,user_name',
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|email|max:255|unique:mlm_users,email',
            'phone' => 'required|digits:10|unique:mlm_users,phone',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted',
        ]);

        DB::beginTransaction();
        try {
            // 1️⃣ Find Sponsor by Username
            $sponsor = MlmUser::where('user_name', $validated['sponsor_username'])->firstOrFail();
            
            if (!$sponsor->is_active || $sponsor->is_deleted) {
                return back()->withErrors(['sponsor_username' => 'Sponsor account is inactive or deleted.'])
                            ->withInput();
            }

            // 2️⃣ Generate Unique Track ID
            $trackId = $this->generateTrackId();

            // 3️⃣ Create MLM User
            $mlmUser = MlmUser::create([
                'user_name' => $validated['user_name'],
                'track_id' => $trackId,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'sponsor_id' => $sponsor->id,
                'position_in_sponsor_leg' => 'none', // Will be updated by placement service
                'membership_type' => 'CUSTOMER',
                'is_active' => true,
                'is_verified' => false,
                'is_deleted' => false,
            ]);

            // 4️⃣ Find Placement in Binary Tree
            $placement = $this->placementService->findPlacement($sponsor->id, $mlmUser->id);

            if (!$placement) {
                // If no placement found, rollback
                throw new \Exception('Unable to find placement in binary tree. Please contact admin.');
            }

            // 5️⃣ Update MLM User with position
            $mlmUser->update([
                'position_in_sponsor_leg' => $placement['position'],
            ]);

            // 6️⃣ Create Tree Node
            $treeNode = MLMTree::create([
                'mlm_user_id' => $mlmUser->id,
                'parent_id' => $placement['parent_id'],
                'position' => $placement['position'],
                'level' => $placement['level'],
                'package_id' => null,
                'business_volume' => 0,
                'earned_amount' => 0,
                'rank' => null,
                'registered_at' => now(),
            ]);

            // 7️⃣ Sync Closure Table
            $this->closureService->syncClosures($treeNode);

            // 8️⃣ Create Default Spilling Preference
            SpillingPreference::create([
                'mlm_user_id' => $mlmUser->id,
                'preference' => 'HOLDING_TANK',
            ]);

            DB::commit();

            return redirect()->route('mlm.register.form')
                ->with('success', "✅ User registered successfully! Username: {$mlmUser->user_name} | Track ID: {$trackId} | Position: {$placement['position']} (Level {$placement['level']})");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['registration' => 'Registration failed: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Generate unique track ID
     */
    private function generateTrackId(): string
    {
        $prefix = date('Y'); // 2024
        $random = strtoupper(Str::random(6)); // ABC123
        $timestamp = time(); // Unix timestamp
        
        return "TRK{$prefix}{$random}{$timestamp}";
    }
}