@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    .dashboard-wrapper {
        font-family: Arial, sans-serif;
        color: #1f2937;
    }

    .dashboard-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.25rem;
        color: #1f2937;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: #ffffff;
        padding: 1.25rem;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.4rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111827;
    }

    .stat-value.green {
        color: #16a34a;
    }

    .stat-value.indigo {
        color: #4f46e5;
    }

    .payments-card {
        background: #ffffff;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        padding: 1.25rem;
    }

    .payments-card h3 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #111827;
    }

    .payments-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
        color: #4b5563;
    }

    .payments-table th,
    .payments-table td {
        padding: 0.75rem 0.9rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    .payments-table thead {
        background: #f9fafb;
        text-transform: uppercase;
        font-size: 0.75rem;
        color: #374151;
    }

    .payments-table .user-name {
        font-weight: 600;
        color: #111827;
    }

    .status-pill {
        display: inline-block;
        padding: 0.25rem 0.6rem;
        border-radius: 999px;
        font-size: 0.75rem;
        background: #dcfce7;
        color: #166534;
    }

    .empty-state {
        text-align: center;
        color: #9ca3af;
        padding: 2rem 0;
    }

    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
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
                        <td><span class="status-pill">{{ ucfirst($payment->status) }}</span></td>
                        <td>{{ $payment->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty-state">No payments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection