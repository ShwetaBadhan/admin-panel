<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        

        // to create  user
        $this->call(AdminUserSeeder::class);

        // to create seo pages 
        $this->call(SeoPagesSeeder::class);
        // to create permission pages 
        $this->call(PermissionSeeder::class);
    }
}
