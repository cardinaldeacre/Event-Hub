<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function show(Ticket $ticket)
    {
        $ticket->load('booking.slot.event', 'booking.user');

        return view('ticket.show', compact('ticket'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required|uuid|exists:tickets.ticket_code'
        ]);

        $ticket = Ticket::where('ticket_code', $request->ticket_code)->first();

        if ($ticket->is_used) {
            return response()->json(['status' => 'error', 'message' => 'Ticket has already been used.', 'data' => $ticket], 400);
        }

        $ticket->update([
            'is_used' => true,
            'checked_in_at' => Carbon::now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Ticket verified successfully.', 'data' => $ticket], 200);
    }
}
