@extends('layouts.admin')

@section('title', 'Manage Plans')

@section('content')
<style>
    .plan-page { font-family: Arial, sans-serif; color: #1f2937; }
    .plan-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.1rem; }
    .plan-header h1 { margin: 0; font-size: 1.6rem; color: #111827; }
    .plan-btn { padding: 0.7rem 1rem; background: #4f46e5; color: #fff; border: none; border-radius: 0.6rem; text-decoration: none; font-weight: 600; display: inline-block; }
    .plan-btn:hover { background: #4338ca; color: #fff; }
    .plan-grid { display: grid; grid-template-columns: 1fr; gap: 1rem; }
    .plan-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.8rem; padding: 1.1rem; box-shadow: 0 1px 2px rgba(0,0,0,0.04); display: flex; flex-direction: column; justify-content: space-between; }
    .plan-card h3 { margin: 0 0 0.5rem; color: #111827; font-size: 1.05rem; }
    .plan-pill { display: inline-block; padding: 0.28rem 0.6rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; }
    .plan-pill.active { background: #dcfce7; color: #166534; }
    .plan-pill.inactive { background: #f3f4f6; color: #4b5563; }
    .plan-price { font-size: 1.7rem; font-weight: 700; color: #111827; }
    .plan-price small { font-size: 0.9rem; color: #6b7280; }
    .plan-section { border-top: 1px solid #e5e7eb; padding-top: 0.8rem; margin-top: 0.8rem; }
    .plan-list { display: grid; grid-template-columns: 1fr; gap: 0.45rem; font-size: 0.9rem; color: #4b5563; }
    .plan-actions { display: flex; gap: 0.45rem; align-items: center; }
    .plan-link { display: inline-block; padding: 0.45rem 0.65rem; border-radius: 0.5rem; text-decoration: none; font-size: 0.8rem; font-weight: 700; }
    .plan-link.edit { background: #eef2ff; color: #4338ca; }
    .plan-link.delete { background: #fef2f2; color: #b91c1c; border: 0; cursor: pointer; }
    .empty-state { text-align: center; padding: 2rem 1rem; color: #6b7280; border: 1px dashed #d1d5db; border-radius: 0.8rem; background: #fff; }
    @media (min-width: 768px) { .plan-grid { grid-template-columns: repeat(2,1fr); } }
    @media (min-width: 1200px) { .plan-grid { grid-template-columns: repeat(3,1fr); } }
</style>

<div class="plan-page">
    <div class="plan-header">
        <h1>Plans</h1>
        <a href="{{ route('admin.plans.create') }}" class="plan-btn"><i class="fas fa-plus"></i> Add Plan</a>
    </div>

    <div class="plan-grid">
        @forelse($plans as $plan)
            <div class="plan-card">
                <div>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.7rem;">
                        <h3>{{ $plan->name }}</h3>
                        @if($plan->is_active)
                            <span class="plan-pill active">Active</span>
                        @else
                            <span class="plan-pill inactive">Inactive</span>
                        @endif
                    </div>

                    <div style="margin-bottom:0.7rem;">
                        <span class="plan-price">₹{{ number_format($plan->price, 0) }}</span>
                        <small>/ {{ ucfirst($plan->billing_cycle) }}</small>
                    </div>

                    @if($plan->annual_price)
                        <p style="color:#16a34a; margin:0 0 0.7rem; font-size:0.9rem;">Annual: ₹{{ number_format($plan->annual_price, 0) }}</p>
                    @endif

                    <div class="plan-section">
                        <p style="font-size:0.75rem; text-transform:uppercase; color:#9ca3af; margin:0 0 0.5rem; font-weight:700;">Limits</p>
                        <div class="plan-list">
                            <div><i class="fas fa-qrcode"></i> {{ ($plan->limits['qr_codes'] ?? 0) == -1 ? 'Unlimited' : ($plan->limits['qr_codes'] ?? 0) }} QR Codes</div>
                            <div><i class="fas fa-star"></i> {{ ($plan->limits['reviews_per_month'] ?? 0) == -1 ? 'Unlimited' : ($plan->limits['reviews_per_month'] ?? 0) }} Reviews/mo</div>
                            <div><i class="fas fa-code-branch"></i> {{ ($plan->limits['branches'] ?? 0) == -1 ? 'Unlimited' : ($plan->limits['branches'] ?? 0) }} Branches</div>
                            <div><i class="fas fa-users"></i> {{ ($plan->limits['employees'] ?? 0) == -1 ? 'Unlimited' : ($plan->limits['employees'] ?? 0) }} Employees</div>
                        </div>
                    </div>

                    @if($plan->features)
                        <div class="plan-section">
                            <p style="font-size:0.75rem; text-transform:uppercase; color:#9ca3af; margin:0 0 0.5rem; font-weight:700;">Features</p>
                            <ul style="margin:0; padding-left:1rem; color:#4b5563;">
                                @foreach(array_slice($plan->features, 0, 3) as $feature)
                                    <li style="margin-bottom:0.25rem;">{{ $feature }}</li>
                                @endforeach
                                @if(count($plan->features) > 3)
                                    <li style="color:#4f46e5; font-size:0.85rem;">+ {{ count($plan->features) - 3 }} more</li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="plan-section" style="display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:0.8rem; color:#9ca3af;">Subs: {{ $plan->subscriptions_count ?? $plan->subscriptions()->count() }}</span>
                    <div class="plan-actions">
                        <a href="{{ route('admin.plans.edit', $plan) }}" class="plan-link edit"><i class="fas fa-pen"></i> Edit</a>
                        <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Delete this plan?')">
                            @csrf
                            @method('DELETE')
                            <button class="plan-link delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-tags" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i>
                <p>No plans created yet.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection