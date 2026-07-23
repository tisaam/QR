@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    /* Base Layout */
    .dashboard-page { max-width: 1280px; margin: 0 auto; padding: 0 1.5rem 3rem; }
    .dashboard-header { margin-bottom: 2rem; }
    .dashboard-title { font-size: 1.75rem; font-weight: 800; color: #111827; margin: 0 0 0.25rem 0; }
    .dashboard-subtitle { color: #6b7280; font-size: 0.9rem; margin: 0; }

    /* Stats Grid (4 Columns) */
    .stats-grid { display: grid; gap: 1.25rem; margin-bottom: 1.5rem; grid-template-columns: 1fr; }
    @media (min-width: 640px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .stats-grid { grid-template-columns: repeat(4, 1fr); } }
    
    .stat-card {
        background: #ffffff; border: 1px solid #f3f4f6; border-radius: 1.25rem; 
        padding: 1.5rem; transition: all 0.2s ease;
    }
    .stat-card:hover { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.07); transform: translateY(-2px); }
    .stat-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
    .stat-label { font-size: 0.8rem; font-weight: 500; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em; }
    .stat-value { font-size: 2rem; font-weight: 800; color: #111827; line-height: 1.1; margin-top: 0.25rem; }
    .stat-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
    .stat-icon--blue { background: #eff6ff; color: #2563eb; }
    .stat-icon--yellow { background: #fffbeb; color: #d97706; }
    .stat-icon--green { background: #ecfdf5; color: #059669; }
    .stat-icon--purple { background: #f5f3ff; color: #7c3aed; }
    .stat-note { color: #16a34a; font-size: 0.75rem; font-weight: 600; display: flex; align-items: center; gap: 4px; }

    /* Main Grid (2 Columns) */
    .dashboard-grid { display: grid; gap: 1.5rem; grid-template-columns: 1fr; }
    @media (min-width: 1024px) { .dashboard-grid { grid-template-columns: 1.6fr 1fr; } }

    /* Cards */
    .card {
        background: #ffffff; border: 1px solid #f3f4f6; border-radius: 1.25rem; 
        padding: 1.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
    }
    .card-heading { font-size: 1.1rem; font-weight: 700; color: #111827; margin: 0 0 1.25rem 0; display: flex; align-items: center; justify-content: space-between;}
    .card-heading a { font-size: 0.8rem; font-weight: 600; color: #2563eb; text-decoration: none; }
    .card-heading a:hover { text-decoration: underline; }

    /* Plan Widget */
    .plan-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem; }
    .plan-name { font-size: 1.25rem; font-weight: 800; color: #111827; }
    .plan-badge { padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-active { background: #dcfce7; color: #166534; }
    .badge-inactive { background: #fee2e2; color: #991b1b; }
    
    .limits-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; background: #f9fafb; padding: 1rem; border-radius: 0.875rem; margin-bottom: 1rem; }
    .limit-item-text { font-size: 0.8rem; color: #6b7280; }
    .limit-item-value { font-size: 0.875rem; font-weight: 700; color: #111827; }
    
    .features-wrap { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .feature-pill{
    display: inline-flex;
    align-items: center;
    gap: 6px;          /* Space between icon and text */
    padding: 0.35rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.7rem;
    font-weight: 600;
}
    .pill-active { background: #ecfdf5; color: #065f46; }
    .pill-locked { background: #f3f4f6; color: #9ca3af; }
    
    .no-plan-cta { text-align: center; padding: 1.5rem 0; }
    .btn-subscribe { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: #111827; color: #fff; border-radius: 0.75rem; font-weight: 600; font-size: 0.875rem; text-decoration: none; transition: background 0.2s; }
    .btn-subscribe:hover { background: #1f2937; }

    /* Reviews List */
    .review-list { display: flex; flex-direction: column; }
    .review-item { display: flex; align-items: flex-start; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid #f3f4f6; }
    .review-item:last-child { border-bottom: none; }
    .review-avatar { width: 40px; height: 40px; border-radius: 50%; background: #e5e7eb; color: #4b5563; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.9rem; }
    .review-content { flex: 1; min-width: 0; }
    .review-name { font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 0.25rem; }
    .review-stars { display: flex; gap: 2px; margin-bottom: 0.5rem; }
    .review-stars i { font-size: 0.75rem; }
    .review-text { color: #6b7280; font-size: 0.8rem; line-height: 1.5; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .review-meta { color: #9ca3af; font-size: 0.7rem; white-space: nowrap; margin-left: auto; padding-top: 0.25rem; }

    /* Quick Actions */
    .action-list { display: flex; flex-direction: column; gap: 0.75rem; }
    .action-link { display: flex; align-items: center; gap: 0.75rem; padding: 1rem 1.25rem; border-radius: 1rem; text-decoration: none; font-weight: 600; font-size: 0.875rem; transition: all 0.2s ease; border: 1px solid transparent; }
    .action-link i { width: 20px; text-align: center; }
    
    .action-blue { background: #eff6ff; color: #1d4ed8; }
    .action-blue:hover { background: #dbeafe; border-color: #bfdbfe; }
    .action-gray { background: #f9fafb; color: #374151; }
    .action-gray:hover { background: #f3f4f6; border-color: #e5e7eb; }
    .action-purple { background: #faf5ff; color: #7c3aed; }
    .action-purple:hover { background: #f3e8ff; border-color: #e9d5ff; }
    .action-green { background: #ecfdf5; color: #047857; }
    .action-green:hover { background: #d1fae5; border-color: #a7f3d0; }

    .empty-state { color: #9ca3af; font-size: 0.875rem; text-align: center; padding: 2rem 0; }
/* ==========================================================
   DARK MODE (Only)
========================================================== */

body:not(.light-mode) .dashboard-title{
    color:#f8fafc !important;
}

body:not(.light-mode) .dashboard-subtitle{
    color:#94a3b8 !important;
}

/* Cards */
body:not(.light-mode) .stat-card,
body:not(.light-mode) .card{
    background:#1e293b !important;
    border:1px solid #334155 !important;
    box-shadow:none !important;
}

/* Card Hover */
body:not(.light-mode) .stat-card:hover{
    background:#243244 !important;
    border-color:#475569 !important;
}

/* Text */
body:not(.light-mode) .stat-label{
    color:#94a3b8 !important;
}

body:not(.light-mode) .stat-value,
body:not(.light-mode) .card-heading,
body:not(.light-mode) .plan-name,
body:not(.light-mode) .review-name,
body:not(.light-mode) .limit-item-value{
    color:#f8fafc !important;
}

body:not(.light-mode) .limit-item-text,
body:not(.light-mode) .review-text,
body:not(.light-mode) .review-meta,
body:not(.light-mode) .empty-state{
    color:#94a3b8 !important;
}

/* Links */
body:not(.light-mode) .card-heading a{
    color:#60a5fa !important;
}

body:not(.light-mode) .card-heading a:hover{
    color:#93c5fd !important;
}

/* Plan Limits */
body:not(.light-mode) .limits-grid{
    background:#0f172a !important;
    border:1px solid #334155;
}

/* Feature Pills */
body:not(.light-mode) .pill-active{
    background:#064e3b !important;
    color:#6ee7b7 !important;
}

body:not(.light-mode) .pill-locked{
    background:#334155 !important;
    color:#cbd5e1 !important;
}

/* Subscribe Button */
body:not(.light-mode) .btn-subscribe{
    background:#6366f1 !important;
    color:#fff !important;
}

body:not(.light-mode) .btn-subscribe:hover{
    background:#4f46e5 !important;
}

/* Review List */
body:not(.light-mode) .review-item{
    border-bottom:1px solid #334155 !important;
}

body:not(.light-mode) .review-avatar{
    background:#334155 !important;
    color:#f8fafc !important;
}

/* Action Buttons */
body:not(.light-mode) .action-blue{
    background:#1e3a8a !important;
    color:#bfdbfe !important;
}

body:not(.light-mode) .action-blue:hover{
    background:#1d4ed8 !important;
}

body:not(.light-mode) .action-gray{
    background:#334155 !important;
    color:#f8fafc !important;
}

body:not(.light-mode) .action-gray:hover{
    background:#475569 !important;
}

body:not(.light-mode) .action-purple{
    background:#4c1d95 !important;
    color:#ddd6fe !important;
}

body:not(.light-mode) .action-purple:hover{
    background:#5b21b6 !important;
}

body:not(.light-mode) .action-green{
    background:#14532d !important;
    color:#bbf7d0 !important;
}

body:not(.light-mode) .action-green:hover{
    background:#166534 !important;
}

/* Stat Icons */
body:not(.light-mode) .stat-icon--blue{
    background:#1e3a8a !important;
    color:#93c5fd !important;
}

body:not(.light-mode) .stat-icon--yellow{
    background:#78350f !important;
    color:#fde68a !important;
}

body:not(.light-mode) .stat-icon--green{
    background:#14532d !important;
    color:#86efac !important;
}

body:not(.light-mode) .stat-icon--purple{
    background:#4c1d95 !important;
    color:#ddd6fe !important;
}

/* Badges */
body:not(.light-mode) .badge-active{
    background:#14532d !important;
    color:#bbf7d0 !important;
}

body:not(.light-mode) .badge-inactive{
    background:#7f1d1d !important;
    color:#fecaca !important;
}
/* ==========================================================
   RESPONSIVE DESIGN
========================================================== */

/* Tablets */
@media (max-width: 1024px) {

    .dashboard-page{
        padding:0 1rem 2rem;
    }

    .dashboard-grid{
        grid-template-columns:1fr;
    }

    .card,
    .stat-card{
        padding:1.25rem;
    }

    .stat-value{
        font-size:1.75rem;
    }
}

/* Mobile */
@media (max-width:768px){

    .dashboard-page{
        padding:0 .75rem 1.5rem;
    }

    .dashboard-header{
        margin-bottom:1.25rem;
    }

    .dashboard-title{
        font-size:1.45rem;
    }

    .dashboard-subtitle{
        font-size:.82rem;
    }

    .stats-grid{
        grid-template-columns:1fr;
        gap:1rem;
    }

    .dashboard-grid{
        grid-template-columns:1fr;
        gap:1rem;
    }

    .card,
    .stat-card{
        padding:1rem;
        border-radius:18px;
    }

    .stat-row{
        align-items:center;
    }

    .stat-value{
        font-size:1.6rem;
    }

    .stat-icon{
        width:42px;
        height:42px;
        font-size:1rem;
    }

    .card-heading{
        font-size:1rem;
        flex-direction:column;
        align-items:flex-start;
        gap:.4rem;
    }

    .card-heading a{
        font-size:.78rem;
    }

    .plan-header{
        flex-direction:column;
        align-items:flex-start;
    }

    .plan-name{
        font-size:1.1rem;
    }

    .limits-grid{
        grid-template-columns:1fr;
        gap:.75rem;
    }

    .features-wrap{
        gap:.4rem;
    }

    .feature-pill{
        font-size:.68rem;
        padding:.3rem .6rem;
    }

    .review-item{
        gap:.75rem;
    }

    .review-avatar{
        width:36px;
        height:36px;
        font-size:.8rem;
    }

    .review-name{
        font-size:.82rem;
    }

    .review-text{
        white-space:normal;
        overflow:visible;
        text-overflow:unset;
    }

    .review-meta{
        display:block;
        margin-top:.4rem;
        margin-left:0;
        white-space:normal;
    }

    .action-link{
        padding:.9rem 1rem;
        font-size:.82rem;
        border-radius:.85rem;
    }

    .btn-subscribe{
        width:100%;
        justify-content:center;
    }
}

/* Small Mobile */
@media (max-width:480px){

    .dashboard-page{
        padding:0 .6rem 1rem;
    }

    .dashboard-title{
        font-size:1.3rem;
    }

    .dashboard-subtitle{
        font-size:.78rem;
    }

    .card,
    .stat-card{
        padding:.9rem;
    }

    .stat-label{
        font-size:.7rem;
    }

    .stat-value{
        font-size:1.45rem;
    }

    .stat-icon{
        width:38px;
        height:38px;
        font-size:.9rem;
        border-radius:10px;
    }

    .plan-name{
        font-size:1rem;
    }

    .feature-pill{
        width:100%;
        justify-content:center;
    }

    .action-link{
        padding:.85rem;
        font-size:.8rem;
    }

    .review-item{
        flex-direction:column;
    }

    .review-avatar{
        margin-bottom:.25rem;
    }

    .review-meta{
        margin-top:.4rem;
    }

    .limits-grid{
        padding:.8rem;
    }
}
</style>

<div class="dashboard-page">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Dashboard</h1>
        <p class="dashboard-subtitle">Welcome back, {{ Auth::user()->name }}!</p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-row">
                <div>
                    <div class="stat-label">Total QR Scans</div>
                    <div class="stat-value">{{ $totalScans }}</div>
                </div>
                <div class="stat-icon stat-icon--blue"><i class="fas fa-qrcode"></i></div>
            </div>
            <div class="stat-note"><i class="fas fa-arrow-up"></i> {{ $monthlyGrowth }}% this month</div>
        </div>

        <div class="stat-card">
            <div class="stat-row">
                <div>
                    <div class="stat-label">Total Reviews</div>
                    <div class="stat-value">{{ $totalReviews }}</div>
                </div>
                <div class="stat-icon stat-icon--yellow"><i class="fas fa-star"></i></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-row">
                <div>
                    <div class="stat-label">Avg Rating</div>
                    <div class="stat-value">{{ number_format($avgRating, 1) }} <span style="font-size: 1rem; color: #9ca3af; font-weight: 500;">/ 5</span></div>
                </div>
                <div class="stat-icon stat-icon--green"><i class="fas fa-chart-line"></i></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-row">
                <div>
                    <div class="stat-label">Conversion Rate</div>
                    <div class="stat-value">{{ $conversionRate }}%</div>
                </div>
                <div class="stat-icon stat-icon--purple"><i class="fas fa-bullseye"></i></div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="dashboard-grid">
        
        <!-- Left Column: Reviews -->
        <div class="card">
            <h2 class="card-heading">
                Recent Reviews
                <a href="{{ route('reviews.index') }}">View All</a>
            </h2>
            <div class="review-list">
                @forelse($recentReviews as $review)
                    <div class="review-item">
                        <div class="review-avatar">{{ strtoupper(substr($review->customer_name ?? 'U', 0, 1)) }}</div>
                        <div class="review-content">
                            <div class="review-name">{{ $review->customer_name ?? 'Anonymous' }}</div>
                            <div class="review-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star" style="color: {{ $i <= $review->rating ? '#f59e0b' : '#e5e7eb' }}"></i>
                                @endfor
                            </div>
                            <div class="review-text">{{ Str::limit($review->review_text, 80) }}</div>
                        </div>
                        <div class="review-meta">{{ $review->created_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <div class="empty-state">No reviews yet. Create a QR code to get started!</div>
                @endforelse
            </div>
        </div>

        <!-- Right Column -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            <!-- Current Plan Widget -->
            <div class="card">
                <h2 class="card-heading">
                    Current Plan
                    <a href="{{ route('subscription.current') }}">Details</a>
                </h2>

                @php
                    $sub = auth()->user()->activeSubscription;
                    $plan = $sub?->plan;
                    $limits = $plan?->limits ?? [];
                @endphp

                @if($sub && $plan)
                    <div class="plan-header">
                        <div class="plan-name">{{ $plan->name }}</div>
                        <span class="plan-badge {{ $sub->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                            {{ ucfirst($sub->status) }}
                        </span>
                    </div>

                    <div class="limits-grid">
                        <div>
                            <div class="limit-item-text">QR Codes</div>
                            <div class="limit-item-value">{{ ($limits['qr_codes'] ?? 0) <= 0 ? 'Unlimited' : $limits['qr_codes'] }}</div>
                        </div>
                        <div>
                            <div class="limit-item-text">Branches</div>
                            <div class="limit-item-value">{{ ($limits['branches'] ?? 0) <= 0 ? 'Unlimited' : $limits['branches'] }}</div>
                        </div>
                        <div>
                            <div class="limit-item-text">Reviews/Mo</div>
                            <div class="limit-item-value">{{ ($limits['reviews_per_month'] ?? 0) <= 0 ? 'Unlimited' : $limits['reviews_per_month'] }}</div>
                        </div>
                        <div>
                            <div class="limit-item-text">AI Credits</div>
                            <div class="limit-item-value">{{ ($limits['ai_credits'] ?? 0) <= 0 ? 'Unlimited' : $limits['ai_credits'] }}</div>
                        </div>
                    </div>

                    <div class="features-wrap">
                        @foreach(['whatsapp' => 'WhatsApp', 'sms' => 'SMS', 'nfc' => 'NFC', 'white_label' => 'White Label'] as $key => $label)
                            @php $isEnabled = ($limits[$key] ?? false); @endphp
                            <span class="feature-pill {{ $isEnabled ? 'pill-active' : 'pill-locked' }}">
                                <i class="fas {{ $isEnabled ? 'fa-check' : 'fa-lock' }} mr-1" style="font-size: 0.6rem;"></i>
                                {{ $label }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <div class="no-plan-cta">
                        <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">No active plan found.</p>
                        <a href="{{ route('plans.index') }}" class="btn-subscribe">
                            <i class="fas fa-crown"></i> Subscribe Now
                        </a>
                    </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="card" style="flex-grow: 1;">
                <h2 class="card-heading">Quick Actions</h2>
                <div class="action-list">
                    <a href="{{ route('qr-codes.create') }}" class="action-link action-blue">
                        <i class="fas fa-plus-circle"></i> Create New QR Code
                    </a>
                    <a href="{{ route('qr-codes.index') }}" class="action-link action-gray">
                        <i class="fas fa-download"></i> Download QR Codes
                    </a>
                    <a href="{{ route('plans.index') }}" class="action-link action-purple">
                        <i class="fas fa-crown"></i> Upgrade Plan
                    </a>
                    <a href="{{ route('whatsapp.index') }}" class="action-link action-green">
                        <i class="fab fa-whatsapp"></i> WhatsApp Campaign
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection