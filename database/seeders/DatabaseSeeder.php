<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // call seed
        $this->call([
            RoleAndPermissionSeeder::class,
            SchoolClassSeeder::class,
            SchoolSubjectSeeder::class,
            UserSeeder::class,
        ]);
    }
}
