@extends('layouts.admin')

@section('title', 'Manage Plans')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Plans</h1>
    <a href="{{ route('admin.plans.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
        <i class="fas fa-plus mr-2"></i>Add Plan
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($plans as $plan)
        <div class="bg-white rounded-xl shadow-sm border p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">{{ $plan->name }}</h3>
                    @if($plan->is_active)
                        <span class="px-2.5 py-1 text-xs rounded-full bg-green-100 text-green-700 font-medium">Active</span>
                    @else
                        <span class="px-2.5 py-1 text-xs rounded-full bg-gray-100 text-gray-600 font-medium">Inactive</span>
                    @endif
                </div>
                
                <div class="mb-4">
                    <span class="text-3xl font-bold text-gray-900">₹{{ number_format($plan->price, 0) }}</span>
                    <span class="text-gray-500 text-sm">/ {{ ucfirst($plan->billing_cycle) }}</span>
                </div>

                @if($plan->annual_price)
                    <p class="text-sm text-green-600 mb-4">Annual: ₹{{ number_format($plan->annual_price, 0) }}</p>
                @endif

                <div class="border-t pt-4 mb-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase mb-2">Limits</p>
                    <div class="grid grid-cols-2 gap-2 text-sm text-gray-600">
                        <div><i class="fas fa-qrcode text-indigo-400 w-4"></i> {{ $plan->limits['qr_codes'] ?? 0 == -1 ? 'Unlimited' : ($plan->limits['qr_codes'] ?? 0) }} QR Codes</div>
                        <div><i class="fas fa-star text-yellow-400 w-4"></i> {{ $plan->limits['reviews_per_month'] ?? 0 == -1 ? 'Unlimited' : ($plan->limits['reviews_per_month'] ?? 0) }} Reviews/mo</div>
                        <div><i class="fas fa-code-branch text-blue-400 w-4"></i> {{ $plan->limits['branches'] ?? 0 == -1 ? 'Unlimited' : ($plan->limits['branches'] ?? 0) }} Branches</div>
                        <div><i class="fas fa-users text-purple-400 w-4"></i> {{ $plan->limits['employees'] ?? 0 == -1 ? 'Unlimited' : ($plan->limits['employees'] ?? 0) }} Employees</div>
                    </div>
                </div>

                @if($plan->features)
                    <div class="border-t pt-4">
                        <p class="text-xs font-semibold text-gray-400 uppercase mb-2">Features</p>
                        <ul class="space-y-1">
                            @foreach(array_slice($plan->features, 0, 3) as $feature)
                                <li class="text-sm text-gray-600 flex items-center"><i class="fas fa-check text-green-500 mr-2 text-xs"></i>{{ $feature }}</li>
                            @endforeach
                            @if(count($plan->features) > 3)
                                <li class="text-xs text-indigo-600">+ {{ count($plan->features) - 3 }} more</li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-between border-t pt-4 mt-4">
                <span class="text-xs text-gray-400">Subs: {{ $plan->subscriptions_count ?? $plan->subscriptions()->count() }}</span>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.plans.edit', $plan) }}" class="px-3 py-1.5 text-xs rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Delete this plan?')">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1.5 text-xs rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-16 text-gray-400">
            <i class="fas fa-tags text-5xl mb-4 block"></i>
            <p class="text-lg">No plans created yet.</p>
        </div>
    @endforelse
</div>
@endsection