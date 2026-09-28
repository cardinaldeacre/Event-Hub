<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventSlot;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@mumtaz.id'],
            ['name' => 'Admin Mumtaz', 'password' => bcrypt('password')]
        );

        $event = Event::create([
            'user_id' => $admin->id,
            'title' => 'Tech Workshop & Career Talk 2026',
            'slug' => 'tech-workshop-2026',
            'description' => 'Persiapan karir & deep dive Laravel 13 backend architecture.',
            'location' => 'Auditorium PT Mumtaz Teknologi Indonesia',
            'is_active' => true,
        ]);

        // Slot 1
        EventSlot::create([
            'event_id' => $event->id,
            'start_time' => now()->addDays(1)->setTime(9, 0, 0),
            'end_time' => now()->addDays(1)->setTime(12, 0),
            'capacity' => 5,
            'booked_count' => 0,
        ]);
    }
}