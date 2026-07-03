@extends('layouts.admin')

@section('title', 'Manage Coupons')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Coupon Codes</h1>
    <a href="{{ route('admin.coupons.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
        <i class="fas fa-plus mr-2"></i>Create Coupon
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th class="px-6 py-3">Code</th>
                <th class="px-6 py-3">Name</th>
                <th class="px-6 py-3">Discount</th>
                <th class="px-6 py-3">Usage</th>
                <th class="px-6 py-3">Valid Until</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($coupons as $coupon)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-mono font-bold text-indigo-600">{{ $coupon->code }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $coupon->name }}</td>
                    <td class="px-6 py-4">
                        @if($coupon->discount_type === 'percentage')
                            {{ $coupon->discount_value }}%
                        @else
                            ₹{{ number_format($coupon->discount_value, 2) }}
                        @endif
                        @if($coupon->max_discount)
                            <span class="text-xs text-gray-400">(Max ₹{{ $coupon->max_discount }})</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        {{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $coupon->valid_until->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $coupon->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $coupon->is_active ? 'Active' : 'Disabled' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this coupon?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                        No coupons created yet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection