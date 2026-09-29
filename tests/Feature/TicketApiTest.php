<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Event;
use App\Models\EventSlot;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_can_verify_valid_ticket(): void
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
            'booked_count' => 1,
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'event_slot_id' => $slot->id,
        ]);

        $ticket = Ticket::create([
            'booking_id' => $booking->id,
            'ticket_code' => (string) Str::uuid(),
        ]);

        $response = $this->postJson("/api/tickets/verify", [
            'ticket_code' => $ticket->ticket_code,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'is_used' => true,
        ]);
    }

    public function test_reject_invalid_ticket(): void
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
            'booked_count' => 1,
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'event_slot_id' => $slot->id,
        ]);

        $ticket = Ticket::create([
            'booking_id' => $booking->id,
            'ticket_code' => (string) Str::uuid(),
            'is_used' => true,
            'checked_in_at' => now()->subHour(),
        ]);

        $response = $this->postJson("/api/tickets/verify", [
            'ticket_code' => $ticket->ticket_code,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
            ]);
    }
}
