@extends('layouts.admin')

@section('title', 'Business Details')

@section('content')
<style>
    .business-detail-page { font-family: Arial, sans-serif; color: #1f2937; }
    .back-link { color: #4f46e5; text-decoration: none; font-size: 0.95rem; font-weight: 600; display: inline-block; margin-bottom: 1.2rem; }
    .back-link:hover { color: #3730a3; }
    .detail-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem; }
    .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.8rem; padding: 1.2rem; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
    .card-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.2rem; padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb; }
    .business-logo { width: 4.5rem; height: 4.5rem; border-radius: 0.8rem; object-fit: cover; border: 1px solid #e5e7eb; }
    .logo-placeholder { width: 4.5rem; height: 4.5rem; border-radius: 0.8rem; display: flex; align-items: center; justify-content: center; background: #eef2ff; color: #4f46e5; font-size: 1.6rem; }
    .business-name { font-size: 1.2rem; font-weight: 700; color: #111827; margin: 0; }
    .business-slug { font-size: 0.9rem; color: #6b7280; margin: 0.25rem 0 0; }
    .status-pill { display: inline-block; margin-top: 0.6rem; padding: 0.25rem 0.7rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
    .status-active { background: #dcfce7; color: #166534; }
    .status-inactive { background: #f3f4f6; color: #4b5563; }
    .info-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
    .info-item { padding: 0.25rem 0; }
    .info-label { font-size: 0.85rem; color: #6b7280; margin-bottom: 0.25rem; }
    .info-value { color: #111827; font-weight: 600; }
    .full-width { grid-column: 1 / -1; }
    .table { width: 100%; border-collapse: collapse; font-size: 0.95rem; color: #4b5563; }
    .table th, .table td { padding: 0.75rem 0.5rem; border-bottom: 1px solid #e5e7eb; text-align: left; }
    .table thead { background: #f9fafb; text-transform: uppercase; font-size: 0.75rem; color: #374151; }
    .owner-avatar { width: 3rem; height: 3rem; background: #eef2ff; color: #4f46e5; border-radius: 999px; display: flex; align-items: center; justify-content: center; font-weight: 700; margin: 0 auto 0.7rem; }
    .owner-name { font-weight: 700; color: #111827; margin: 0.25rem 0 0; }
    .owner-email { font-size: 0.9rem; color: #6b7280; margin: 0.2rem 0 0; }
    .meta-row { display: flex; justify-content: space-between; padding: 0.35rem 0; font-size: 0.95rem; }
    .meta-label { color: #6b7280; }
    .meta-value { color: #111827; font-weight: 600; }
    .plan-box { text-align: center; padding: 0.8rem 0; }
    .plan-icon { width: 3rem; height: 3rem; background: #fef3c7; color: #d97706; border-radius: 999px; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.7rem; font-size: 1.1rem; }
    .empty-state { text-align: center; color: #9ca3af; padding: 1.2rem 0; }
    @media (max-width: 992px) { .detail-grid { grid-template-columns: 1fr; } }
    @media (max-width: 576px) { .info-grid { grid-template-columns: 1fr; } }
</style>

<div class="business-detail-page">
    <div>
        <a href="{{ route('admin.businesses.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Businesses
        </a>
    </div>

    <div class="detail-grid">
        <div style="display:flex; flex-direction:column; gap:1.25rem;">
            <div class="card">
                <div class="card-header">
                    @if($business->logo)
                        <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}" class="business-logo">
                    @else
                        <div class="logo-placeholder"><i class="fas fa-building"></i></div>
                    @endif
                    <div>
                        <h2 class="business-name">{{ $business->name }}</h2>
                        <p class="business-slug">{{ $business->slug ?? 'N/A' }}</p>
                        @if($business->status === 'active')
                            <span class="status-pill status-active">Active</span>
                        @else
                            <span class="status-pill status-inactive">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <p class="info-label">Contact Email</p>
                        <p class="info-value">{{ $business->email ?? 'N/A' }}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Phone Number</p>
                        <p class="info-value">{{ $business->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="info-item full-width">
                        <p class="info-label">Address</p>
                        <p class="info-value">{{ $business->address ?? 'N/A' }}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Created At</p>
                        <p class="info-value">{{ $business->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Last Updated</p>
                        <p class="info-value">{{ $business->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 style="margin:0 0 1rem; font-size:1.05rem; font-weight:700;">Subscription History</h3>
                @if($business->subscriptions && $business->subscriptions->count() > 0)
                    <div style="overflow-x:auto;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Plan</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($business->subscriptions as $sub)
                                    <tr>
                                        <td style="font-weight:600; color:#111827;">{{ $sub->plan->name ?? 'Deleted Plan' }}</td>
                                        <td>
                                            <span class="status-pill {{ $sub->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                                {{ ucfirst($sub->status) }}
                                            </span>
                                        </td>
                                        <td style="color:#6b7280;">{{ $sub->created_at->format('M d, Y') }}</td>
                                        <td style="color:#6b7280;">{{ $sub->ends_at ? $sub->ends_at->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="empty-state">No subscriptions found for this business.</p>
                @endif
            </div>
        </div>

        <div style="display:flex; flex-direction:column; gap:1.25rem;">
            <div class="card">
                <h3 style="margin:0 0 1rem; font-size:1.05rem; font-weight:700;">Owner Details</h3>
                <div style="text-align:center; margin-bottom:1rem;">
                    <div class="owner-avatar">{{ strtoupper(substr($business->user->name ?? 'U', 0, 1)) }}</div>
                    <p class="owner-name">{{ $business->user->name ?? 'Unknown' }}</p>
                    <p class="owner-email">{{ $business->user->email ?? 'N/A' }}</p>
                </div>
                <div style="border-top:1px solid #e5e7eb; padding-top:0.8rem;">
                    <div class="meta-row">
                        <span class="meta-label">Joined</span>
                        <span class="meta-value">{{ $business->user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Role</span>
                        <span class="meta-value">
                            @if($business->user->is_admin)
                                Admin
                            @else
                                User
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 style="margin:0 0 1rem; font-size:1.05rem; font-weight:700;">Current Plan</h3>
                @if($business->subscription && $business->subscription->status === 'active')
                    <div class="plan-box">
                        <div class="plan-icon"><i class="fas fa-crown"></i></div>
                        <h4 style="margin:0.25rem 0; font-size:1rem; font-weight:700; color:#111827;">{{ $business->subscription->plan->name ?? 'Plan' }}</h4>
                        <p style="margin:0.4rem 0 0; font-size:1.35rem; font-weight:700; color:#4f46e5;">
                            ₹{{ number_format($business->subscription->plan->price ?? 0) }}
                            <span style="font-size:0.85rem; color:#9ca3af; font-weight:400;">/{{ $business->subscription->plan->billing_cycle ?? 'month' }}</span>
                        </p>
                    </div>
                    <div style="border-top:1px solid #e5e7eb; padding-top:0.8rem; margin-top:0.8rem;">
                        <div class="meta-row">
                            <span class="meta-label">Status</span>
                            <span class="meta-value" style="color:#16a34a;">Active</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">Expires On</span>
                            <span class="meta-value">{{ $business->subscription->ends_at ? $business->subscription->ends_at->format('M d, Y') : 'N/A' }}</span>
                        </div>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-exclamation-circle" style="font-size:1.8rem; display:block; margin-bottom:0.5rem;"></i>
                        No active subscription
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection