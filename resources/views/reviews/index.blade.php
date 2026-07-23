@extends('layouts.app')

@section('title', 'Reviews')

@push('styles')
<style>
    .review-page {
        max-width: 72rem;
        margin: 0 auto;
        padding: 0 1rem;
    }
    .review-header {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    @media (min-width: 640px) {
        .review-header {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
    }
    .review-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        border: 1px solid #d1d5db;
        background: #ffffff;
        color: #374151;
        font-size: 0.875rem;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.2s ease, border-color 0.2s ease;
    }
    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #cbd5e1;
    }
    .review-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .review-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 1.25rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    @media (min-width: 768px) {
        .review-card {
            flex-direction: row;
            align-items: center;
        }
    }
    .review-customer {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        width: 100%;
    }
    @media (min-width: 768px) {
        .review-customer {
            width: 25%;
        }
    }
    .customer-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 9999px;
        background: #dbeafe;
        color: #2563eb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .customer-info {
        min-width: 0;
    }
    .customer-name {
        margin: 0;
        font-weight: 600;
        color: #111827;
    }
    .customer-date {
        margin: 0.25rem 0 0;
        font-size: 0.75rem;
        color: #9ca3af;
    }
    .review-main {
        width: 100%;
    }
    @media (min-width: 768px) {
        .review-main {
            width: 50%;
        }
    }
    .review-stars {
        display: flex;
        gap: 0.15rem;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    .star-filled {
        color: #f59e0b;
    }
    .star-empty {
        color: #e5e7eb;
    }
    .review-text {
        margin: 0;
        color: #374151;
        font-size: 0.9375rem;
        line-height: 1.6;
    }
    .review-meta {
        width: 100%;
        text-align: left;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    @media (min-width: 768px) {
        .review-meta {
            width: 25%;
            text-align: right;
        }
    }
    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    .badge.published {
        background: #dcfce7;
        color: #166534;
    }
    .badge.pending {
        background: #fef9c3;
        color: #92400e;
    }
    .badge.other {
        background: #f3f4f6;
        color: #4b5563;
    }
    .review-source {
        margin: 0;
        color: #9ca3af;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.25rem;
    }
    .empty-state {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 1.25rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
        padding: 2.5rem;
        text-align: center;
        color: #9ca3af;
        font-size: 0.95rem;
    }
    .pagination {
        margin-top: 1.5rem;
    }
    /* ==========================================================
   DARK MODE
========================================================== */

body:not(.light-mode) .review-page{
    color:#e5e7eb !important;
}

/* Title */
body:not(.light-mode) .review-title{
    color:#f8fafc !important;
}

/* Secondary Button */
body:not(.light-mode) .btn-secondary{
    background:#1e293b !important;
    border:1px solid #334155 !important;
    color:#e2e8f0 !important;
}

body:not(.light-mode) .btn-secondary:hover{
    background:#334155 !important;
    border-color:#475569 !important;
}

/* Review Card */
body:not(.light-mode) .review-card,
body:not(.light-mode) .empty-state{
    background:#1e293b !important;
    border:1px solid #334155 !important;
    box-shadow:none !important;
}

/* Customer Avatar */
body:not(.light-mode) .customer-avatar{
    background:#312e81 !important;
    color:#c7d2fe !important;
}

/* Customer Name */
body:not(.light-mode) .customer-name{
    color:#f8fafc !important;
}

/* Date */
body:not(.light-mode) .customer-date{
    color:#94a3b8 !important;
}

/* Review Text */
body:not(.light-mode) .review-text{
    color:#cbd5e1 !important;
}

/* Source */
body:not(.light-mode) .review-source{
    color:#94a3b8 !important;
}

/* Empty Stars */
body:not(.light-mode) .star-empty{
    color:#475569 !important;
}

/* Badges */
body:not(.light-mode) .badge.published{
    background:#14532d !important;
    color:#bbf7d0 !important;
}

body:not(.light-mode) .badge.pending{
    background:#78350f !important;
    color:#fde68a !important;
}

body:not(.light-mode) .badge.other{
    background:#334155 !important;
    color:#e2e8f0 !important;
}

/* Pagination */
body:not(.light-mode) .pagination{
    color:#e2e8f0 !important;
}

body:not(.light-mode) .pagination a,
body:not(.light-mode) .pagination span{
    background:#1e293b !important;
    color:#e2e8f0 !important;
    border:1px solid #334155 !important;
}

body:not(.light-mode) .pagination .active span{
    background:#4f46e5 !important;
    color:#fff !important;
    border-color:#4f46e5 !important;
}
</style>
@endpush

@section('content')
<div class="review-page">
    <div class="review-header">
        <h1 class="review-title">Customer Reviews</h1>
        <a href="{{ route('reviews.export') }}" class="btn-secondary">
            <i class="fas fa-file-excel"></i>Export CSV
        </a>
    </div>

    <div class="review-list">
        @forelse($reviews as $review)
            <div class="review-card">
                <div class="review-customer">
                    <div class="customer-avatar">{{ strtoupper(substr($review->customer_name ?? 'A', 0, 1)) }}</div>
                    <div class="customer-info">
                        <p class="customer-name">{{ $review->customer_name ?? 'Anonymous' }}</p>
                        <p class="customer-date">{{ $review->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="review-main">
                    <div class="review-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $review->rating ? 'star-filled' : 'star-empty' }}"></i>
                        @endfor
                    </div>
                    <p class="review-text">{{ $review->review_text }}</p>
                </div>

                <div class="review-meta">
                    <span class="badge {{ $review->status === 'published' ? 'published' : ($review->status === 'pending' ? 'pending' : 'other') }}">
                        {{ ucfirst($review->status) }}
                    </span>
                    <p class="review-source">
                        <i class="fas fa-qrcode"></i>{{ $review->qrCode->name ?? 'Direct' }}
                    </p>
                </div>
            </div>
        @empty
            <div class="empty-state">
                No reviews collected yet.
            </div>
        @endforelse
    </div>

    <div class="pagination">
        {{ $reviews->links() }}
    </div>
</div>
@endsection