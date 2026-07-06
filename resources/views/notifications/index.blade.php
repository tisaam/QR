@extends('layouts.app')

@section('title', 'Notifications')

@push('styles')
<style>
    .notification-item {
        transition: all 0.2s ease;
    }
    .notification-item:hover {
        background-color: #f9fafb;
    }
    .notification-item.unread {
        border-left: 4px solid #3b82f6;
        background-color: #eff6ff;
    }
    .notification-item.unread:hover {
        background-color: #dbeafe;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-bell text-blue-500 mr-2"></i>Notifications
            </h1>
            <p class="text-gray-500 text-sm mt-1">Stay updated with your latest activity</p>
        </div>
        <div class="mt-3 sm:mt-0 flex items-center space-x-3">
            <span id="unread-count-badge" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                {{ $notifications->where('is_read', false)->count() }} unread
            </span>
            <button 
                id="mark-all-read-btn" 
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                onclick="markAllRead()"
            >
                <i class="fas fa-check-double mr-2"></i>Mark all as read
            </button>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
        
        @if($notifications->isEmpty())
            <!-- Empty State -->
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bell-slash text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">No notifications yet</h3>
                <p class="text-gray-400 text-sm">When you receive notifications, they'll appear here.</p>
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($notifications as $notification)
                    <div 
                        class="notification-item {{ !$notification->is_read ? 'unread' : '' }} px-6 py-4 cursor-pointer"
                        data-id="{{ $notification->id }}"
                        data-is-read="{{ $notification->is_read ? 'true' : 'false' }}"
                        onclick="handleNotificationClick(this, {{ $notification->id }})"
                    >
                        <div class="flex items-start">
                            <!-- Icon -->
                            <div class="flex-shrink-0 mt-0.5">
                                @switch($notification->type)
                                    @case('review')
                                        <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                            <i class="fas fa-star text-yellow-500"></i>
                                        </div>
                                        @break
                                    @case('qr_scan')
                                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <i class="fas fa-qrcode text-green-500"></i>
                                        </div>
                                        @break
                                    @case('campaign')
                                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                            <i class="fas fa-bullhorn text-purple-500"></i>
                                        </div>
                                        @break
                                    @case('whatsapp')
                                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                            <i class="fab fa-whatsapp text-emerald-500"></i>
                                        </div>
                                        @break
                                    @case('sms')
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-sms text-blue-500"></i>
                                        </div>
                                        @break
                                    @case('alert')
                                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                            <i class="fas fa-exclamation-triangle text-red-500"></i>
                                        </div>
                                        @break
                                    @case('system')
                                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="fas fa-cog text-gray-500"></i>
                                        </div>
                                        @break
                                    @default
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-info-circle text-blue-500"></i>
                                        </div>
                                @endswitch
                            </div>

                            <!-- Content -->
                            <div class="flex-1 ml-4 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-semibold {{ !$notification->is_read ? 'text-gray-900' : 'text-gray-600' }}">
                                        {{ $notification->title }}
                                    </h4>
                                    <div class="flex items-center space-x-2 ml-4 flex-shrink-0">
                                        @if(!$notification->is_read)
                                            <span class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></span>
                                        @endif
                                        <span class="text-xs text-gray-400">
                                            {{ $notification->read_at ? $notification->read_at->diffForHumans() : $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 mt-0.5 truncate">
                                    {{ $notification->message }}
                                </p>

                                @if($notification->data && is_array($notification->data) && isset($notification->data['action_url']))
                                    <a 
                                        href="{{ $notification->data['action_url'] }}" 
                                        class="inline-flex items-center mt-2 text-xs font-medium text-blue-600 hover:text-blue-700"
                                        onclick="event.stopPropagation()"
                                    >
                                        {{ $notification->data['action_text'] ?? 'View details' }}
                                        <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $notifications->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Mark single notification as read
    function handleNotificationClick(element, notificationId) {
        if (element.dataset.isRead === 'true') return;

        fetch(`/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || 
                    document.querySelector('input[name="_token"]')?.value,
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
                const dot = element.querySelector('.bg-blue-500.w-2');
                if (dot) dot.remove();
                updateUnreadCount(-1);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Mark all as read
    function markAllRead() {
        const btn = document.getElementById('mark-all-read-btn');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
        btn.disabled = true;

        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || 
                    document.querySelector('input[name="_token"]')?.value,
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
                    const dot = item.querySelector('.bg-blue-500.w-2');
                    if (dot) dot.remove();
                });
                document.getElementById('unread-count-badge').textContent = '0 unread';
                btn.innerHTML = '<i class="fas fa-check mr-2"></i>All caught up!';
                btn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                btn.classList.add('bg-green-600', 'hover:bg-green-700');

                // Update navbar bell dot
                const bellDot = document.querySelector('header .bg-red-500');
                if (bellDot) bellDot.remove();

                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('bg-green-600', 'hover:bg-green-700');
                    btn.classList.add('bg-blue-600', 'hover:bg-blue-700');
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

    // Update unread count badge
    function updateUnreadCount(change) {
        const badge = document.getElementById('unread-count-badge');
        const currentText = badge.textContent;
        const currentCount = parseInt(currentText) || 0;
        const newCount = Math.max(0, currentCount + change);
        badge.textContent = `${newCount} unread`;

        if (newCount === 0) {
            const bellDot = document.querySelector('header .bg-red-500');
            if (bellDot) bellDot.remove();
        }
    }
</script>
@endpush