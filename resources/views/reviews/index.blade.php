@extends('layouts.app')

@section('title', 'Reviews')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Customer Reviews</h1>
    <a href="{{ route('reviews.export') }}" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition text-sm">
        <i class="fas fa-file-excel mr-2"></i>Export CSV
    </a>
</div>

<div class="space-y-4">
    @forelse($reviews as $review)
        <div class="bg-white rounded-xl shadow-sm border p-5 flex flex-col md:flex-row md:items-center gap-4">
            <!-- Customer Info -->
            <div class="flex items-center gap-3 md:w-1/4">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">
                    {{ strtoupper(substr($review->customer_name ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <p class="font-medium text-gray-800">{{ $review->customer_name ?? 'Anonymous' }}</p>
                    <p class="text-xs text-gray-400">{{ $review->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Review Content -->
            <div class="md:w-2/4">
                <div class="flex text-yellow-400 text-sm mb-1">
                    @for($i=1; $i<=5; $i++)
                        <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-gray-200' }}"></i>
                    @endfor
                </div>
                <p class="text-gray-700 text-sm">{{ $review->review_text }}</p>
            </div>

            <!-- Meta & Status -->
            <div class="md:w-1/4 text-right space-y-2">
                <span class="px-2 py-1 text-xs rounded-full 
                    {{ $review->status === 'published' ? 'bg-green-100 text-green-700' : 
                       ($review->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                    {{ ucfirst($review->status) }}
                </span>
                <p class="text-xs text-gray-400">
                    <i class="fas fa-qrcode mr-1"></i>{{ $review->qrCode->name ?? 'Direct' }}
                </p>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl shadow-sm border p-10 text-center text-gray-400">
            No reviews collected yet.
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $reviews->links() }}
</div>
@endsection