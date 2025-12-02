<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $pharmacistRole = Role::firstOrCreate(['name' => 'pharmacist']);
        $doctorRole = Role::firstOrCreate(['name' => 'doctor']);

        // Create users and assign roles
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@carevance.com',
            'password' => bcrypt('password'),
        ])->assignRole($adminRole);

        User::factory()->create([
            'name' => 'Staff User',
            'email' => 'staff@carevance.com',
            'password' => bcrypt('password'),
        ])->assignRole($staffRole);

        User::factory()->create([
            'name' => 'Pharmacist User',
            'email' => 'pharmacist@carevance.com',
            'password' => bcrypt('password'),
        ])->assignRole($pharmacistRole);

        User::factory()->create([
            'name' => 'Doctor User',
            'email' => 'doctor@carevance.com',
            'password' => bcrypt('password'),
        ])->assignRole($doctorRole);
    }
}
