// database/migrations/xxxx_xx_xx_create_settings_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('email');
            $table->string('location');
            $table->string('phone1');
            $table->string('phone2')->nullable();
            $table->longText('about')->nullable();
            $table->json('social_links')->nullable(); // Facebook, Instagram, etc.
            $table->timestamps();
        });

        // Insert default row so you always have one record
        DB::table('settings')->insert([
            'company_name' => 'Continuity Care',
            'email' => 'info@Continuity Care.com',
            'location' => 'Haryana, India',
            'phone1' => '+91 98765 43210',
            'about' => 'Welcome to Continuity Care – your trusted healthcare partner.',
            'social_links' => json_encode([
                'facebook' => '',
                'instagram' => '',
                'linkedin' => '',
                'twitter' => '',
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
