@extends('layouts.admin')

@section('title', 'Plan Details - ' . $plan->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.plans.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back to Plans
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Header Card -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <h2 class="text-2xl font-bold text-gray-800">{{ $plan->name }}</h2>
                        <span class="px-3 py-1 text-xs rounded-full font-medium {{ $plan->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-400 mb-4">Slug: {{ $plan->slug }}</p>
                    <div class="flex items-baseline space-x-2 mb-3">
                        <span class="text-4xl font-bold text-indigo-600">₹{{ number_format($plan->price) }}</span>
                        <span class="text-gray-500 font-medium">/ {{ ucfirst($plan->billing_cycle) }}</span>
                    </div>
                    @if($plan->annual_price)
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-calendar-alt mr-1"></i> Yearly Price: <span class="font-semibold text-gray-700">₹{{ number_format($plan->annual_price) }}</span>
                        </p>
                    @endif
                    @if($plan->trial_days > 0)
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-clock mr-1"></i> Trial Period: <span class="font-semibold text-gray-700">{{ $plan->trial_days }} Days</span>
                        </p>
                    @endif
                </div>
                <a href="{{ route('admin.plans.edit', $plan) }}" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg text-sm font-medium hover:bg-indigo-100 transition">
                    <i class="fas fa-pen mr-1"></i> Edit Plan
                </a>
            </div>
            @if($plan->description)
                <div class="mt-6 pt-6 border-t">
                    <p class="text-sm text-gray-500 mb-1">Description</p>
                    <p class="text-gray-800">{{ $plan->description }}</p>
                </div>
            @endif
        </div>

        <!-- Features List -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-list-check text-green-500 mr-2"></i>Plan Features
            </h3>
            @if($plan->features && count($plan->features) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($plan->features as $feature)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="text-sm text-gray-700">{{ $feature }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400 text-center py-6">No features listed for this plan.</p>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Stats -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Statistics</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-indigo-50 rounded-lg">
                    <div>
                        <p class="text-xs text-indigo-600">Total Subscribers</p>
                        <p class="text-2xl font-bold text-indigo-700">{{ $plan->subscriptions->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-indigo-600"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div>
                        <p class="text-xs text-green-600">Active Subscribers</p>
                        <p class="text-2xl font-bold text-green-700">{{ $plan->subscriptions->where('status', 'active')->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <div>
                        <p class="text-xs text-yellow-600">Est. Monthly Revenue</p>
                        <p class="text-2xl font-bold text-yellow-700">₹{{ number_format($plan->subscriptions->where('status', 'active')->count() * $plan->price) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-rupee-sign text-yellow-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Limits & Access -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Limits & Access</h3>
            @php $limits = $plan->limits ?? []; @endphp
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">QR Codes</span>
                    <span class="font-medium text-gray-800 {{ ($limits['qr_codes'] ?? -1) == -1 ? 'text-green-600' : '' }}">
                        {{ ($limits['qr_codes'] ?? -1) == -1 ? 'Unlimited' : $limits['qr_codes'] }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Reviews / Month</span>
                    <span class="font-medium text-gray-800 {{ ($limits['reviews_per_month'] ?? -1) == -1 ? 'text-green-600' : '' }}">
                        {{ ($limits['reviews_per_month'] ?? -1) == -1 ? 'Unlimited' : $limits['reviews_per_month'] }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Branches</span>
                    <span class="font-medium text-gray-800">{{ $limits['branches'] ?? 1 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Employees</span>
                    <span class="font-medium text-gray-800">{{ $limits['employees'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">AI Credits</span>
                    <span class="font-medium text-gray-800">{{ $limits['ai_credits'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Analytics Days</span>
                    <span class="font-medium text-gray-800">{{ $limits['analytics_days'] ?? 7 }} Days</span>
                </div>

                <div class="border-t pt-3 mt-3 space-y-2.5">
                    <p class="text-xs font-semibold text-gray-400 uppercase">Integrations</p>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">WhatsApp</span>
                        @if($limits['whatsapp'] ?? false)
                            <span class="text-green-600"><i class="fas fa-check-circle"></i> Enabled</span>
                        @else
                            <span class="text-gray-400"><i class="fas fa-times-circle"></i> Disabled</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">SMS</span>
                        @if($limits['sms'] ?? false)
                            <span class="text-green-600"><i class="fas fa-check-circle"></i> Enabled</span>
                        @else
                            <span class="text-gray-400"><i class="fas fa-times-circle"></i> Disabled</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">NFC Support</span>
                        @if($limits['nfc'] ?? false)
                            <span class="text-green-600"><i class="fas fa-check-circle"></i> Enabled</span>
                        @else
                            <span class="text-gray-400"><i class="fas fa-times-circle"></i> Disabled</span>
                        @endif
                    </div>
                </div>

                <div class="border-t pt-3 mt-3 space-y-2.5">
                    <p class="text-xs font-semibold text-gray-400 uppercase">Branding</p>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">White Label</span>
                        @if($limits['white_label'] ?? false)
                            <span class="text-green-600"><i class="fas fa-check-circle"></i> Yes</span>
                        @else
                            <span class="text-gray-400"><i class="fas fa-times-circle"></i> No</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Custom Branding</span>
                        @if($limits['custom_branding'] ?? false)
                            <span class="text-green-600"><i class="fas fa-check-circle"></i> Yes</span>
                        @else
                            <span class="text-gray-400"><i class="fas fa-times-circle"></i> No</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Remove Branding</span>
                        @if($limits['remove_branding'] ?? false)
                            <span class="text-green-600"><i class="fas fa-check-circle"></i> Yes</span>
                        @else
                            <span class="text-gray-400"><i class="fas fa-times-circle"></i> No</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Meta Info -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Meta Info</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Sort Order</span>
                    <span class="font-medium text-gray-800">{{ $plan->sort_order }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Created</span>
                    <span class="font-medium text-gray-800">{{ $plan->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Last Updated</span>
                    <span class="font-medium text-gray-800">{{ $plan->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white rounded-xl shadow-sm border border-red-200 p-6">
            <h3 class="font-semibold text-red-600 mb-2">Danger Zone</h3>
            <p class="text-xs text-gray-500 mb-4">Once deleted, this action cannot be undone.</p>
            <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Are you absolutely sure you want to delete this plan?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full px-4 py-2.5 bg-red-50 text-red-600 rounded-lg text-sm font-medium hover:bg-red-100 transition border border-red-200">
                    <i class="fas fa-trash mr-2"></i>Delete Plan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection