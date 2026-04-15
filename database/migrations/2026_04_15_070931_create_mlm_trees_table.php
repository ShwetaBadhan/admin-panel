<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('mlm_trees', function (Blueprint $table) {
            $table->id();
            
            // 🔗 Core Links (within MLM system only)
            $table->foreignId('mlm_user_id')->unique()->constrained('mlm_users')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('mlm_trees')->nullOnDelete(); // Parent node in tree
            
            // 🔹 Binary Tree Structure
            $table->enum('position', ['left', 'right', 'none'])->default('none');
            $table->unsignedInteger('level')->default(0); // Depth from root (0 = root)
            
            // 🔹 Package & Volume
            $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
            $table->decimal('business_volume', 18, 6)->default(0); // BV for commission calc
            $table->decimal('earned_amount', 18, 6)->default(0); // Total earned till now
            
            // 🔹 Rank & Metadata
            $table->string('rank')->nullable();
            $table->timestamp('registered_at')->useCurrent();
            
            $table->timestamps();
            
            // 🔹 Unique Constraints (Critical for Binary Logic)
            $table->unique(['parent_id', 'position'], 'unique_parent_position'); // One child per leg per parent
            $table->unique(['mlm_user_id'], 'unique_user_in_tree'); // One node per MLM user
            
            // 🔹 Indexes for fast tree queries
            $table->index(['mlm_user_id'], 'idx_tree_user');
            $table->index(['parent_id'], 'idx_tree_parent');
            $table->index(['parent_id', 'position'], 'idx_tree_parent_pos');
            $table->index(['level'], 'idx_tree_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlm_trees');
    }
};
