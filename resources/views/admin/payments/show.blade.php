@extends('layouts.admin')

@section('title', 'Payment Details')

@section('content')
<style>
    .payment-detail-page { font-family: Arial, sans-serif; color: #1f2937; }
    .payment-back { color: #4f46e5; text-decoration: none; font-size: 0.95rem; font-weight: 600; display: inline-block; margin-bottom: 1.2rem; }
    .payment-back:hover { color: #3730a3; }
    .payment-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem; }
    .payment-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.8rem; padding: 1.25rem; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
    .payment-card h2, .payment-card h3 { margin: 0 0 1rem; color: #111827; }
    .payment-info-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
    .payment-info-item { padding: 0.25rem 0; }
    .payment-label { font-size: 0.85rem; color: #6b7280; margin-bottom: 0.25rem; }
    .payment-value { color: #111827; font-weight: 600; }
    .payment-amount { font-size: 1.35rem; font-weight: 700; color: #111827; }
    .payment-status { display: inline-block; padding: 0.3rem 0.7rem; border-radius: 999px; font-size: 0.8rem; font-weight: 600; }
    .status-success { background: #dcfce7; color: #166534; }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-failed { background: #fee2e2; color: #b91c1c; }
    .gateway-response { background: #f9fafb; padding: 1rem; border-radius: 0.6rem; font-size: 0.85rem; overflow-x: auto; }
    .user-avatar { width: 3rem; height: 3rem; border-radius: 999px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-weight: 700; margin: 0 auto 0.7rem; }
    .user-name { font-weight: 700; color: #111827; margin: 0.25rem 0 0; }
    .user-email { font-size: 0.9rem; color: #6b7280; margin: 0.2rem 0 0; }
    .detail-row { display: flex; justify-content: space-between; padding: 0.4rem 0; font-size: 0.95rem; border-top: 1px solid #e5e7eb; margin-top: 0.5rem; }
    .detail-label { color: #6b7280; }
    .detail-value { color: #111827; font-weight: 600; }
    .business-link { color: #4f46e5; text-decoration: none; font-weight: 600; }
    .business-link:hover { color: #3730a3; }
    @media (max-width: 992px) { .payment-grid { grid-template-columns: 1fr; } }
    @media (max-width: 576px) { .payment-info-grid { grid-template-columns: 1fr; } }
</style>

<div class="payment-detail-page">
    <a href="{{ route('admin.payments.index') }}" class="payment-back">
        <i class="fas fa-arrow-left"></i> Back to Payments
    </a>

    <div class="payment-grid">
        <div class="payment-card">
            <h2>Payment Information</h2>
            <div class="payment-info-grid">
                <div class="payment-info-item">
                    <p class="payment-label">Transaction ID</p>
                    <p class="payment-value" style="font-family:monospace;">{{ $payment->transaction_id ?? 'N/A' }}</p>
                </div>
                <div class="payment-info-item">
                    <p class="payment-label">Amount</p>
                    <p class="payment-amount">₹{{ number_format($payment->amount) }}</p>
                </div>
                <div class="payment-info-item">
                    <p class="payment-label">Gateway</p>
                    <p class="payment-value">{{ strtoupper($payment->gateway ?? 'N/A') }}</p>
                </div>
                <div class="payment-info-item">
                    <p class="payment-label">Status</p>
                    <p>
                        <span class="payment-status {{ $payment->status === 'success' ? 'status-success' : ($payment->status === 'pending' ? 'status-pending' : 'status-failed') }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </p>
                </div>
                <div class="payment-info-item">
                    <p class="payment-label">Created At</p>
                    <p class="payment-value">{{ $payment->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div class="payment-info-item">
                    <p class="payment-label">Currency</p>
                    <p class="payment-value">INR</p>
                </div>
            </div>

            @if($payment->gateway_response)
                <div style="margin-top:1rem;">
                    <p class="payment-label">Gateway Response</p>
                    <pre class="gateway-response">{{ json_encode($payment->gateway_response, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif
        </div>

        <div class="payment-card">
            <h3>User Details</h3>
            <div style="text-align:center; margin-bottom:0.8rem;">
                <div class="user-avatar">{{ strtoupper(substr($payment->user->name ?? 'U', 0, 1)) }}</div>
                <p class="user-name">{{ $payment->user->name ?? 'Unknown' }}</p>
                <p class="user-email">{{ $payment->user->email ?? 'N/A' }}</p>
            </div>

            @if($payment->plan)
                <div class="detail-row">
                    <span class="detail-label">Plan</span>
                    <span class="detail-value">{{ $payment->plan->name }}</span>
                </div>
            @endif

            @if($payment->user && $payment->user->business)
                <div class="detail-row">
                    <span class="detail-label">Business</span>
                    <a href="{{ route('admin.businesses.show', $payment->user->business) }}" class="business-link">{{ $payment->user->business->name }}</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection