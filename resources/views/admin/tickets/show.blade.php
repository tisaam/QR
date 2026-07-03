@extends('layouts.admin')

@section('title', 'Ticket #{{ $ticket->id }}')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.tickets.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back to Tickets
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Conversations -->
    <div class="lg:col-span-3 space-y-4">
        <!-- Original Message -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-start space-x-4">
                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-indigo-600 font-semibold">
                        {{ strtoupper(substr($ticket->user->name ?? 'U', 0, 1)) }}
                    </span>
                </div>
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-2">
                        <h3 class="font-semibold text-gray-800">{{ $ticket->user->name ?? 'Unknown' }}</h3>
                        <span class="text-xs text-gray-400">{{ $ticket->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 mb-3">{{ $ticket->subject }}</h2>
                    <div class="text-gray-600 whitespace-pre-wrap">{{ $ticket->message }}</div>
                    
                    @if($ticket->attachment)
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-paperclip text-gray-400 mr-2"></i>
                            <a href="{{ Storage::url($ticket->attachment) }}" target="_blank" 
                               class="text-indigo-600 hover:underline text-sm">{{ basename($ticket->attachment) }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Replies -->
        @foreach($ticket->replies as $reply)
            <div class="bg-white rounded-xl shadow-sm border p-6 {{ $reply->is_admin ? 'border-l-4 border-l-indigo-500' : '' }}">
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 {{ $reply->is_admin ? 'bg-purple-100' : 'bg-indigo-100' }} rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="{{ $reply->is_admin ? 'text-purple-600' : 'text-indigo-600' }} font-semibold text-sm">
                            {{ $reply->is_admin ? 'A' : strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}
                        </span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h3 class="font-semibold text-gray-800">
                                {{ $reply->is_admin ? 'Admin' : $reply->user->name }}
                            </h3>
                            @if($reply->is_admin)
                                <span class="px-2 py-0.5 text-xs bg-purple-100 text-purple-700 rounded-full">Staff</span>
                            @endif
                            <span class="text-xs text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="text-gray-600 whitespace-pre-wrap">{{ $reply->message }}</div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Reply Form -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Reply</h3>
            <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}">
                @csrf
                <textarea name="message" rows="4" required
                    class="w-full px-4 py-3 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                    placeholder="Type your reply..."></textarea>
                <div class="flex items-center justify-between mt-3">
                    <select name="status" class="px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                        <i class="fas fa-paper-plane mr-2"></i>Send Reply
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="space-y-4">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Ticket Details</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Status</p>
                    @php
                        $statusColors = [
                            'open' => 'bg-red-100 text-red-700',
                            'in_progress' => 'bg-blue-100 text-blue-700',
                            'resolved' => 'bg-green-100 text-green-700',
                            'closed' => 'bg-gray-100 text-gray-600',
                        ];
                    @endphp
                    <span class="inline-block mt-1 px-3 py-1 text-xs rounded-full {{ $statusColors[$ticket->status] ?? '' }} font-medium">
                        {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Priority</p>
                    @php
                        $priorityColors = [
                            'low' => 'bg-gray-100 text-gray-600',
                            'medium' => 'bg-blue-100 text-blue-700',
                            'high' => 'bg-orange-100 text-orange-700',
                            'urgent' => 'bg-red-100 text-red-700',
                        ];
                    @endphp
                    <span class="inline-block mt-1 px-3 py-1 text-xs rounded-full {{ $priorityColors[$ticket->priority] ?? '' }} font-medium">
                        {{ ucfirst($ticket->priority) }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Created</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $ticket->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Last Updated</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $ticket->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-semibold text-gray-800 mb-4">User Info</h3>
            <div class="text-center mb-3">
                <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <span class="text-indigo-600 text-xl font-bold">
                        {{ strtoupper(substr($ticket->user->name ?? 'U', 0, 1)) }}
                    </span>
                </div>
                <p class="font-semibold text-gray-800">{{ $ticket->user->name ?? 'Unknown' }}</p>
                <p class="text-xs text-gray-500">{{ $ticket->user->email ?? 'N/A' }}</p>
            </div>
            @if($ticket->user && $ticket->user->business)
                <div class="border-t pt-3">
                    <p class="text-xs text-gray-500">Business</p>
                    <p class="text-sm font-medium text-indigo-600">{{ $ticket->user->business->name }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection