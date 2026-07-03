@extends('layouts.admin')

@section('title', 'Support Tickets')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Support Tickets</h1>
    <div class="flex items-center space-x-3">
        <select id="status-filter" class="px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">All Status</option>
            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
        <select id="priority-filter" class="px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">All Priority</option>
            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
        </select>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-red-600">{{ $tickets->where('status', 'open')->count() }}</p>
        <p class="text-sm text-red-500">Open</p>
    </div>
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-blue-600">{{ $tickets->where('status', 'in_progress')->count() }}</p>
        <p class="text-sm text-blue-500">In Progress</p>
    </div>
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-green-600">{{ $tickets->where('status', 'resolved')->count() }}</p>
        <p class="text-sm text-green-500">Resolved</p>
    </div>
    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-gray-600">{{ $tickets->where('status', 'closed')->count() }}</p>
        <p class="text-sm text-gray-500">Closed</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-4">Ticket ID</th>
                    <th class="px-6 py-4">Subject</th>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Priority</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Last Update</th>
                    <th class="px-6 py-4">Action</th>
                </tr>
            </thead>
            <tbody id="tickets-table">
                @forelse($tickets as $ticket)
                    <tr class="border-b hover:bg-gray-50 {{ $ticket->status === 'open' ? 'bg-red-50/30' : '' }}">
                        <td class="px-6 py-4 font-mono text-xs">#{{ $ticket->id }}</td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900 truncate max-w-xs">{{ $ticket->subject }}</p>
                            <p class="text-xs text-gray-400 truncate max-w-xs">{{ Str::limit($ticket->message, 50) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-7 h-7 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <span class="text-indigo-600 text-xs font-semibold">
                                        {{ strtoupper(substr($ticket->user->name ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                                <span>{{ $ticket->user->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $priorityColors = [
                                    'low' => 'bg-gray-100 text-gray-600',
                                    'medium' => 'bg-blue-100 text-blue-700',
                                    'high' => 'bg-orange-100 text-orange-700',
                                    'urgent' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 text-xs rounded-full {{ $priorityColors[$ticket->priority] ?? 'bg-gray-100 text-gray-600' }} font-medium">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'open' => 'bg-red-100 text-red-700',
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    'resolved' => 'bg-green-100 text-green-700',
                                    'closed' => 'bg-gray-100 text-gray-600',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 text-xs rounded-full {{ $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-600' }} font-medium">
                                {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs">{{ $ticket->updated_at->diffForHumans() }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.tickets.show', $ticket) }}" 
                               class="text-indigo-600 hover:text-indigo-800 text-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-headset text-4xl mb-3 block"></i>
                            No tickets found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 bg-gray-50 border-t flex items-center justify-between">
        <p class="text-sm text-gray-500">Showing {{ $tickets->count() }} of {{ $tickets->total() }} tickets</p>
        {{ $tickets->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('status-filter').addEventListener('change', function(e) {
    const priority = document.getElementById('priority-filter').value;
    let url = '{{ route('admin.tickets.index') }}?status=' + e.target.value;
    if (priority) url += '&priority=' + priority;
    window.location.href = url;
});

document.getElementById('priority-filter').addEventListener('change', function(e) {
    const status = document.getElementById('status-filter').value;
    let url = '{{ route('admin.tickets.index') }}?priority=' + e.target.value;
    if (status) url += '&status=' + status;
    window.location.href = url;
});
</script>
@endpush