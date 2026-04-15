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
        Schema::create('mlm_tree_closures', function (Blueprint $table) {
            // 🔹 Composite Primary Key: ancestor + descendant (both reference mlm_trees)
            $table->foreignId('ancestor_id')->constrained('mlm_trees')->cascadeOnDelete();
            $table->foreignId('descendant_id')->constrained('mlm_trees')->cascadeOnDelete();
            $table->unsignedInteger('depth'); // 0 = self, 1 = direct child, 2 = grandchild...
            
            $table->primary(['ancestor_id', 'descendant_id'], 'pk_closure');
            
            // 🔹 Indexes for fast ancestor/descendant queries
            $table->index(['ancestor_id'], 'idx_closure_ancestor');
            $table->index(['descendant_id'], 'idx_closure_descendant');
            $table->index(['descendant_id', 'depth'], 'idx_closure_desc_depth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlm_tree_closures');
    }
};
