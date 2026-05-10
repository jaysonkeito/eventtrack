<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Venue;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Default Admin ────────────────────────────────────
        User::create([
            'first_name'        => 'System',
            'last_name'         => 'Admin',
            'email'             => 'admin@eventtrack.com',
            'password'          => Hash::make('Admin@1234'),
            'role'              => 'admin',
            'status'            => 'active',
            'email_verified_at' => now(),
        ]);

        // ── Default Organizer (for testing) ──────────────────
        User::create([
            'first_name'        => 'Juan',
            'last_name'         => 'Organizer',
            'email'             => 'organizer@eventtrack.com',
            'password'          => Hash::make('Organizer@1234'),
            'role'              => 'organizer',
            'status'            => 'active',
            'email_verified_at' => now(),
        ]);

        // ── Default Attendee (for testing) ───────────────────
        User::create([
            'first_name'        => 'Maria',
            'last_name'         => 'Attendee',
            'email'             => 'attendee@eventtrack.com',
            'password'          => Hash::make('Attendee@1234'),
            'role'              => 'attendee',
            'status'            => 'active',
            'email_verified_at' => now(),
        ]);

        // ── Categories ───────────────────────────────────────
        $categories = [
            ['name' => 'Seminar',     'description' => 'Educational talks and presentations',         'color_hex' => '#1A56A0'],
            ['name' => 'Workshop',    'description' => 'Hands-on training and skill-building events', 'color_hex' => '#16A34A'],
            ['name' => 'Sports',      'description' => 'Athletic competitions and sports events',      'color_hex' => '#DC2626'],
            ['name' => 'Cultural',    'description' => 'Arts, culture, and heritage events',          'color_hex' => '#7C3AED'],
            ['name' => 'Conference',  'description' => 'Professional and academic conferences',       'color_hex' => '#0891B2'],
            ['name' => 'Orientation', 'description' => 'Onboarding and welcome events',               'color_hex' => '#EA580C'],
            ['name' => 'Community',   'description' => 'Outreach and community service events',       'color_hex' => '#65A30D'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // ── Venues ───────────────────────────────────────────
        $venues = [
            ['name' => 'Main Auditorium',     'address' => 'Building A, Ground Floor', 'city' => 'Cebu City', 'capacity' => 500],
            ['name' => 'Function Hall',       'address' => 'Building B, 2nd Floor',    'city' => 'Cebu City', 'capacity' => 200],
            ['name' => 'Open Quadrangle',     'address' => 'Campus Center',            'city' => 'Cebu City', 'capacity' => 1000],
            ['name' => 'Computer Laboratory', 'address' => 'Building C, Room 301',     'city' => 'Cebu City', 'capacity' => 50],
        ];

        foreach ($venues as $venue) {
            Venue::create($venue);
        }
    }
}
