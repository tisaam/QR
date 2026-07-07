@extends('layouts.admin')

@section('title', 'Manage Coupons')

@section('content')
<style>
    .coupon-list-page { font-family: Arial, sans-serif; color: #1f2937; }
    .coupon-list-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; gap: 1rem; }
    .coupon-list-title { font-size: 1.5rem; font-weight: 700; color: #111827; margin: 0; }
    .coupon-create-btn { display: inline-block; background: #4f46e5; color: #fff; padding: 0.7rem 1rem; border-radius: 0.6rem; text-decoration: none; font-weight: 600; }
    .coupon-create-btn:hover { background: #4338ca; }
    .coupon-list-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.8rem; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
    .coupon-list-card table { width: 100%; border-collapse: collapse; font-size: 0.95rem; color: #4b5563; }
    .coupon-list-card th, .coupon-list-card td { padding: 0.8rem 0.9rem; border-bottom: 1px solid #e5e7eb; text-align: left; }
    .coupon-list-card thead { background: #f9fafb; text-transform: uppercase; font-size: 0.72rem; color: #374151; }
    .coupon-code { font-family: monospace; font-weight: 700; color: #4f46e5; }
    .coupon-name { font-weight: 600; color: #111827; }
    .coupon-badge { display: inline-block; padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
    .coupon-badge.active { background: #dcfce7; color: #166534; }
    .coupon-badge.disabled { background: #f3f4f6; color: #4b5563; }
    .delete-btn { color: #dc2626; background: none; border: none; cursor: pointer; }
    .empty-state { text-align: center; color: #9ca3af; padding: 1.5rem 0; }
    @media (max-width: 768px) { .coupon-list-header { flex-direction: column; align-items: flex-start; } }
</style>

<div class="coupon-list-page">
    <div class="coupon-list-header">
        <h1 class="coupon-list-title">Coupon Codes</h1>
        <a href="{{ route('admin.coupons.create') }}" class="coupon-create-btn">
            <i class="fas fa-plus"></i> Create Coupon
        </a>
    </div>

    <div class="coupon-list-card">
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
                        <td class="coupon-code">{{ $coupon->code }}</td>
                        <td class="coupon-name">{{ $coupon->name }}</td>
                        <td>
                            @if($coupon->discount_type === 'percentage')
                                {{ $coupon->discount_value }}%
                            @else
                                ₹{{ number_format($coupon->discount_value, 2) }}
                            @endif
                            @if($coupon->max_discount)
                                <span style="color:#9ca3af; font-size:0.8rem;">(Max ₹{{ $coupon->max_discount }})</span>
                            @endif
                        </td>
                        <td>{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}</td>
                        <td>{{ $coupon->valid_until->format('M d, Y') }}</td>
                        <td>
                            <span class="coupon-badge {{ $coupon->is_active ? 'active' : 'disabled' }}">
                                {{ $coupon->is_active ? 'Active' : 'Disabled' }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Delete this coupon?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="delete-btn"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">No coupons created yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection