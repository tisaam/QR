@extends('layouts.app')

@section('title', 'Notifications')

@push('styles')
<style>
    .notification-wrapper {
        max-width: 64rem;
        margin: 0 auto;
        padding: 0 1rem;
    }
    .notification-header {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    @media (min-width: 640px) {
        .notification-header {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
    }
    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .page-title i { color: var(--accent-glow); }
    .page-subtitle {
        margin: 0.25rem 0 0;
        color: var(--text-secondary);
        font-size: 0.9375rem;
    }
    .notification-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: center;
    }
    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        background: var(--accent-glow-soft);
        color: var(--accent-glow);
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid rgba(6, 182, 212, 0.2);
    }
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        background: var(--accent-glow);
        color: #ffffff;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-primary:hover {
        background: #0891b2;
        box-shadow: 0 0 15px var(--accent-glow-soft);
    }
    .notifications-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-glass);
        border-radius: 1.25rem;
        backdrop-filter: blur(16px);
        box-shadow: 0 25px 50px -12px var(--shadow-color);
        overflow: hidden;
    }
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        color: var(--text-secondary);
    }
    .empty-icon {
        width: 5rem;
        height: 5rem;
        border-radius: 9999px;
        background: var(--accent-glow-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: var(--text-secondary);
        font-size: 1.75rem;
    }
    .notification-list {
        display: block;
    }
    .notification-item {
        padding: 1.125rem 1.5rem;
        border-bottom: 1px solid var(--border-glass);
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .notification-item:last-child {
        border-bottom: none;
    }
    .notification-item:hover {
        background: rgba(255, 255, 255, 0.03);
    }
    /* Unread State - Glass Glow */
    .notification-item.unread {
        border-left: 4px solid var(--accent-glow);
        background: rgba(6, 182, 212, 0.03);
    }
    .notification-item.unread:hover {
        background: rgba(6, 182, 212, 0.06);
    }
    .notification-rows {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }
    .notification-icon {
        min-width: 2.5rem;
        min-height: 2.5rem;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease;
    }
    .notification-item:hover .notification-icon {
        transform: scale(1.1);
    }
    .notification-content {
        flex: 1;
        min-width: 0;
    }
    .notification-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .notification-title {
        margin: 0;
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-secondary);
    }
    .notification-title.unread {
        color: var(--text-primary);
    }
    .notification-time {
        font-size: 0.75rem;
        color: var(--text-secondary);
        white-space: nowrap;
        opacity: 0.8;
    }
    .notification-text {
        margin: 0.25rem 0 0;
        color: var(--text-secondary);
        font-size: 0.9375rem;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .notification-link {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        margin-top: 0.5rem;
        color: var(--accent-glow);
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: gap 0.2s ease;
    }
    .notification-link:hover {
        color: #fff;
        gap: 0.5rem;
    }
    .notification-dot {
        width: 0.5rem;
        height: 0.5rem;
        border-radius: 9999px;
        background: var(--accent-glow);
        flex-shrink: 0;
        box-shadow: 0 0 8px var(--accent-glow);
    }
    .notification-pagination {
        padding: 1rem 1.5rem;
        background: rgba(0,0,0,0.1);
        border-top: 1px solid var(--border-glass);
    }
    /* Fix pagination links for dark mode */
    .notification-pagination a, .notification-pagination span {
        color: var(--text-secondary) !important;
        transition: color 0.2s;
    }
    .notification-pagination a:hover {
        color: var(--accent-glow) !important;
    }
</style>
@endpush

@section('content')
<div class="notification-wrapper">
    <div class="notification-header">
        <div>
            <h1 class="page-title"><i class="fas fa-bell"></i> Notifications</h1>
            <p class="page-subtitle">Stay updated with your latest activity</p>
        </div>
        <div class="notification-actions">
            <span id="unread-count-badge" class="badge">{{ $notifications->where('is_read', false)->count() }} unread</span>
            <button id="mark-all-read-btn" class="btn-primary" onclick="markAllRead()">
                <i class="fas fa-check-double"></i>Mark all as read
            </button>
        </div>
    </div>

    <div class="notifications-card">
        @if($notifications->isEmpty())
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-bell-slash"></i></div>
                <h3 style="margin:0 0 0.5rem; font-size:1.125rem; color:var(--text-primary);">No notifications yet</h3>
                <p style="margin:0; font-size:0.9375rem;">When you receive notifications, they'll appear here.</p>
            </div>
        @else
            <div class="notification-list">
                @foreach($notifications as $notification)
                    <div class="notification-item {{ !$notification->is_read ? 'unread' : '' }}" data-id="{{ $notification->id }}" data-is-read="{{ $notification->is_read ? 'true' : 'false' }}" onclick="handleNotificationClick(this, {{ $notification->id }})">
                        <div class="notification-rows">
                            @php
                                $icon = 'fas fa-info-circle';
                                $iconColor = 'var(--accent-glow)';
                                $iconBackground = 'var(--accent-glow-soft)';
                            @endphp
                            @switch($notification->type)
                                @case('review')
                                    @php
                                        $icon = 'fas fa-star';
                                        $iconColor = '#eab308';
                                        $iconBackground = 'rgba(234, 179, 8, 0.15)';
                                    @endphp
                                    @break
                                @case('qr_scan')
                                    @php
                                        $icon = 'fas fa-qrcode';
                                        $iconColor = '#22c55e';
                                        $iconBackground = 'rgba(34, 197, 94, 0.15)';
                                    @endphp
                                    @break
                                @case('campaign')
                                    @php
                                        $icon = 'fas fa-bullhorn';
                                        $iconColor = '#a78bfa';
                                        $iconBackground = 'rgba(167, 139, 250, 0.15)';
                                    @endphp
                                    @break
                                @case('whatsapp')
                                    @php
                                        $icon = 'fab fa-whatsapp';
                                        $iconColor = '#22c55e';
                                        $iconBackground = 'rgba(34, 197, 94, 0.15)';
                                    @endphp
                                    @break
                                @case('sms')
                                    @php
                                        $icon = 'fas fa-sms';
                                        $iconColor = 'var(--accent-glow)';
                                        $iconBackground = 'var(--accent-glow-soft)';
                                    @endphp
                                    @break
                                @case('alert')
                                    @php
                                        $icon = 'fas fa-exclamation-triangle';
                                        $iconColor = '#ef4444';
                                        $iconBackground = 'rgba(239, 68, 68, 0.15)';
                                    @endphp
                                    @break
                                @case('system')
                                    @php
                                        $icon = 'fas fa-cog';
                                        $iconColor = 'var(--text-secondary)';
                                        $iconBackground = 'rgba(255,255,255,0.05)';
                                    @endphp
                                    @break
                            @endswitch
                            <div class="notification-icon" style="background: {{ $iconBackground }}; color: {{ $iconColor }};">
                                <i class="{{ $icon }}"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-meta">
                                    <h4 class="notification-title {{ !$notification->is_read ? 'unread' : '' }}">{{ $notification->title }}</h4>
                                    <div style="display:flex;align-items:center;gap:0.5rem;">
                                        @if(!$notification->is_read)
                                            <span class="notification-dot"></span>
                                        @endif
<span class="notification-time">{{ $notification->read_at ? $notification->read_at->diffForHumans() : ($notification->created_at ? $notification->created_at->diffForHumans() : 'Just now') }}</span>                                    </div>
                                </div>
                                <p class="notification-text">{{ $notification->message }}</p>
                                @if($notification->data && is_array($notification->data) && isset($notification->data['action_url']))
                                    <a href="{{ $notification->data['action_url'] }}" class="notification-link" onclick="event.stopPropagation()">
                                        {{ $notification->data['action_text'] ?? 'View details' }}
                                        <i class="fas fa-arrow-right" style="font-size:10px;"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="notification-pagination">
                {{ $notifications->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function handleNotificationClick(element, notificationId) {
        if (element.dataset.isRead === 'true') return;

        fetch(`/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                element.dataset.isRead = 'true';
                element.classList.remove('unread');
                const dot = element.querySelector('.notification-dot');
                if (dot) dot.remove();
                updateUnreadCount(-1);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function markAllRead() {
        const btn = document.getElementById('mark-all-read-btn');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        btn.disabled = true;

        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.querySelectorAll('.notification-item.unread').forEach(item => {
                    item.classList.remove('unread');
                    item.dataset.isRead = 'true';
                    const dot = item.querySelector('.notification-dot');
                    if (dot) dot.remove();
                });
                document.getElementById('unread-count-badge').textContent = '0 unread';
                btn.innerHTML = '<i class="fas fa-check"></i> All caught up!';
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                }, 3000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.innerHTML = originalHTML;
            btn.disabled = false;
        });
    }

    function updateUnreadCount(change) {
        const badge = document.getElementById('unread-count-badge');
        const currentCount = parseInt(badge.textContent) || 0;
        const newCount = Math.max(0, currentCount + change);
        badge.textContent = `${newCount} unread`;
    }
</script>
@endpush