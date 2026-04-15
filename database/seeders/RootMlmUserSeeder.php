<?php

namespace Database\Seeders;

use App\Models\MlmUser;
use App\Models\MLMTree;
use App\Models\MLMTreeClosure;
use App\Models\SpillingPreference;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RootMlmUserSeeder extends Seeder
{
    /**
     * Root user credentials (save these securely!)
     */
    private const ROOT_USERNAME = 'Founder01';
    private const ROOT_TRACK_ID = 'ROOT01'; // 30 chars
    private const ROOT_EMAIL = 'founder@mlm.local';
    private const ROOT_PHONE = '9999999999';
    private const ROOT_PASSWORD = 'Founder@2024'; // 🔐 Change in production!

    public function run(): void
    {
        $this->command->info("🌱 Creating Root MLM User: " . self::ROOT_USERNAME);

        // 🔹 Check if root already exists
        $existingRoot = MlmUser::where('user_name', self::ROOT_USERNAME)->first();
        
        if ($existingRoot) {
            $this->command->warn("⚠️ Root user '" . self::ROOT_USERNAME . "' already exists. Skipping.");
            $this->command->info("   Root ID: {$existingRoot->id} | Track ID: {$existingRoot->track_id}");
            return;
        }

        // 🔹 1. Create Root MLM User in mlm_users table
        $rootUser = MlmUser::create([
            'user_name' => self::ROOT_USERNAME,
            'track_id' => self::ROOT_TRACK_ID,
            'first_name' => 'System',
            'last_name' => 'Founder',
            'email' => self::ROOT_EMAIL,
            'phone' => self::ROOT_PHONE,
            'password' => Hash::make(self::ROOT_PASSWORD),
            
            // MLM Fields
            'sponsor_id' => null, // No sponsor = ROOT
            'position_in_sponsor_leg' => 'none', // Top of tree
            'membership_type' => 'DIRECT_SELLER', // Root has highest membership
            'is_active' => true,
            'is_verified' => true,
            'is_deleted' => false,
            'is_payout_active' => true,
        ]);

        $this->command->info("✅ MLM User created: ID = {$rootUser->id}");

        // 🔹 2. Create Root Node in mlm_trees table (Binary Tree Structure)
        $rootTree = MLMTree::create([
            'mlm_user_id' => $rootUser->id,
            'parent_id' => null, // No parent = ROOT of tree
            'position' => 'none', // Root position
            'level' => 0, // Depth 0 = top of tree
            'package_id' => null, // Root may not have a package
            'business_volume' => 0,
            'earned_amount' => 0,
            'rank' => 'FOUNDER',
            'registered_at' => now(),
        ]);

        $this->command->info("✅ Tree node created: ID = {$rootTree->id} | Level = 0 | Position = none");

        // 🔹 3. Create Self-Reference in Closure Table (for fast genealogy queries)
        MLMTreeClosure::create([
            'ancestor_id' => $rootTree->id,
            'descendant_id' => $rootTree->id,
            'depth' => 0, // Self-reference
        ]);

        $this->command->info("✅ Closure entry created: Self-reference (depth = 0)");

        // 🔹 4. Create Default Spilling Preference (Optional but recommended)
        SpillingPreference::create([
            'mlm_user_id' => $rootUser->id,
            'preference' => 'HOLDING_TANK', // Root doesn't spill, but set default
        ]);

        $this->command->info("✅ Spilling preference set: HOLDING_TANK");

        // 🔹 5. Display Important Info for Developer
        $this->command->newLine();
        $this->command->info("🎉 ROOT MLM USER CREATED SUCCESSFULLY!");
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->command->info("📋 Login Credentials (Save Securely):");
        $this->command->info("   Username : " . self::ROOT_USERNAME);
        $this->command->info("   Password : " . self::ROOT_PASSWORD);
        $this->command->info("   Email    : " . self::ROOT_EMAIL);
        $this->command->info("   Phone    : " . self::ROOT_PHONE);
        $this->command->newLine();
        $this->command->info("🔗 Database IDs (for sponsor_id reference):");
        $this->command->info("   mlm_users.id      = {$rootUser->id}");
        $this->command->info("   mlm_trees.id      = {$rootTree->id}");
        $this->command->info("   track_id          = " . self::ROOT_TRACK_ID);
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->command->newLine();
        $this->command->info("💡 Use this root user's ID as sponsor_id for new registrations:");
        $this->command->info("   POST /api/mlm/register");
        $this->command->info("   { \"sponsor_id\": {$rootUser->id}, ... }");
        $this->command->info("   → New users will auto-place under Founder01's tree!");
    }
}