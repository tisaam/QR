@extends('layouts.app')

@section('title', 'Ticket: ' . $ticket->subject)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-800">{{ $ticket->subject }}</h1>
            <p class="text-xs text-gray-400 mt-1">Opened {{ $ticket->created_at->format('M d, Y') }}</p>
        </div>
        @if($ticket->status !== 'closed')
            <form action="{{ route('tickets.close', $ticket) }}" method="POST" onsubmit="return confirm('Close this ticket?')">
                @csrf
                <button class="text-sm text-red-500 border border-red-200 px-3 py-1 rounded-lg hover:bg-red-50">Close Ticket</button>
            </form>
        @endif
    </div>

    <!-- Messages -->
    <div class="space-y-4 mb-6">
        @foreach($messages as $msg)
            <div class="flex {{ $msg->is_admin ? 'justify-start' : 'justify-end' }}">
                <div class="max-w-[75%] px-4 py-3 rounded-2xl text-sm 
                    {{ $msg->is_admin ? 'bg-indigo-50 text-gray-800 border border-indigo-100' : 'bg-blue-600 text-white' }}">
                    <p class="font-semibold text-xs mb-1 {{ $msg->is_admin ? 'text-indigo-600' : 'text-blue-200' }}">
                        {{ $msg->is_admin ? 'Support Team' : 'You' }} • {{ $msg->created_at->format('h:i A') }}
                    </p>
                    <p>{{ $msg->message }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Reply Form -->
    @if($ticket->status !== 'closed')
        <form action="{{ route('tickets.message', $ticket) }}" method="POST" class="bg-white rounded-xl shadow-sm border p-4 flex gap-3">
            @csrf
            <input type="text" name="message" required class="flex-1 border rounded-lg px-4 py-2 focus:ring-blue-500" placeholder="Type your reply...">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-semibold">Send</button>
        </form>
    @else
        <div class="bg-gray-100 text-center py-4 rounded-xl text-gray-500 text-sm">This ticket is closed.</div>
    @endif
</div>
@endsection