@extends('layouts.admin')

@section('title', 'Ticket #' . $ticket->id)

@section('content')
<style>
    .ticket-page { font-family: Arial, sans-serif; color: #1f2937; }
    .ticket-back { margin-bottom: 1.25rem; }
    .ticket-back a { color: #4f46e5; text-decoration: none; font-size: 0.95rem; font-weight: 600; }
    .ticket-back a:hover { color: #3730a3; }
    .ticket-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem; }
    .ticket-panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.9rem; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
    .ticket-main { padding: 1.25rem; }
    .ticket-sidebar { padding: 1.25rem; }
    .ticket-thread { margin-bottom: 1rem; }
    .ticket-thread:last-child { margin-bottom: 0; }
    .thread-header { display: flex; align-items: flex-start; gap: 0.75rem; margin-bottom: 0.75rem; }
    .thread-avatar { width: 2.5rem; height: 2.5rem; border-radius: 999px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #4f46e5; background: #e0e7ff; flex-shrink: 0; }
    .thread-avatar.admin { background: #ede9fe; color: #7c3aed; }
    .thread-meta { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.25rem; }
    .thread-name { font-weight: 700; color: #111827; margin: 0; }
    .thread-date { font-size: 0.8rem; color: #9ca3af; }
    .staff-badge { display: inline-block; padding: 0.2rem 0.5rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; background: #ede9fe; color: #7c3aed; }
    .thread-body { color: #4b5563; line-height: 1.6; white-space: pre-wrap; }
    .attachment-box { margin-top: 1rem; padding: 0.75rem; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.7rem; }
    .attachment-link { color: #4f46e5; text-decoration: none; font-weight: 600; font-size: 0.95rem; }
    .reply-title { font-size: 1rem; font-weight: 700; color: #111827; margin: 0 0 0.85rem; }
    .reply-form textarea { width: 100%; box-sizing: border-box; padding: 0.9rem 1rem; border: 1px solid #d1d5db; border-radius: 0.7rem; font-size: 0.95rem; resize: vertical; }
    .reply-actions { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; margin-top: 0.85rem; }
    .reply-actions select { padding: 0.7rem 0.9rem; border: 1px solid #d1d5db; border-radius: 0.6rem; background: #fff; }
    .reply-actions button { background: #4f46e5; color: #fff; border: none; padding: 0.7rem 1rem; border-radius: 0.6rem; font-size: 0.95rem; font-weight: 600; cursor: pointer; }
    .reply-actions button:hover { background: #4338ca; }
    .section-title { font-size: 1rem; font-weight: 700; color: #111827; margin: 0 0 0.9rem; }
    .detail-list > div { padding-bottom: 0.75rem; margin-bottom: 0.75rem; border-bottom: 1px solid #f3f4f6; }
    .detail-list > div:last-child { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }
    .detail-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em; color: #6b7280; margin: 0 0 0.25rem; }
    .detail-value { color: #111827; font-size: 0.95rem; }
    .status-badge, .priority-badge { display: inline-block; margin-top: 0.3rem; padding: 0.3rem 0.65rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
    .status-open { background: #fee2e2; color: #b91c1c; }
    .status-in-progress { background: #dbeafe; color: #1d4ed8; }
    .status-resolved { background: #dcfce7; color: #166534; }
    .status-closed { background: #f3f4f6; color: #4b5563; }
    .priority-low { background: #f3f4f6; color: #4b5563; }
    .priority-medium { background: #dbeafe; color: #1d4ed8; }
    .priority-high { background: #ffedd5; color: #c2410c; }
    .priority-urgent { background: #fee2e2; color: #b91c1c; }
    .user-card { text-align: center; }
    .user-avatar { width: 3.3rem; height: 3.3rem; border-radius: 999px; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.7rem; background: #e0e7ff; color: #4f46e5; font-size: 1.1rem; font-weight: 700; }
    .user-name { font-size: 1rem; font-weight: 700; color: #111827; margin: 0 0 0.25rem; }
    .user-email { font-size: 0.85rem; color: #6b7280; margin: 0; }
    .business-box { margin-top: 0.9rem; padding-top: 0.75rem; border-top: 1px solid #e5e7eb; }
    .business-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em; color: #6b7280; margin: 0 0 0.25rem; }
    .business-name { color: #4f46e5; font-weight: 600; margin: 0; }
    .ticket-title { font-size: 1.2rem; font-weight: 700; color: #111827; margin: 0 0 0.75rem; }
    .ticket-divider { border-top: 1px solid #e5e7eb; margin: 1rem 0; }
    @media (max-width: 992px) { .ticket-layout { grid-template-columns: 1fr; } }
</style>

<div class="ticket-page">
    <div class="ticket-back">
        <a href="{{ route('admin.tickets.index') }}">
            <i class="fas fa-arrow-left"></i> Back to Tickets
        </a>
    </div>

    <div class="ticket-layout">
        <div>
            <div class="ticket-panel ticket-main">
                <div class="thread-header">
                    <div class="thread-avatar">{{ strtoupper(substr($ticket->user->name ?? 'U', 0, 1)) }}</div>
                    <div>
                        <div class="thread-meta">
                            <h3 class="thread-name">{{ $ticket->user->name ?? 'Unknown' }}</h3>
                            <span class="thread-date">{{ $ticket->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <h2 class="ticket-title">{{ $ticket->subject }}</h2>
                        <div class="thread-body">{{ $ticket->message }}</div>

                        @if($ticket->attachment)
                            <div class="attachment-box">
                                <i class="fas fa-paperclip" style="color:#9ca3af; margin-right:0.4rem;"></i>
                                <a href="{{ Storage::url($ticket->attachment) }}" target="_blank" class="attachment-link">
                                    {{ basename($ticket->attachment) }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="ticket-divider"></div>

            @foreach($ticket->replies as $reply)
                <div class="ticket-panel ticket-main ticket-thread {{ $reply->is_admin ? 'reply-admin' : '' }}">
                    <div class="thread-header">
                        <div class="thread-avatar {{ $reply->is_admin ? 'admin' : '' }}">
                            {{ $reply->is_admin ? 'A' : strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="thread-meta">
                                <h3 class="thread-name">{{ $reply->is_admin ? 'Admin' : ($reply->user->name ?? 'Unknown') }}</h3>
                                @if($reply->is_admin)
                                    <span class="staff-badge">Staff</span>
                                @endif
                                <span class="thread-date">{{ $reply->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="thread-body">{{ $reply->message }}</div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="ticket-panel ticket-main">
                <h3 class="reply-title">Reply</h3>
                <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}" class="reply-form">
                    @csrf
                    <textarea name="message" rows="4" required placeholder="Type your reply..."></textarea>
                    <div class="reply-actions">
                        <select name="status">
                            <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        <button type="submit">
                            <i class="fas fa-paper-plane" style="margin-right:0.35rem;"></i>Send Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div>
            <div class="ticket-panel ticket-sidebar">
                <h3 class="section-title">Ticket Details</h3>
                <div class="detail-list">
                    <div>
                        <p class="detail-label">Status</p>
                        <span class="status-badge {{ 'status-' . str_replace('_', '-', $ticket->status) }}">
                            {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                        </span>
                    </div>
                    <div>
                        <p class="detail-label">Priority</p>
                        <span class="priority-badge {{ 'priority-' . $ticket->priority }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </div>
                    <div>
                        <p class="detail-label">Created</p>
                        <p class="detail-value">{{ $ticket->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <p class="detail-label">Last Updated</p>
                        <p class="detail-value">{{ $ticket->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <div class="ticket-panel ticket-sidebar" style="margin-top:1rem;">
                <h3 class="section-title">User Info</h3>
                <div class="user-card">
                    <div class="user-avatar">
                        {{ strtoupper(substr($ticket->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <p class="user-name">{{ $ticket->user->name ?? 'Unknown' }}</p>
                    <p class="user-email">{{ $ticket->user->email ?? 'N/A' }}</p>
                </div>
                @if($ticket->user && $ticket->user->business)
                    <div class="business-box">
                        <p class="business-label">Business</p>
                        <p class="business-name">{{ $ticket->user->business->name }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection