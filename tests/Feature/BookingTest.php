<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_succes_booking(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create();

        $event = Event::create([
            'user_id' => $admin->id,
            'title' => 'Laravel 13 Converence',
            'slug' => 'laravel-13-conference',
            'description' => 'Deep dive backend architecture',
            'location' => 'Jakarta',
        ]);

        $slot = EventSlot::create([
            'event_id' => $event->id,
            'start_time' => now()->addDays(1),
            'end_time' => now()->addDays(1)->addHours(2),
            'capacity' => 100,
            'booked_count' => 0,
        ]);

        $response = $this->actingAs($user)->post('/bookings', [
            'event_slot_id' => $slot->id,
        ]);

        // assert redirct ke halaman tiket
        $response->assertSessionHasNoErrors();

        // assert database catat booking & tiket
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'event_slot_id' => $slot->id,
        ]);

        $this->assertDatabaseHas('event_slots', [
            'id' => $slot->id,
            'booked_count' => 1,
        ]);
    }

    public function test_booking_when_slot_is_full(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create();

        $event = Event::create([
            'user_id' => $admin->id,
            'title' => 'Laravel 13 Converence',
            'slug' => 'laravel-13-conference',
            'description' => 'Deep dive backend architecture',
            'location' => 'Jakarta',
        ]);

        $slot = EventSlot::create([
            'event_id' => $event->id,
            'start_time' => now()->addDays(1),
            'end_time' => now()->addDays(1)->addHours(2),
            'capacity' => 100,
            'booked_count' => 100,
        ]);

        $targetUrl = '/events/' . $event->id;

        $response = $this->actingAs($user)
            ->from($targetUrl)->withHeaders([
                    'Accept' => 'text/html',
                ])
            ->post('/bookings', [
                'event_slot_id' => $slot->id,
            ]);

        $response->assertRedirect($targetUrl);
        $response->assertSessionHasErrors('booking_error');
    }
}
