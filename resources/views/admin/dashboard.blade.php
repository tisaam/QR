@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">SaaS Dashboard</h1>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border">
        <p class="text-sm text-gray-500">Total Businesses</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalBusinesses }}</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border">
        <p class="text-sm text-gray-500">Active Subscriptions</p>
        <p class="text-3xl font-bold text-green-600 mt-1">{{ $activeSubscriptions }}</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border">
        <p class="text-sm text-gray-500">Total Revenue</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">₹{{ number_format($totalRevenue, 0) }}</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border">
        <p class="text-sm text-gray-500">This Month</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">₹{{ number_format($monthlyRevenue, 0) }}</p>
    </div>
</div>

<!-- Recent Payments -->
<div class="bg-white rounded-xl shadow-sm border p-6">
    <h3 class="font-bold text-gray-800 mb-4">Recent Payments</h3>
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th class="px-4 py-3">User</th>
                <th class="px-4 py-3">Amount</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentPayments as $payment)
                <tr class="border-b">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $payment->user->name ?? 'Unknown' }}</td>
                    <td class="px-4 py-3 font-semibold">₹{{ number_format($payment->amount) }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">{{ ucfirst($payment->status) }}</span></td>
                    <td class="px-4 py-3">{{ $payment->created_at->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No payments yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection