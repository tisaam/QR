@extends('layouts.app')
 
@section('title', 'My Subscription Details')
 
@push('styles')
<style>
    .subs-wrapper {
        max-width: 56rem;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
 
    .subs-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
    }
    .page-title {
        font-size: 1.55rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .page-title i { color: #eab308; } /* Gold for crown/plan */
 
    .view-plans-link {
        font-size: 0.850rem;
        font-weight: 500;
        color: var(--accent-glow);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: gap 0.2s ease;
        margin-left: 10px;
    }
    .view-plans-link:hover { gap: 0.75rem; }
 
    /* --- Glass Card --- */
    .glass-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-glass);
        border-radius: 1.25rem;
        padding: 1.75rem;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 0 20px 30px rgba(15, 23, 42, 0.1);
        margin-bottom: 1.5rem;
    }
 
    .grid-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    @media (max-width: 1024px) {
        .grid-container { grid-template-columns: 1fr; }
    }
 
    /* --- Plan Header --- */
    .plan-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--text-primary);
        margin: 0 0 0.25rem 0;
    }
    .plan-desc {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
    }
    .plan-price {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-primary);
        margin: 0;
    }
    .plan-cycle {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }
    .plan-renew {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        opacity: 0.7;
        margin-top: 0.25rem;
    }
 
    /* --- Status Badges --- */
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 9999px;
        border: 1px solid transparent;
    }
    .badge-active { background: rgba(16, 185, 129, 0.15); color: #34d399; border-color: rgba(16, 185, 129, 0.2); }
    .badge-trialing { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border-color: rgba(245, 158, 11, 0.2); }
    .badge-inactive { background: rgba(239, 68, 68, 0.15); color: #f87171; border-color: rgba(239, 68, 68, 0.2); }
 
    /* --- Section Titles --- */
    .card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 1.25rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
 
    /* --- Features List --- */
    .feature-item {
        display: flex;
        align-items: flex-start;
        font-size: 0.9375rem;
        color: var(--text-secondary);
        margin-bottom: 0.75rem;
    }
    .feature-item:last-child { margin-bottom: 0; }
    .feature-check {
        color: var(--accent-glow);
        margin-right: 0.75rem;
        margin-top: 0.15rem;
        font-size: 0.875rem;
    }
 
    /* --- Progress Bars --- */
    .stat-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    .stat-label { font-size: 0.875rem; color: var(--text-secondary); }
    .stat-value { font-size: 0.875rem; font-weight: 700; color: var(--text-primary); }
    .stat-value.danger { color: #f87171; }
   
    .progress-track {
        width: 100%;
        height: 0.5rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 9999px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        border-radius: 9999px;
        transition: width 1s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .fill-cyan { background: linear-gradient(90deg, #06b6d4, #22d3ee); box-shadow: 0 0 12px rgba(6, 182, 212, 0.4); }
    .fill-amber { background: linear-gradient(90deg, #f59e0b, #fbbf24); box-shadow: 0 0 12px rgba(245, 158, 11, 0.4); }
    .fill-red { background: linear-gradient(90deg, #ef4444, #f87171); box-shadow: 0 0 12px rgba(239, 68, 68, 0.4); }
    .fill-green { background: linear-gradient(90deg, #10b981, #34d399); box-shadow: 0 0 12px rgba(16, 185, 129, 0.4); }
 
    /* --- Tool Access Grid --- */
    .tools-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 1rem;
    }
    .tool-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1.25rem 0.5rem;
        border-radius: 1rem;
        border: 1px solid var(--border-glass);
        text-align: center;
        transition: all 0.3s ease;
    }
    .tool-card.is-active {
        border-color: rgba(16, 185, 129, 0.2);
        background: rgba(16, 185, 129, 0.05);
    }
    .tool-card.is-locked {
        opacity: 0.5;
    }
    .tool-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
        font-size: 1.1rem;
    }
    .tool-card.is-active .tool-icon {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
        box-shadow: 0 0 15px rgba(16, 185, 129, 0.2);
    }
    .tool-card.is-locked .tool-icon {
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-secondary);
    }
    .tool-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
    }
    .tool-status {
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }
    .tool-card.is-active .tool-status { color: #34d399; }
    .tool-card.is-locked .tool-status { color: var(--text-secondary); }
 
    /* --- Actions Footer --- */
    .actions-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .footer-info {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .footer-btns {
        display: flex;
        gap: 0.75rem;
    }
   
    /* Buttons */
    .btn-cancel {
        padding: 0.65rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #f87171;
        background: transparent;
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-cancel:hover {
        background: rgba(239, 68, 68, 0.1);
        border-color: #f87171;
    }
    .btn-upgrade {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 0.70rem;
        background: var(--accent-glow);
        color: #ffffff;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 0.75rem;
        border: none;
        text-decoration: none;
        box-shadow: 0 0 20px var(--accent-glow-soft);
        transition: all 0.3s ease;
    }
    .btn-upgrade:hover {
        background: #0891b2;
        box-shadow: 0 0 30px rgba(6, 182, 212, 0.4);
        transform: translateY(-2px);
    }
 
    /* --- Empty State --- */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }
    .empty-icon {
        width: 5rem;
        height: 5rem;
        border-radius: 9999px;
        background: rgba(234, 179, 8, 0.15); /* Gold glow for plan */
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: #eab308;
        font-size: 2rem;
        box-shadow: 0 0 30px rgba(234, 179, 8, 0.1);
    }
    .empty-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }
    .empty-text {
        font-size: 0.9375rem;
        color: var(--text-secondary);
        margin: 0 0 1.5rem 0;
        max-width: 24rem;
        margin-left: auto;
        margin-right: auto;
    }
 
    @media (max-width: 640px) {
        .actions-footer { flex-direction: column; align-items: stretch; text-align: center; }
        .footer-btns { justify-content: center; }
    }
</style>
@endpush
 
@section('content')
<div class="subs-wrapper">
   
    <div class="subs-header">
        <h1 class="page-title"><i class="fas fa-crown"></i> My Subscription</h1>
        <a href="{{ route('plans.index') }}" class="view-plans-link">
            View All Plans <i class="fas fa-arrow-right"></i>
        </a>
    </div>
 
    @if($subscription && $subscription->plan)
        @php
            $plan = $subscription->plan;
            $limits = $plan->limits ?? [];
            $features = $plan->features ?? [];
           
            // Get current usage counts
            $usage = [
                'qr_codes' => auth()->user()->business->qrCodes()->count() ?? 0,
                'branches' => auth()->user()->business->branches()->count() ?? 0,
            ];
        @endphp
 
        <!-- Plan Header Card -->
        <div class="glass-card">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; gap: 1.5rem; align-items: center;">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem;">
                        <h2 class="plan-title">{{ $plan->name }}</h2>
                        <span class="badge {{ $subscription->status === 'active' ? 'badge-active' : ($subscription->status === 'trialing' ? 'badge-trialing' : 'badge-inactive') }}">
                            {{ strtoupper($subscription->status) }}
                        </span>
                    </div>
                    <p class="plan-desc">{{ $plan->description }}</p>
                </div>
                <div style="text-align: left;">
                    <div class="plan-price">${{ number_format($plan->price, 2) }}</div>
                    <div class="plan-cycle">/ {{ $plan->billing_cycle }}</div>
                    @if($subscription->ends_at)
                        <div class="plan-renew">Renews on {{ $subscription->ends_at->format('M d, Y') }}</div>
                    @endif
                </div>
            </div>
        </div>
 
        <div class="grid-container">
           
            <!-- 1. What's Included -->
            <div class="glass-card">
                <h3 class="card-title"><i class="fas fa-sparkles" style="color: #eab308;"></i> What's Included</h3>
                @if(count($features) > 0)
                    <div>
                        @foreach($features as $feature)
                            <div class="feature-item">
                                <i class="fas fa-check-circle feature-check"></i>
                                <span>{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="font-size: 0.875rem; color: var(--text-secondary); opacity: 0.7;">No specific features listed for this plan.</p>
                @endif
            </div>
 
            <!-- 2. Resource Usage -->
            <div class="glass-card">
                <h3 class="card-title"><i class="fas fa-chart-pie" style="color: var(--accent-glow);"></i> Resource Usage</h3>
                <div>
                    @foreach(['qr_codes' => 'QR Codes', 'branches' => 'Branches', 'reviews_per_month' => 'Reviews/Month', 'ai_credits' => 'AI Credits'] as $key => $label)
                        @php
                            $limit = $limits[$key] ?? 0;
                            $used = $usage[$key] ?? 0;
                            $isUnlimited = ($limit <= 0);
                            $percent = $isUnlimited ? 0 : min(100, ($used / $limit) * 100);
                        @endphp
                       
                        <div style="margin-bottom: 1.25rem;">
                            <div class="stat-header">
                                <span class="stat-label">{{ $label }}</span>
                                <span class="stat-value {{ $percent >= 90 && !$isUnlimited ? 'danger' : '' }}">
                                    {{ $isUnlimited ? 'Unlimited' : "$used / $limit" }}
                                </span>
                            </div>
                            <div class="progress-track">
                                @if($isUnlimited)
                                    <div class="progress-fill fill-green" style="width: 100%"></div>
                                @elseif($percent >= 90)
                                    <div class="progress-fill fill-red" style="width: {{ $percent }}%"></div>
                                @else
                                    <div class="progress-fill fill-cyan" style="width: {{ $percent }}%"></div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
 
        <!-- 3. Tool & Feature Access -->
        <div class="glass-card">
            <h3 class="card-title"><i class="fas fa-puzzle-piece" style="color: #a78bfa;"></i> Tool & Feature Access</h3>
            <div class="tools-grid">
                @foreach([
                    'whatsapp' => ['label' => 'WhatsApp', 'icon' => 'fab fa-whatsapp'],
                    'sms' => ['label' => 'SMS', 'icon' => 'fas fa-sms'],
                    'nfc' => ['label' => 'NFC Cards', 'icon' => 'fas fa-wifi'],
                    'white_label' => ['label' => 'White Label', 'icon' => 'fas fa-tag'],
                    'custom_branding' => ['label' => 'Custom Branding', 'icon' => 'fas fa-palette'],
                    'remove_branding' => ['label' => 'Remove Branding', 'icon' => 'fas fa-eye-slash']
                ] as $key => $data)
                    @php $isEnabled = ($limits[$key] ?? false); @endphp
                   
                    <div class="tool-card {{ $isEnabled ? 'is-active' : 'is-locked' }}">
                        <div class="tool-icon">
                            <i class="{{ $data['icon'] }}"></i>
                        </div>
                        <div class="tool-name">{{ $data['label'] }}</div>
                        <div class="tool-status">
                            {{ $isEnabled ? 'Active' : 'Locked' }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
 
        <!-- Actions Footer -->
        <div class="glass-card">
            <div class="actions-footer">
                <p class="footer-info">
                    <i class="fas fa-info-circle"></i>
                    Need more resources? Upgrade your plan.
                </p>
                <div class="footer-btns">
                    @if($subscription->status === 'active')
                        <form method="POST" action="{{ route('subscription.cancel') }}" onsubmit="return confirm('Are you sure? You will lose access at the end of your billing period.')">
                            @csrf
                            <button type="submit" class="btn-cancel">
                                Cancel Plan
                            </button>
                        </form>
                    @endif
                   
                    <a href="{{ route('plans.index') }}" class="btn-upgrade">
                        <i class="fas fa-rocket"></i> Upgrade Plan
                    </a>
                </div>
            </div>
        </div>
 
    @else
        <!-- No Subscription State -->
        <div class="glass-card">
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-crown"></i></div>
                <h3 class="empty-title">No Active Plan</h3>
                <p class="empty-text">You are currently not subscribed to any plan. Choose a plan to unlock all features for your business.</p>
                <a href="{{ route('plans.index') }}" class="btn-upgrade">
                    <i class="fas fa-arrow-right"></i> Browse Plans
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
 