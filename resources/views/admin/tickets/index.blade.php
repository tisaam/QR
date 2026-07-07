@extends('layouts.admin')

@section('title', 'Support Tickets')

@section('content')
<style>
    .tickets-page { font-family: Arial, sans-serif; color: #1f2937; }
    .tickets-topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; gap: 1rem; }
    .tickets-title { font-size: 1.5rem; font-weight: 700; color: #1f2937; margin: 0; }
    .tickets-filters { display: flex; gap: 0.75rem; flex-wrap: wrap; }
    .tickets-filters select { padding: 0.65rem 0.9rem; border: 1px solid #d1d5db; border-radius: 0.6rem; font-size: 0.95rem; background: #fff; }
    .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem; margin-bottom: 1.25rem; }
    .stat-card { padding: 1rem; border-radius: 0.8rem; text-align: center; border: 1px solid #e5e7eb; }
    .stat-card.open { background: #fef2f2; border-color: #fecaca; }
    .stat-card.progress { background: #eff6ff; border-color: #bfdbfe; }
    .stat-card.resolved { background: #f0fdf4; border-color: #bbf7d0; }
    .stat-card.closed { background: #f9fafb; border-color: #e5e7eb; }
    .stat-value { font-size: 1.5rem; font-weight: 700; margin: 0; }
    .stat-label { font-size: 0.9rem; margin: 0.2rem 0 0; }
    .table-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.8rem; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
    .table-card table { width: 100%; border-collapse: collapse; font-size: 0.95rem; color: #4b5563; }
    .table-card th, .table-card td { padding: 0.8rem 0.9rem; border-bottom: 1px solid #e5e7eb; text-align: left; }
    .table-card thead { background: #f9fafb; text-transform: uppercase; font-size: 0.72rem; color: #374151; }
    .table-card tbody tr:hover { background: #f9fafb; }
    .ticket-subject { font-weight: 600; color: #111827; margin: 0; }
    .ticket-preview { font-size: 0.8rem; color: #9ca3af; margin: 0.2rem 0 0; }
    .user-cell { display: flex; align-items: center; gap: 0.6rem; }
    .avatar { width: 1.9rem; height: 1.9rem; border-radius: 999px; background: #eef2ff; display: flex; align-items: center; justify-content: center; color: #4f46e5; font-size: 0.8rem; font-weight: 700; }
    .badge { display: inline-block; padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
    .badge-low { background: #f3f4f6; color: #4b5563; }
    .badge-medium { background: #dbeafe; color: #1d4ed8; }
    .badge-high { background: #ffedd5; color: #c2410c; }
    .badge-urgent { background: #fee2e2; color: #b91c1c; }
    .badge-open { background: #fee2e2; color: #b91c1c; }
    .badge-progress { background: #dbeafe; color: #1d4ed8; }
    .badge-resolved { background: #dcfce7; color: #166534; }
    .badge-closed { background: #f3f4f6; color: #4b5563; }
    .view-link { color: #4f46e5; text-decoration: none; font-weight: 600; }
    .view-link:hover { color: #3730a3; }
    .empty-state { text-align: center; color: #9ca3af; padding: 2rem 0; }
    .pagination-wrap { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.1rem; background: #f9fafb; border-top: 1px solid #e5e7eb; }
    .pagination-wrap p { margin: 0; color: #6b7280; font-size: 0.95rem; }
    @media (max-width: 768px) { .tickets-topbar { flex-direction: column; align-items: flex-start; } .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 576px) { .stats-grid { grid-template-columns: 1fr; } }
</style>

<div class="tickets-page">
    <div class="tickets-topbar">
        <h1 class="tickets-title">Support Tickets</h1>
        <div class="tickets-filters">
            <select id="status-filter">
                <option value="">All Status</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
            <select id="priority-filter">
                <option value="">All Priority</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
            </select>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card open">
            <p class="stat-value" style="color:#dc2626;">{{ $tickets->where('status', 'open')->count() }}</p>
            <p class="stat-label" style="color:#b91c1c;">Open</p>
        </div>
        <div class="stat-card progress">
            <p class="stat-value" style="color:#2563eb;">{{ $tickets->where('status', 'in_progress')->count() }}</p>
            <p class="stat-label" style="color:#1d4ed8;">In Progress</p>
        </div>
        <div class="stat-card resolved">
            <p class="stat-value" style="color:#16a34a;">{{ $tickets->where('status', 'resolved')->count() }}</p>
            <p class="stat-label" style="color:#15803d;">Resolved</p>
        </div>
        <div class="stat-card closed">
            <p class="stat-value" style="color:#6b7280;">{{ $tickets->where('status', 'closed')->count() }}</p>
            <p class="stat-label" style="color:#4b5563;">Closed</p>
        </div>
    </div>

    <div class="table-card">
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Subject</th>
                        <th>User</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Last Update</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tickets-table">
                    @forelse($tickets as $ticket)
                        <tr>
                            <td style="font-family:monospace; font-size:0.8rem;">#{{ $ticket->id }}</td>
                            <td>
                                <p class="ticket-subject">{{ $ticket->subject }}</p>
                                <p class="ticket-preview">{{ Str::limit($ticket->message, 50) }}</p>
                            </td>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar">{{ strtoupper(substr($ticket->user->name ?? 'U', 0, 1)) }}</div>
                                    <span>{{ $ticket->user->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $priorityColors = [
                                        'low' => 'badge-low',
                                        'medium' => 'badge-medium',
                                        'high' => 'badge-high',
                                        'urgent' => 'badge-urgent',
                                    ];
                                @endphp
                                <span class="badge {{ $priorityColors[$ticket->priority] ?? 'badge-low' }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'open' => 'badge-open',
                                        'in_progress' => 'badge-progress',
                                        'resolved' => 'badge-resolved',
                                        'closed' => 'badge-closed',
                                    ];
                                @endphp
                                <span class="badge {{ $statusColors[$ticket->status] ?? 'badge-closed' }}">
                                    {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                </span>
                            </td>
                            <td style="color:#9ca3af; font-size:0.85rem;">{{ $ticket->updated_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="view-link">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="fas fa-headset" style="font-size:1.8rem; display:block; margin-bottom:0.5rem;"></i>
                                No tickets found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            <p>Showing {{ $tickets->count() }} of {{ $tickets->total() }} tickets</p>
            {{ $tickets->links() }}
        </div>
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