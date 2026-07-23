@extends('layouts.admin')

@section('title', 'Payments')

@section('content')
<style>
    .payments-page { font-family: Arial, sans-serif; color: #1f2937; }
    .payments-topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; gap: 1rem; }
    .payments-title { font-size: 1.5rem; font-weight: 700; color: #111827; margin: 0; }
    .payments-controls { display: flex; gap: 0.75rem; flex-wrap: wrap; }
    .payments-controls select, .payments-controls input { padding: 0.65rem 0.9rem; border: 1px solid #d1d5db; border-radius: 0.6rem; font-size: 0.95rem; }
    .payments-controls input { width: 16rem; }
    .payments-controls .search-wrap { position: relative; }
    .payments-controls .search-wrap i { position: absolute; left: 0.8rem; top: 50%; transform: translateY(-50%); color: #9ca3af; }
    .payments-controls .search-wrap input { padding-left: 2.3rem; }
    .summary-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; margin-bottom: 1.25rem; }
    .summary-card { padding: 1rem; border-radius: 0.8rem; border: 1px solid #e5e7eb; }
    .summary-card.success { background: #f0fdf4; border-color: #bbf7d0; }
    .summary-card.pending { background: #fffbeb; border-color: #fde68a; }
    .summary-card.failed { background: #fef2f2; border-color: #fecaca; }
    .summary-label { font-size: 0.9rem; margin: 0; }
    .summary-value { font-size: 1.4rem; font-weight: 700; margin: 0.25rem 0 0; }
    .payments-table-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.8rem; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
    .payments-table-card table { width: 100%; border-collapse: collapse; font-size: 0.95rem; color: #4b5563; }
    .payments-table-card th, .payments-table-card td { padding: 0.8rem 0.9rem; border-bottom: 1px solid #e5e7eb; text-align: left; }
    .payments-table-card thead { background: #f9fafb; text-transform: uppercase; font-size: 0.72rem; color: #374151; }
    .payments-table-card tbody tr:hover { background: #f9fafb; }
    .user-cell { display: flex; align-items: center; gap: 0.6rem; }
    .avatar { width: 2rem; height: 2rem; border-radius: 999px; background: #eef2ff; display: flex; align-items: center; justify-content: center; color: #4f46e5; font-size: 0.8rem; font-weight: 700; }
    .status-pill { display: inline-block; padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
    .status-success { background: #dcfce7; color: #166534; }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-failed { background: #fee2e2; color: #b91c1c; }
    .view-link { color: #4f46e5; text-decoration: none; font-weight: 600; }
    .view-link:hover { color: #3730a3; }
    .empty-state { text-align: center; color: #9ca3af; padding: 1.6rem 0; }
    .pagination-wrap { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.1rem; background: #f9fafb; border-top: 1px solid #e5e7eb; }
    .pagination-wrap p { margin: 0; color: #6b7280; font-size: 0.95rem; }
    @media (max-width: 768px) { .payments-topbar { flex-direction: column; align-items: flex-start; } .summary-grid { grid-template-columns: 1fr; } .payments-controls input { width: 100%; } }
/* ===========================================================
   PAYMENTS PAGE - DARK MODE ONLY
   (Light mode remains EXACTLY the same)
=========================================================== */

body:not(.light-mode) .payments-page{
    color: var(--child-text);
}

/* Header */

body:not(.light-mode) .payments-title{
    color: var(--child-text);
}

/* Controls */

body:not(.light-mode) .payments-controls select,
body:not(.light-mode) .payments-controls input{
    background: var(--child-bg);
    color: var(--child-text);
    border: 1px solid var(--child-border);
}

body:not(.light-mode) .payments-controls input::placeholder{
    color: var(--child-text-sec);
}

body:not(.light-mode) .payments-controls select:focus,
body:not(.light-mode) .payments-controls input:focus{
    outline: none;
    border-color: var(--accent-glow);
    box-shadow: 0 0 0 3px var(--accent-glow-soft);
}

body:not(.light-mode) .payments-controls .search-wrap i{
    color: var(--child-text-sec);
}

/* Summary Cards */

body:not(.light-mode) .summary-card{
    border: 1px solid var(--child-border);
}

body:not(.light-mode) .summary-card.success{
    background: rgba(16,185,129,.12);
    border-color: rgba(16,185,129,.25);
}

body:not(.light-mode) .summary-card.pending{
    background: rgba(245,158,11,.12);
    border-color: rgba(245,158,11,.25);
}

body:not(.light-mode) .summary-card.failed{
    background: rgba(239,68,68,.12);
    border-color: rgba(239,68,68,.25);
}

body:not(.light-mode) .summary-label{
    color: var(--child-text-sec);
}

body:not(.light-mode) .summary-value{
    color: var(--child-text);
}

/* Table */

body:not(.light-mode) .payments-table-card{
    background: var(--child-bg);
    border: 1px solid var(--child-border);
    box-shadow: 0 10px 25px rgba(0,0,0,.35);
}

body:not(.light-mode) .payments-table-card table{
    color: var(--child-text);
}

body:not(.light-mode) .payments-table-card thead{
    background: rgba(255,255,255,.04);
    color: var(--child-text-sec);
}

body:not(.light-mode) .payments-table-card th,
body:not(.light-mode) .payments-table-card td{
    border-bottom: 1px solid var(--child-border);
}

body:not(.light-mode) .payments-table-card tbody tr:hover{
    background: rgba(255,255,255,.03);
}

/* Avatar */

body:not(.light-mode) .avatar{
    background: rgba(99,102,241,.15);
    color: #a5b4fc;
}

/* Status Pills */

body:not(.light-mode) .status-success{
    background: rgba(16,185,129,.15);
    color: #34d399;
}

body:not(.light-mode) .status-pending{
    background: rgba(245,158,11,.15);
    color: #fbbf24;
}

body:not(.light-mode) .status-failed{
    background: rgba(239,68,68,.15);
    color: #f87171;
}

/* Links */

body:not(.light-mode) .view-link{
    color: #818cf8;
}

body:not(.light-mode) .view-link:hover{
    color: #a5b4fc;
}

/* Empty State */

body:not(.light-mode) .empty-state{
    color: var(--child-text-sec);
}

/* Pagination */

body:not(.light-mode) .pagination-wrap{
    background: rgba(255,255,255,.03);
    border-top: 1px solid var(--child-border);
}

body:not(.light-mode) .pagination-wrap p{
    color: var(--child-text-sec);
}
</style>

<div class="payments-page">
    <div class="payments-topbar">
        <h1 class="payments-title">Payments</h1>
        <div class="payments-controls">
            <select id="status-filter">
                <option value="">All Status</option>
                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <div class="search-wrap">
                <input type="text" id="search" placeholder="Search...">
                <i class="fas fa-search"></i>
            </div>
        </div>
    </div>

    <div class="summary-grid">
        <div class="summary-card success">
            <p class="summary-label" style="color:#15803d;">Successful</p>
            <p class="summary-value" style="color:#166534;">₹{{ number_format($payments->where('status', 'success')->sum('amount'), 0) }}</p>
        </div>
        <div class="summary-card pending">
            <p class="summary-label" style="color:#b45309;">Pending</p>
            <p class="summary-value" style="color:#92400e;">₹{{ number_format($payments->where('status', 'pending')->sum('amount'), 0) }}</p>
        </div>
        <div class="summary-card failed">
            <p class="summary-label" style="color:#dc2626;">Failed</p>
            <p class="summary-value" style="color:#b91c1c;">₹{{ number_format($payments->where('status', 'failed')->sum('amount'), 0) }}</p>
        </div>
    </div>

    <div class="payments-table-card">
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>User</th>
                        <th>Plan</th>
                        <th>Amount</th>
                        <th>Gateway</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="payments-table">
                    @forelse($payments as $payment)
                        <tr>
                            <td style="font-family:monospace; font-size:0.8rem;">{{ Str::limit($payment->transaction_id ?? 'N/A', 15) }}</td>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar">{{ strtoupper(substr($payment->user->name ?? 'U', 0, 1)) }}</div>
                                    <span style="font-weight:600; color:#111827;">{{ $payment->user->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td>{{ $payment->plan->name ?? 'N/A' }}</td>
                            <td style="font-weight:700; color:#111827;">₹{{ number_format($payment->amount) }}</td>
                            <td><span style="font-size:0.8rem; text-transform:uppercase;">{{ $payment->gateway ?? 'N/A' }}</span></td>
                            <td>
                                @php
                                    $statusColors = [
                                        'success' => 'status-success',
                                        'pending' => 'status-pending',
                                        'failed' => 'status-failed',
                                    ];
                                    $color = $statusColors[$payment->status] ?? 'status-pending';
                                @endphp
                                <span class="status-pill {{ $color }}">{{ ucfirst($payment->status) }}</span>
                            </td>
                            <td style="color:#9ca3af;">{{ $payment->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <a href="{{ route('admin.payments.show', $payment) }}" class="view-link">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                <i class="fas fa-credit-card" style="font-size:1.8rem; display:block; margin-bottom:0.5rem;"></i>
                                No payments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            <p>Showing {{ $payments->count() }} of {{ $payments->total() }} payments</p>
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('status-filter').addEventListener('change', function(e) {
    window.location.href = `{{ route('admin.payments.index') }}?status=` + e.target.value;
});

document.getElementById('search').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#payments-table tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(search) ? '' : 'none';
    });
});
</script>
@endpush