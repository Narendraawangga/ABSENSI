<?php

namespace Database\Seeders;

use App\Models\InternProfile;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Administrator / Pembimbing Magang',
        ]);

        $internRole = Role::create([
            'name' => 'Intern',
            'slug' => 'intern',
            'description' => 'Anak Magang / User',
        ]);

        // Create Default Work Schedule
        WorkSchedule::create([
            'name' => 'Jadwal Normal',
            'time_in' => '08:00:00',
            'time_out' => '17:00:00',
            'late_tolerance' => 15,
            'is_default' => true,
        ]);

        // Create Basic Settings
        Setting::insert([
            ['key' => 'app_name', 'value' => 'Intern Management System', 'type' => 'text'],
            ['key' => 'company_name', 'value' => 'PT Teknologi Masa Depan', 'type' => 'text'],
        ]);

        // Create Admin User
        $admin = User::create([
            'role_id' => $adminRole->id,
            'name' => 'Admin Pembimbing',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        // Create Intern User
        $intern = User::create([
            'role_id' => $internRole->id,
            'name' => 'Narendra',
            'username' => 'narendra',
            'email' => 'narendra@example.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        InternProfile::create([
            'user_id' => $intern->id,
            'nim' => '12345678',
            'university' => 'Universitas Contoh',
            'major' => 'Teknik Informatika',
            'phone' => '081234567890',
            'division' => 'Software Engineering',
            'supervisor_id' => $admin->id,
            'start_date' => Carbon::now()->startOfMonth(),
            'end_date' => Carbon::now()->addMonths(3),
        ]);

        // Let's create another intern
        $intern2 = User::create([
            'role_id' => $internRole->id,
            'name' => 'Sinta Magang',
            'username' => 'sinta',
            'email' => 'sinta@example.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        InternProfile::create([
            'user_id' => $intern2->id,
            'nim' => '87654321',
            'university' => 'Universitas Teknologi',
            'major' => 'Desain Komunikasi Visual',
            'phone' => '081987654321',
            'division' => 'UI/UX Design',
            'supervisor_id' => $admin->id,
            'start_date' => Carbon::now()->startOfMonth(),
            'end_date' => Carbon::now()->addMonths(6),
        ]);
    }
}
