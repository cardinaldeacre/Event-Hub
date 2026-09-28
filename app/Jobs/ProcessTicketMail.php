<?php

namespace App\Jobs;

use App\Mail\SendTicketMail;
use App\Models\Ticket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Mail;

class ProcessTicketMail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Ticket $ticket)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->ticket->load(['booking.user', 'booking.slot.event']);

        Mail::to($this->ticket->booking->user->email)->send(new SendTicketMail($this->ticket));
    }
}
