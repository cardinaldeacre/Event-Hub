<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('E-Ticket Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden text-center bg-white border shadow-sm sm:rounded-lg">

                @if (session('success'))
                    <div class="px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <h3 class="mb-1 text-2xl font-bold text-gray-800">
                    {{ $ticket->booking->slot->event->title }}
                </h3>
                <p class="mb-4 text-sm text-gray-500">
                    {{ $ticket->booking->slot->event->location }}
                </p>

                <hr class="my-4 border-dashed">

                <div class="flex justify-center my-6">
                    {!! QrCode::size(200)->color(30, 41, 59)->generate($ticket->ticket_code) !!}
                </div>

                <p class="mb-6 font-mono text-xs tracking-wider text-gray-400 uppercase">
                    Code: {{ $ticket->ticket_code }}
                </p>

                <div class="p-4 space-y-2 text-sm text-left rounded-lg bg-gray-50">
                    <div>
                        <span class="text-gray-500">Pemegang Tiket:</span>
                        <p class="font-semibold text-gray-800">{{ $ticket->booking->user->name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Waktu Event:</span>
                        <p class="font-semibold text-gray-800">
                            {{ $ticket->booking->slot->start_time->format('d M Y, H:i') }} -
                            {{ $ticket->booking->slot->end_time->format('H:i') }} WIB
                        </p>
                    </div>
                    <div>
                        <span class="text-gray-500">Status Check-in:</span>
                        <p class="font-semibold">
                            @if ($ticket->is_used)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Sudah Dipakai ({{ $ticket->checked_in_at->format('H:i') }})
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Valid / Belum Dipakai
                                </span>
                            @endif
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
