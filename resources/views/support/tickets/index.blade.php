@extends('layouts.app')

@section('title', 'Support Tickets')

@push('styles')
<style>
    .support-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .support-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .support-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border-radius: 0.85rem;
        background: #2563eb;
        color: #ffffff;
        font-weight: 700;
        text-decoration: none;
        transition: background 0.2s ease;
    }
    .support-button:hover {
        background: #1d4ed8;
    }
    .support-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 1.25rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }
    .ticket-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
        color: #6b7280;
    }
    .ticket-table th,
    .ticket-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f3f4f6;
        text-align: left;
        vertical-align: middle;
    }
    .ticket-table thead {
        background: #f9fafb;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 0.75rem;
    }
    .ticket-row {
        background: #ffffff;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    .ticket-row:hover {
        background: #f8fafc;
    }
    .ticket-subject {
        color: #111827;
        font-weight: 600;
    }
    .ticket-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.25rem 0.65rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: capitalize;
        white-space: nowrap;
    }
    .ticket-pill-high {
        background: #fee2e2;
        color: #b91c1c;
    }
    .ticket-pill-medium {
        background: #fef3c7;
        color: #b45309;
    }
    .ticket-pill-low {
        background: #f3f4f6;
        color: #374151;
    }
    .ticket-pill-open {
        background: #dbeafe;
        color: #1d4ed8;
    }
    .ticket-pill-resolved {
        background: #d1fae5;
        color: #166534;
    }
    .ticket-pill-closed {
        background: #ffedd5;
        color: #b45309;
    }
    .ticket-empty {
        padding: 2.5rem 1.25rem;
        text-align: center;
        color: #9ca3af;
    }
</style>
@endpush

@section('content')
<div class="support-header">
    <h1 class="support-title">Support Tickets</h1>
    <a href="{{ route('tickets.create') }}" class="support-button">
        <i class="fas fa-plus"></i>Open Ticket
    </a>
</div>

<div class="support-card">
    <table class="ticket-table">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Updated</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
                <tr class="ticket-row" onclick="window.location='{{ route('tickets.show', $ticket) }}'">
                    <td class="ticket-subject">{{ $ticket->subject }}</td>
                    <td>
                        <span class="ticket-pill {{ $ticket->priority === 'high' ? 'ticket-pill-high' : ($ticket->priority === 'medium' ? 'ticket-pill-medium' : 'ticket-pill-low') }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </td>
                    <td>
                        <span class="ticket-pill {{ $ticket->status === 'open' ? 'ticket-pill-open' : ($ticket->status === 'resolved' ? 'ticket-pill-resolved' : 'ticket-pill-closed') }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </td>
                    <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="ticket-empty">No support tickets.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection 