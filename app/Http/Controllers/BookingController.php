<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Services\BookingService;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request, BookingService $bookingService)
    {
        try {
            $booking = $bookingService->createBooking($request->user(), $request->validated('event_slot_id'));
            return redirect()->route('tickets.show', $booking->ticket->id)->with('success', 'Booking created successfully. Your ticket code is: ' . $booking->ticket->ticket_code);
        } catch (\Exception $e) {
            return back()->withErrors(['booking_error' => $e->getMessage()]);
        }
    }
}
