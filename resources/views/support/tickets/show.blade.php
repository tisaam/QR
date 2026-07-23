@extends('layouts.app')

@section('title', 'Ticket: ' . $ticket->subject)

@push('styles')
<style>
    .ticket-page {
        max-width: 48rem;
        margin: 0 auto;
        padding: 0 1rem;
    }
    .ticket-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }
    .ticket-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .ticket-meta {
        color: #9ca3af;
        font-size: 0.8rem;
        margin-top: 0.5rem;
    }
    .ticket-action {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 0.9rem;
        border: 1px solid #fecaca;
        border-radius: 0.85rem;
        color: #dc2626;
        background: #fff1f2;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    .ticket-action:hover {
        background: #ffe4e6;
    }
    .message-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .message-row {
        display: flex;
    }
    .message-row.user {
        justify-content: flex-end;
    }
    .message-bubble {
        max-width: 75%;
        padding: 1rem 1.1rem;
        border-radius: 1.5rem;
        font-size: 0.95rem;
        line-height: 1.5;
        word-break: break-word;
    }
    .message-bubble.support {
        background: #eef2ff;
        color: #111827;
        border: 1px solid #e0e7ff;
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 1.5rem;
        border-bottom-right-radius: 1.5rem;
        border-bottom-left-radius: 1.5rem;
    }
    .message-bubble.user {
        background: #2563eb;
        color: #ffffff;
        border-top-right-radius: 0.75rem;
        border-top-left-radius: 1.5rem;
        border-bottom-right-radius: 1.5rem;
        border-bottom-left-radius: 1.5rem;
    }
    .message-meta {
        margin: 0 0 0.5rem;
        font-size: 0.75rem;
        font-weight: 700;
    }
    .message-meta.support {
        color: #4338ca;
    }
    .message-meta.user {
        color: #bfdbfe;
    }
    .reply-form {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        padding: 1rem;
        align-items: center;
    }
    .reply-input {
        flex: 1;
        min-width: 12rem;
        border: 1px solid #d1d5db;
        border-radius: 1rem;
        padding: 0.85rem 1rem;
        font-size: 0.95rem;
        color: #111827;
    }
    .reply-input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
    .btn-send {
        padding: 0.85rem 1.25rem;
        border: none;
        border-radius: 1rem;
        background: #2563eb;
        color: #ffffff;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    .btn-send:hover {
        background: #1d4ed8;
    }
    .ticket-closed {
        background: #f3f4f6;
        color: #6b7280;
        border-radius: 1rem;
        padding: 1rem;
        text-align: center;
        font-size: 0.95rem;
    }
</style>
@endpush

@section('content')
<div class="ticket-page">
    <div class="ticket-header">
        <div>
            <h1 class="ticket-title">{{ $ticket->subject }}</h1>
            <p class="ticket-meta">Opened {{ $ticket->created_at->format('M d, Y') }}</p>
        </div>
        @if($ticket->status !== 'closed')
            <form action="{{ route('tickets.close', $ticket) }}" method="POST" onsubmit="return confirm('Close this ticket?')">
                @csrf
                <button class="ticket-action" type="submit">Close Ticket</button>
            </form>
        @endif
    </div>

    <div class="message-list">
        @foreach($messages as $msg)
            <div class="message-row {{ $msg->is_admin ? 'support' : 'user' }}">
                <div class="message-bubble {{ $msg->is_admin ? 'support' : 'user' }}">
                    <p class="message-meta {{ $msg->is_admin ? 'support' : 'user' }}">
                        {{ $msg->is_admin ? 'Support Team' : 'You' }} • {{ $msg->created_at->format('h:i A') }}
                    </p>
                    <p>{{ $msg->message }}</p>
                </div>
            </div>
        @endforeach
    </div>

    @if($ticket->status !== 'closed')
        <form action="{{ route('tickets.message', $ticket) }}" method="POST" class="reply-form">
            @csrf
            <input type="text" name="message" required class="reply-input" placeholder="Type your reply...">
            <button type="submit" class="btn-send">Send</button>
        </form>
    @else
        <div class="ticket-closed">This ticket is closed.</div>
    @endif
</div>
@endsection