@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-500 text-sm">Welcome back, {{ Auth::user()->name }}!</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total QR Scans</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalScans }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-qrcode text-blue-600 text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-green-500 mt-4"><i class="fas fa-arrow-up"></i> {{ $monthlyGrowth }}% this month</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Reviews</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalReviews }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-star text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Avg Rating</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($avgRating, 1) }} <span class="text-lg text-gray-400">/ 5</span></p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Conversion Rate</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $conversionRate }}%</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-bullseye text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Reviews -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border p-6">
        <h3 class="font-bold text-gray-800 mb-4">Recent Reviews</h3>
        <div class="space-y-4">
            @forelse($recentReviews as $review)
                <div class="flex items-start border-b pb-4">
                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold mr-4">
                        {{ strtoupper(substr($review->customer_name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">{{ $review->customer_name ?? 'Anonymous' }}</p>
                        <div class="flex text-yellow-400 text-xs my-1">
                            @for($i=1; $i<=5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                        <p class="text-sm text-gray-600">{{ Str::limit($review->review_text, 100) }}</p>
                    </div>
                    <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <p class="text-gray-400 text-sm text-center py-4">No reviews yet. Create a QR code to get started!</p>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h3 class="font-bold text-gray-800 mb-4">Quick Actions</h3>
        <div class="space-y-3">
            <a href="{{ route('qr-codes.create') }}" class="flex items-center p-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                <i class="fas fa-plus-circle mr-3"></i> Create New QR Code
            </a>
            <a href="{{ route('qr-codes.index') }}" class="flex items-center p-3 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                <i class="fas fa-download mr-3"></i> Download QR Codes
            </a>
            <a href="{{ route('plans.index') }}" class="flex items-center p-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition">
                <i class="fas fa-crown mr-3"></i> Upgrade Plan
            </a>
            <a href="{{ route('whatsapp.index') }}" class="flex items-center p-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition">
                <i class="fab fa-whatsapp mr-3"></i> Send WhatsApp Campaign
            </a>
        </div>
    </div>
</div>
@endsection