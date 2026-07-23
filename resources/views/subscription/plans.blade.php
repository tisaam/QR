@extends('layouts.app')

@section('title', 'Choose Your Plan')

@push('styles')
<style>
    /* Layout Grid: 1 column mobile, 3 columns desktop */
    .pricing-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-top: 50px;
    }
    @media (min-width: 768px) {
        .pricing-grid {
            grid-template-columns: repeat(3, 1fr); /* Forces 3 boxes in one line */
            gap: 2rem;
        }
    }

    /* Card Design */
    .pricing-card {
        background: #ffffff;
        border: 1.5px solid #e5e7eb;
        border-radius: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .pricing-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: #c7d2fe;
    }

    /* Current Plan Highlight */
    .pricing-card.is-current {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .pricing-card.is-current:hover {
        border-color: #2563eb;
    }
    .current-badge {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        text-align: center;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.5rem 0;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    /* Card Inner Content */
    .pricing-body {
        padding: 2rem 1.5rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .plan-name {
        font-size: 1.25rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 0.25rem;
    }
    .plan-desc {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 1.5rem;
        min-height: 2.5rem;
    }
    .plan-price {
        display: flex;
        align-items: baseline;
        margin-bottom: 0.5rem;
    }
    .plan-amount {
        font-size: 2.5rem;
        font-weight: 800;
        color: #111827;
        line-height: 1;
    }
    .plan-period {
        font-size: 0.875rem;
        color: #9ca3af;
        margin-left: 0.5rem;
        font-weight: 500;
    }
    .plan-annual {
        font-size: 0.8rem;
        color: #059669;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    /* Features List */
    .features-list {
        list-style: none;
        padding: 0;
        margin: 0 0 2rem 0;
        flex-grow: 1;
    }
    .features-list li {
        display: flex;
        align-items: flex-start;
        font-size: 0.875rem;
        color: #4b5563;
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }
    .features-list li i {
        color: #10b981;
        margin-right: 0.75rem;
        margin-top: 0.15rem;
        font-size: 0.75rem;
    }

    /* Modern Buttons */
    .btn-plan {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 0.875rem 1.5rem;
        font-size: 0.95rem;
        font-weight: 700;
        border-radius: 0.875rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-buy {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(59, 130, 246, 0.4);
    }
    .btn-buy:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.5);
    }
    .btn-current {
        background: #f3f4f6;
        color: #9ca3af;
        cursor: default;
        box-shadow: none;
    }
    /* ==========================================================
   DARK MODE - PRICING PLANS
========================================================== */

body:not(.light-mode) .pricing-card{
    background:#1e293b !important;
    border:1.5px solid #334155 !important;
    box-shadow:none !important;
}

body:not(.light-mode) .pricing-card:hover{
    background:#243447 !important;
    border-color:#4f46e5 !important;
    box-shadow:0 10px 25px rgba(0,0,0,.35) !important;
}

body:not(.light-mode) .pricing-card.is-current{
    border-color:#6366f1 !important;
    box-shadow:0 0 0 3px rgba(99,102,241,.20) !important;
}

body:not(.light-mode) .pricing-card.is-current:hover{
    border-color:#818cf8 !important;
}

body:not(.light-mode) .current-badge{
    background:linear-gradient(135deg,#6366f1,#4338ca) !important;
    color:#ffffff !important;
}

body:not(.light-mode) .plan-name{
    color:#f8fafc !important;
}

body:not(.light-mode) .plan-desc{
    color:#94a3b8 !important;
}

body:not(.light-mode) .plan-amount{
    color:#ffffff !important;
}

body:not(.light-mode) .plan-period{
    color:#cbd5e1 !important;
}

body:not(.light-mode) .plan-annual{
    color:#34d399 !important;
}

body:not(.light-mode) .features-list li{
    color:#cbd5e1 !important;
}

body:not(.light-mode) .features-list li i{
    color:#22c55e !important;
}

/* Buttons */

body:not(.light-mode) .btn-buy{
    background:linear-gradient(135deg,#6366f1,#4338ca) !important;
    color:#ffffff !important;
    box-shadow:0 6px 18px rgba(99,102,241,.35) !important;
}

body:not(.light-mode) .btn-buy:hover{
    background:linear-gradient(135deg,#4f46e5,#3730a3) !important;
    box-shadow:0 10px 22px rgba(99,102,241,.45) !important;
}

body:not(.light-mode) .btn-current{
    background:#334155 !important;
    color:#94a3b8 !important;
    border:1px solid #475569 !important;
}
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-gray-800 mb-3">Choose Your Plan</h1><br>
        <p class="text-gray-500 max-w-xl mx-auto">Select the perfect plan for your business. Upgrade or downgrade anytime.</p>
    </div>

    <!-- ✅ Custom Grid Class -->
    <div class="pricing-grid">
        @foreach($plans as $plan)
            <!-- ✅ Dynamic Card Class -->
            <div class="pricing-card {{ $currentPlan && $currentPlan->id === $plan->id ? 'is-current' : '' }}">
                
                @if($currentPlan && $currentPlan->id === $plan->id)
                    <div class="current-badge">✓ Current Plan</div>
                @endif

                <div class="pricing-body">
                    <h3 class="plan-name">{{ $plan->name }}</h3>
                    <p class="plan-desc">{{ $plan->description }}</p>
                    
                    <div class="plan-price">
                        <span class="plan-amount">${{ number_format($plan->price, 0) }}</span>
                        <span class="plan-period">/month</span>
                    </div>
                    
                    @if($plan->annual_price)
                        <div class="plan-annual">
                            <i class="fas fa-tag mr-1"></i> ${{ number_format($plan->annual_price, 0) }}/year (Save 20%)
                        </div>
                    @else
                        <div style="margin-bottom: 1.5rem;"></div>
                    @endif

                    <ul class="features-list">
                        @if($plan->features)
                            @foreach($plan->features as $feature)
                                <li>
                                    <i class="fas fa-check-circle"></i>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        @else
                            <li><i class="fas fa-check-circle"></i> Basic features included</li>
                        @endif
                    </ul>

                    @if($currentPlan && $currentPlan->id === $plan->id)
                        <button class="btn-plan btn-current" disabled>
                            Current Plan
                        </button>
                    @else
                        <form method="POST" action="{{ route('subscription.subscribe') }}">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <button type="submit" class="btn-plan btn-buy">
                                {{ $plan->price > 0 ? 'Get Started' : 'Activate Free' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection