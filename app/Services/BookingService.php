<?php

namespace App\Services;

use App\Jobs\ProcessTicketMail;
use App\Models\Booking;
use App\Models\EventSlot;
use App\Models\Ticket;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;

class BookingService
{

    public function createBooking(User $user, int $eventSlotId): Booking
    {
        return DB::transaction(function () use ($user, $eventSlotId) {
            $slot = EventSlot::where("id", $eventSlotId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($slot->booked_count >= $slot->capacity) {
                throw new \Exception('No available slots for this event.');
            }

            $existingBooking = Booking::where('user_id', $user->id)
                ->where('event_slot_id', $eventSlotId)
                ->first();

            if ($existingBooking) {
                throw new \Exception('You have already booked this event slot.');
            }

            $slot->increment('booked_count');

            $booking = Booking::create([
                'user_id' => $user->id,
                'event_slot_id' => $slot->id,
                'status' => 'pending',
            ]);

            $ticketCode = $this->generateTicketCode();

            $ticket = Ticket::create([
                'booking_id' => $booking->id,
                'ticket_code' => $ticketCode,
                'is_used' => false,
            ]);

            ProcessTicketMail::dispatch($ticket);

            return $booking->load(['slot.event', 'user', 'ticket']);
        });
    }
    public function generateTicketCode(): string
    {
        return strtoupper(uniqid('TICKET_'));
    }
}

?>