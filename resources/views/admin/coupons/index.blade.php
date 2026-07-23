@extends('layouts.admin')

@section('title', 'Manage Coupons')

@section('content')
    <style>
        .coupon-list-page {
            font-family: Arial, sans-serif;
            color: #1f2937;
        }

        .coupon-list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            gap: 1rem;
        }

        .coupon-list-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .coupon-create-btn {
            display: inline-block;
            background: #4f46e5;
            color: #fff;
            padding: 0.7rem 1rem;
            border-radius: 0.6rem;
            text-decoration: none;
            font-weight: 600;
        }

        .coupon-create-btn:hover {
            background: #4338ca;
        }

        .coupon-list-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.8rem;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .coupon-list-card table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
            color: #4b5563;
        }

        .coupon-list-card th,
        .coupon-list-card td {
            padding: 0.8rem 0.9rem;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        .coupon-list-card thead {
            background: #f9fafb;
            text-transform: uppercase;
            font-size: 0.72rem;
            color: #374151;
        }

        .coupon-code {
            font-family: monospace;
            font-weight: 700;
            color: #4f46e5;
        }

        .coupon-name {
            font-weight: 600;
            color: #111827;
        }

        .coupon-badge {
            display: inline-block;
            padding: 0.25rem 0.65rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .coupon-badge.active {
            background: #dcfce7;
            color: #166534;
        }

        .coupon-badge.disabled {
            background: #f3f4f6;
            color: #4b5563;
        }

        .delete-btn {
            color: #dc2626;
            background: none;
            border: none;
            cursor: pointer;
        }

        .empty-state {
            text-align: center;
            color: #9ca3af;
            padding: 1.5rem 0;
        }

        @media (max-width: 768px) {
            .coupon-list-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        /* ===========================================================
       COUPONS LIST PAGE - DARK MODE ONLY
       (Light mode remains EXACTLY the same)
    =========================================================== */

        body:not(.light-mode) .coupon-list-page {
            color: var(--child-text);
        }

        /* Header */

        body:not(.light-mode) .coupon-list-title {
            color: var(--child-text);
        }

        /* Create Button */

        body:not(.light-mode) .coupon-create-btn {
            background: #4f46e5;
            color: #fff;
        }

        body:not(.light-mode) .coupon-create-btn:hover {
            background: #4338ca;
        }

        /* Card */

        body:not(.light-mode) .coupon-list-card {
            background: var(--child-bg);
            border: 1px solid var(--child-border);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .35);
        }

        /* Table */

        body:not(.light-mode) .coupon-list-card table {
            color: var(--child-text);
        }

        body:not(.light-mode) .coupon-list-card thead {
            background: rgba(255, 255, 255, .04);
            color: var(--child-text-sec);
        }

        body:not(.light-mode) .coupon-list-card th,
        body:not(.light-mode) .coupon-list-card td {
            border-bottom: 1px solid var(--child-border);
        }

        body:not(.light-mode) .coupon-list-card tbody tr:hover {
            background: rgba(255, 255, 255, .03);
        }

        /* Coupon Code */

        body:not(.light-mode) .coupon-code {
            color: #818cf8;
        }

        /* Coupon Name */

        body:not(.light-mode) .coupon-name {
            color: var(--child-text);
        }

        /* Status Badges */

        body:not(.light-mode) .coupon-badge.active {
            background: rgba(16, 185, 129, .15);
            color: #34d399;
        }

        body:not(.light-mode) .coupon-badge.disabled {
            background: rgba(148, 163, 184, .12);
            color: #cbd5e1;
        }

        /* Delete Button */

        body:not(.light-mode) .delete-btn {
            color: #f87171;
        }

        body:not(.light-mode) .delete-btn:hover {
            color: #ef4444;
        }

        /* Empty State */

        body:not(.light-mode) .empty-state {
            color: var(--child-text-sec);
        }
        /* ===========================
   Responsive Table
=========================== */

.coupon-table-responsive{
    width:100%;
    overflow-x:auto;
    -webkit-overflow-scrolling:touch;
    border-radius:12px;
}

.coupon-table-responsive table{
    width:100%;
    min-width:900px;
    border-collapse:collapse;
}

.coupon-table-responsive::-webkit-scrollbar{
    height:8px;
}

.coupon-table-responsive::-webkit-scrollbar-thumb{
    background:#cbd5e1;
    border-radius:20px;
}

@media(max-width:992px){

    .coupon-table-responsive table{
        min-width:850px;
    }

}

@media(max-width:768px){

    .coupon-table-responsive{
        margin:0 -15px;
        padding:0 15px;
    }

    .coupon-list-card{
        border-radius:14px;
    }

    .coupon-list-card table th,
    .coupon-list-card table td{
        padding:12px 15px;
        white-space:nowrap;
        font-size:14px;
    }

}

/* Dark Mode */

body.dark-mode .coupon-table-responsive::-webkit-scrollbar-thumb{
    background:#475569;
}

body.dark-mode .coupon-max-discount{
    color:#94a3b8 !important;
}
    </style>

    <div class="coupon-list-page">
        <div class="coupon-list-header">
            <h1 class="coupon-list-title">Coupon Codes</h1>
            <a href="{{ route('admin.coupons.create') }}" class="coupon-create-btn">
                <i class="fas fa-plus"></i> Create Coupon
            </a>
        </div>

       <div class="coupon-list-card">

    <div class="coupon-table-responsive">

        <table>

            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Discount</th>
                    <th>Usage</th>
                    <th>Valid Until</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($coupons as $coupon)

                    <tr>

                        <td class="coupon-code">
                            {{ $coupon->code }}
                        </td>

                        <td class="coupon-name">
                            {{ $coupon->name }}
                        </td>

                        <td>

                            @if($coupon->discount_type === 'percentage')

                                {{ $coupon->discount_value }}%

                            @else

                                ₹{{ number_format($coupon->discount_value,2) }}

                            @endif

                            @if($coupon->max_discount)

                                <span class="coupon-max-discount">
                                    (Max ₹{{ $coupon->max_discount }})
                                </span>

                            @endif

                        </td>

                        <td>

                            {{ $coupon->used_count }}
                            /
                            {{ $coupon->usage_limit ?? '∞' }}

                        </td>

                        <td>

                            {{ $coupon->valid_until->format('M d, Y') }}

                        </td>

                        <td>

                            <span class="coupon-badge {{ $coupon->is_active ? 'active' : 'disabled' }}">

                                {{ $coupon->is_active ? 'Active' : 'Disabled' }}

                            </span>

                        </td>

                        <td style="text-align:right;">

                            <form
                                action="{{ route('admin.coupons.destroy',$coupon) }}"
                                method="POST"
                                onsubmit="return confirm('Delete this coupon?')">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="delete-btn">

                                    <i class="fas fa-trash"></i>

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="empty-state">

                            No coupons created yet.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
    </div>
@endsection