@extends('layouts.admin')

@section('title', 'Manage Businesses')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    .biz-page { font-family: 'Inter', sans-serif; color: #1e293b; }

    /* Header */
    .biz-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 16px; flex-wrap: wrap; }
    .biz-header-left { display: flex; flex-direction: column; gap: 4px; }
    .biz-title { font-size: 22px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.03em; }
    .biz-subtitle { font-size: 13px; color: #94a3b8; margin: 0; font-weight: 500; }

    /* Search */
    .search-wrap { position: relative; }
    .search-wrap input {
        width: 280px; padding: 10px 14px 10px 40px; border: 1.5px solid #e2e8f0; border-radius: 12px;
        font-size: 13px; font-family: inherit; color: #1e293b; background: #fff;
        transition: border-color 0.2s, box-shadow 0.2s; outline: none;
    }
    .search-wrap input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
    .search-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px; }

    /* Stats Row */
    .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
    .stat-card {
        background: #fff; border: 1px solid #f1f5f9; border-radius: 14px; padding: 16px 18px;
        display: flex; align-items: center; gap: 14px; transition: box-shadow 0.2s;
    }
    .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.05); }
    .stat-icon {
        width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center;
        justify-content: center; font-size: 16px; flex-shrink: 0;
    }
    .stat-icon.blue { background: #eef2ff; color: #6366f1; }
    .stat-icon.green { background: #ecfdf5; color: #10b981; }
    .stat-icon.amber { background: #fffbeb; color: #f59e0b; }
    .stat-icon.red { background: #fef2f2; color: #ef4444; }
    .stat-number { font-size: 20px; font-weight: 800; color: #0f172a; line-height: 1; margin-bottom: 2px; }
    .stat-label { font-size: 12px; color: #94a3b8; font-weight: 500; }

    /* Table */
    .table-card {
        background: #fff; border: 1px solid #f1f5f9; border-radius: 16px;
        overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .table-card table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .table-card th, .table-card td { padding: 12px 16px; border-bottom: 1px solid #f1f5f9; text-align: left; vertical-align: middle; }
    .table-card thead th {
        background: #f8fafc; text-transform: uppercase; font-size: 11px; font-weight: 700;
        color: #64748b; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0;
    }
    .table-card tbody tr { transition: background 0.15s; }
    .table-card tbody tr:hover { background: #f8fafc; }
    .table-card tbody tr:last-child td { border-bottom: none; }

    /* Business Cell */
    .biz-cell { display: flex; align-items: center; gap: 12px; }
    .biz-thumb {
        width: 40px; height: 40px; border-radius: 10px; object-fit: cover;
        border: 1px solid #f1f5f9; flex-shrink: 0;
    }
    .biz-icon {
        width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center;
        justify-content: center; background: #eef2ff; color: #6366f1; font-size: 14px; flex-shrink: 0;
    }
    .biz-name { font-weight: 700; color: #0f172a; font-size: 13px; line-height: 1.3; }
    .biz-email-small { font-size: 11px; color: #94a3b8; margin-top: 1px; }

    /* Owner Cell */
    .owner-cell { display: flex; align-items: center; gap: 8px; }
    .owner-av {
        width: 28px; height: 28px; border-radius: 8px; background: #f1f5f9;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 800; color: #64748b; flex-shrink: 0;
    }

    /* Status Pills */
    .pill {
        display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px;
        border-radius: 8px; font-size: 11px; font-weight: 700; white-space: nowrap;
        border: 1px solid transparent;
    }
    .pill-dot { width: 6px; height: 6px; border-radius: 50%; }
    .pill-active { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
    .pill-active .pill-dot { background: #10b981; }
    .pill-inactive { background: #f8fafc; color: #94a3b8; border-color: #e2e8f0; }
    .pill-inactive .pill-dot { background: #cbd5e1; }
    .pill-blocked { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
    .pill-blocked .pill-dot { background: #ef4444; }
    .pill-suspended { background: #fffbeb; color: #d97706; border-color: #fde68a; }
    .pill-suspended .pill-dot { background: #f59e0b; }
    .pill-rejected { background: #f8fafc; color: #64748b; border-color: #e2e8f0; }
    .pill-rejected .pill-dot { background: #94a3b8; }

    /* Plan Badge */
    .plan-badge {
        display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px;
        border-radius: 8px; font-size: 11px; font-weight: 700;
        background: #eef2ff; color: #6366f1; border: 1px solid #c7d2fe;
    }
    .plan-badge.none { background: #f8fafc; color: #94a3b8; border-color: #e2e8f0; }

    /* Warning Badge */
    .warn-badge {
        display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px;
        border-radius: 6px; font-size: 11px; font-weight: 700;
    }
    .warn-badge.has { background: #fffbeb; color: #d97706; }
    .warn-badge.none { background: #f8fafc; color: #cbd5e1; }

    /* Usage Mini Bar */
    .usage-mini { display: flex; align-items: center; gap: 6px; font-size: 11px; color: #64748b; }
    .usage-bar-mini {
        width: 48px; height: 4px; background: #f1f5f9; border-radius: 4px; overflow: hidden;
    }
    .usage-fill-mini { height: 100%; border-radius: 4px; transition: width 0.3s; }
    .usage-fill-mini.ok { background: #10b981; }
    .usage-fill-mini.warn { background: #f59e0b; }
    .usage-fill-mini.danger { background: #ef4444; }

    /* Actions */
    .view-link {
        display: inline-flex; align-items: center; gap: 5px; color: #6366f1;
        text-decoration: none; font-weight: 600; font-size: 12px; padding: 6px 12px;
        border-radius: 8px; transition: all 0.15s;
    }
    .view-link:hover { background: #eef2ff; color: #4f46e5; }

    /* Pagination */
    .pagination-wrap {
        padding: 14px 18px; background: #f8fafc; border-top: 1px solid #f1f5f9;
        display: flex; justify-content: space-between; align-items: center;
    }
    .pagination-wrap p { margin: 0; color: #94a3b8; font-size: 13px; font-weight: 500; }

    /* Empty */
    .empty-row td { padding: 48px 16px !important; text-align: center; }
    .empty-icon { font-size: 32px; color: #e2e8f0; margin-bottom: 12px; }
    .empty-text { color: #94a3b8; font-size: 14px; }

    @media (max-width: 1024px) { .stats-row { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) {
        .biz-header { flex-direction: column; align-items: stretch; }
        .search-wrap input { width: 100%; }
        .stats-row { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 640px) { .stats-row { grid-template-columns: 1fr; } }

    /* ===========================================================
   DARK MODE ONLY
   (Light mode remains EXACTLY the same)
=========================================================== */

body:not(.light-mode) .biz-page{
    color:var(--child-text);
}

body:not(.light-mode) .biz-title{
    color:var(--child-text);
}

body:not(.light-mode) .biz-subtitle{
    color:var(--child-text-sec);
}

/* Search */

body:not(.light-mode) .search-wrap input{
    background:var(--child-bg);
    color:var(--child-text);
    border-color:var(--child-border);
}

body:not(.light-mode) .search-wrap input::placeholder{
    color:var(--child-text-sec);
}

body:not(.light-mode) .search-wrap i{
    color:var(--child-text-sec);
}

/* Cards */

body:not(.light-mode) .stat-card,
body:not(.light-mode) .table-card{
    background:var(--child-bg);
    border-color:var(--child-border);
    box-shadow:0 10px 25px rgba(0,0,0,.35);
}

body:not(.light-mode) .stat-number{
    color:var(--child-text);
}

body:not(.light-mode) .stat-label{
    color:var(--child-text-sec);
}

/* Table */

body:not(.light-mode) .table-card thead th{
    background:rgba(255,255,255,.04);
    color:var(--child-text-sec);
    border-color:var(--child-border);
}

body:not(.light-mode) .table-card tbody td{
    color:var(--child-text);
    border-color:var(--child-border);
}

body:not(.light-mode) .table-card tbody tr:hover{
    background:rgba(255,255,255,.03);
}

/* Business */

body:not(.light-mode) .biz-name{
    color:var(--child-text);
}

body:not(.light-mode) .biz-email-small{
    color:var(--child-text-sec);
}

body:not(.light-mode) .biz-thumb{
    border-color:var(--child-border);
}

body:not(.light-mode) .owner-av{
    background:#1e293b;
    color:#cbd5e1;
}

/* Pagination */

body:not(.light-mode) .pagination-wrap{
    background:var(--child-bg);
    border-color:var(--child-border);
}

body:not(.light-mode) .pagination-wrap p{
    color:var(--child-text-sec);
}

/* Empty */

body:not(.light-mode) .empty-text{
    color:var(--child-text-sec);
}

body:not(.light-mode) .empty-icon{
    color:#475569;
}

/* Usage */

body:not(.light-mode) .usage-bar-mini{
    background:#334155;
}

/* View Button */

body:not(.light-mode) .view-link:hover{
    background:rgba(99,102,241,.15);
}

/* Icons */

body:not(.light-mode) .biz-icon{
    background:rgba(99,102,241,.15);
}

body:not(.light-mode) .stat-icon.blue{
    background:rgba(99,102,241,.15);
}

body:not(.light-mode) .stat-icon.green{
    background:rgba(16,185,129,.15);
}

body:not(.light-mode) .stat-icon.amber{
    background:rgba(245,158,11,.15);
}

body:not(.light-mode) .stat-icon.red{
    background:rgba(239,68,68,.15);
}
</style>

<div class="biz-page">

    {{-- Header --}}
    <div class="biz-header">
        <div class="biz-header-left">
            <h1 class="biz-title">Businesses</h1>
            <p class="biz-subtitle">Manage all registered businesses, plans & actions</p>
        </div>
        <div class="search-wrap">
            <input type="text" id="search" placeholder="Search by name, email, owner...">
            <i class="fas fa-search"></i>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-building"></i></div>
            <div>
                <div class="stat-number">{{ $businesses->total() }}</div>
                <div class="stat-label">Total Businesses</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="stat-number">{{ $businesses->where('status', 'active')->count() ?? \App\Models\Business::where('status','active')->count() }}</div>
                <div class="stat-label">Active</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <div class="stat-number">{{ \App\Models\Warning::count() }}</div>
                <div class="stat-label">Total Warnings</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-ban"></i></div>
            <div>
                <div class="stat-number">{{ \App\Models\Business::where('status','blocked')->count() }}</div>
                <div class="stat-label">Blocked</div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-card">
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Business</th>
                        <th>Owner</th>
                        <th>Status</th>
                        <th>Plan</th>
                        <th>QR Usage</th>
                        <th>Warnings</th>
                        <th>Joined</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody id="businesses-table">
                    @forelse($businesses as $business)
                        @php
                            $activeSub = $business->subscriptions->first(fn($s) => $s->status === 'active');
                            $plan = $activeSub?->plan;
                            $qrUsed = $business->qrCodes->count();
                            $qrLimit = $plan->qr_limit ?? 0;
                            $qrPct = $qrLimit > 0 ? min(($qrUsed / $qrLimit) * 100, 100) : 0;
                            $qrClass = $qrPct >= 90 ? 'danger' : ($qrPct >= 70 ? 'warn' : 'ok');
                            $warnCount = $business->warnings->count();
                        @endphp
                        <tr>
                            <td>
                                <div class="biz-cell">
                                    @if($business->logo)
                                        <img src="{{ Storage::url($business->logo) }}" alt="" class="biz-thumb">
                                    @else
                                        <div class="biz-icon"><i class="fas fa-building"></i></div>
                                    @endif
                                    <div>
                                        <div class="biz-name">{{ $business->name }}</div>
                                        <div class="biz-email-small">{{ $business->email ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="owner-cell">
                                    <div class="owner-av">{{ strtoupper(substr($business->user->name ?? 'U', 0, 1)) }}</div>
                                    <span style="font-weight:600; font-size:13px;">{{ $business->user->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $statusClass = match($business->status) {
                                        'active'    => 'pill-active',
                                        'blocked'   => 'pill-blocked',
                                        'suspended' => 'pill-suspended',
                                        'rejected'  => 'pill-rejected',
                                        default     => 'pill-inactive',
                                    };
                                @endphp
                                <span class="pill {{ $statusClass }}">
                                    <span class="pill-dot"></span>
                                    {{ ucfirst($business->status) }}
                                </span>
                            </td>
                            <td>
                                @if($activeSub)
                                    <span class="plan-badge">
                                        <i class="fas fa-crown" style="font-size:9px;"></i>
                                        {{ $plan->name ?? 'Plan' }}
                                    </span>
                                @else
                                    <span class="plan-badge none">No Plan</span>
                                @endif
                            </td>
                            <td>
                                <div class="usage-mini">
                                    <div class="usage-bar-mini">
                                        <div class="usage-fill-mini {{ $qrClass }}" style="width:{{ $qrPct }}%"></div>
                                    </div>
                                    <span>{{ $qrUsed }}{{ $qrLimit > 0 ? '/' . $qrLimit : '' }}</span>
                                </div>
                            </td>
                            <td>
                                @if($warnCount > 0)
                                    <span class="warn-badge has"><i class="fas fa-exclamation-circle" style="font-size:10px;"></i> {{ $warnCount }}</span>
                                @else
                                    <span class="warn-badge none">0</span>
                                @endif
                            </td>
                            <td style="color:#94a3b8; font-size:12px; white-space:nowrap;">{{ $business->created_at->format('M d, Y') }}</td>
                            <td style="text-align:center;">
                                <a href="{{ route('admin.businesses.show', $business) }}" class="view-link">
                                    <i class="fas fa-arrow-right"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="8">
                                <div class="empty-icon"><i class="fas fa-building"></i></div>
                                <div class="empty-text">No businesses found.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            <p>Showing {{ $businesses->count() }} of {{ $businesses->total() }}</p>
            {{ $businesses->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('search').addEventListener('input', function(e) {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('#businesses-table tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endpush