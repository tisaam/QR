@extends('layouts.app')

@section('title', 'Support Tickets')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Support Tickets</h1>
    <a href="{{ route('tickets.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        <i class="fas fa-plus mr-2"></i>Open Ticket
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th class="px-6 py-3">Subject</th>
                <th class="px-6 py-3">Priority</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Updated</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
                <tr class="bg-white border-b hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('tickets.show', $ticket) }}'">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $ticket->subject }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $ticket->priority === 'high' ? 'bg-red-100 text-red-700' : 
                               ($ticket->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-700' : 
                               ($ticket->status === 'resolved' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700') }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $ticket->updated_at->diffForHumans() }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-6 py-10 text-center text-gray-400">No support tickets.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection