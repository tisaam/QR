@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<style>
.dashboard-wrapper{
    font-family:'Inter',Arial,sans-serif;
    color:var(--child-text);
}

.dashboard-title{
    font-size:1.5rem;
    font-weight:700;
    margin-bottom:1.5rem;
    color:var(--child-text);
    letter-spacing:-.025em;
}

/* Stats */

.stats-grid{
    display:grid;
    grid-template-columns:repeat(4,minmax(0,1fr));
    gap:1.25rem;
    margin-bottom:2rem;
}

.stat-card{
    background:var(--child-bg);
    color:var(--child-text);
    padding:1.5rem;
    border-radius:.75rem;
    border:1px solid var(--child-border);
    box-shadow:0 1px 10px var(--shadow-color);
    transition:.25s;
}

.stat-card:hover{
    transform:translateY(-4px);
    box-shadow:0 20px 35px var(--shadow-color);
}

.stat-label{
    font-size:.8rem;
    font-weight:500;
    color:var(--child-text-sec);
    margin-bottom:.5rem;
    text-transform:uppercase;
    letter-spacing:.05em;
}

.stat-value{
    font-size:1.75rem;
    font-weight:800;
    color:var(--child-text);
}

.stat-value.green{
    color:#10b981;
}

.stat-value.indigo{
    color:#6366f1;
}

/* Payments */

.payments-card{
    background:var(--child-bg);
    color:var(--child-text);
    border-radius:.75rem;
    border:1px solid var(--child-border);
    box-shadow:0 1px 10px var(--shadow-color);
    padding:1.5rem;
}

.payments-card h3{
    color:var(--child-text);
    font-size:1.05rem;
    font-weight:700;
    margin-bottom:1.25rem;
}

.table-wrapper{
    width:100%;
    overflow-x:auto;
}

.payments-table{
    width:100%;
    min-width:600px;
    border-collapse:collapse;
    color:var(--child-text);
}

.payments-table th,
.payments-table td{
    padding:.9rem 1rem;
    border-bottom:1px solid var(--child-border);
}

.payments-table thead th{
    background:var(--accent-glow-soft);
    color:var(--child-text-sec);
    text-transform:uppercase;
    font-size:.72rem;
    letter-spacing:.05em;
}

.payments-table tbody tr{
    transition:.2s;
}

.payments-table tbody tr:hover{
    background:var(--accent-glow-soft);
}

.user-name{
    color:var(--child-text);
    font-weight:600;
}

/* Status */

.status-pill{
    display:inline-flex;
    align-items:center;
    padding:.25rem .75rem;
    border-radius:999px;
    font-size:.75rem;
    font-weight:600;
}

.status-pill.paid{
    background:#dcfce7;
    color:#166534;
}

.status-pill.pending{
    background:#fef9c3;
    color:#854d0e;
}

.status-pill.failed{
    background:#fee2e2;
    color:#991b1b;
}

.empty-state{
    text-align:center;
    color:var(--child-text-sec);
    padding:3rem 0;
}

@media(max-width:1024px){
.stats-grid{
grid-template-columns:repeat(2,1fr);
}
}

@media(max-width:576px){
.stats-grid{
grid-template-columns:1fr;
}

.dashboard-title{
font-size:1.25rem;
}
}
</style>

<div class="dashboard-wrapper">
    <h1 class="dashboard-title">SaaS Dashboard</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <p class="stat-label">Total Businesses</p>
            <p class="stat-value">{{ $totalBusinesses }}</p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Active Subscriptions</p>
            <p class="stat-value green">{{ $activeSubscriptions }}</p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Total Revenue</p>
            <p class="stat-value">₹{{ number_format($totalRevenue, 0) }}</p>
        </div>
        <div class="stat-card">
            <p class="stat-label">This Month</p>
            <p class="stat-value indigo">₹{{ number_format($monthlyRevenue, 0) }}</p>
        </div>
    </div>

    <div class="payments-card">
        <h3>Recent Payments</h3>
        
        <!-- Added .table-wrapper to fix mobile CSS breaking -->
        <div class="table-wrapper">
            <table class="payments-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments as $payment)
                        <tr>
                            <td class="user-name">{{ $payment->user->name ?? 'Unknown' }}</td>
                            <td><strong>₹{{ number_format($payment->amount) }}</strong></td>
                            <!-- Dynamic class based on status -->
                            <td><span class="status-pill {{ $payment->status ?? 'paid' }}">{{ ucfirst($payment->status ?? 'paid') }}</span></td>
                            <td>{{ $payment->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty-state">No payments yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection