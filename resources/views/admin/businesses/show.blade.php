@extends('layouts.admin')

@section('title', 'Business Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.businesses.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back to Businesses
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Business Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center space-x-5 mb-6 pb-6 border-b">
                @if($business->logo)
                    <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}" class="w-20 h-20 rounded-xl object-cover border shadow-sm">
                @else
                    <div class="w-20 h-20 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-building text-indigo-600 text-3xl"></i>
                    </div>
                @endif
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $business->name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $business->slug ?? 'N/A' }}</p>
                    @if($business->status === 'active')
                        <span class="inline-block mt-2 px-3 py-1 text-xs rounded-full bg-green-100 text-green-700 font-medium">Active</span>
                    @else
                        <span class="inline-block mt-2 px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-600 font-medium">Inactive</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Contact Email</p>
                    <p class="mt-1 text-gray-800">{{ $business->email ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Phone Number</p>
                    <p class="mt-1 text-gray-800">{{ $business->phone ?? 'N/A' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">Address</p>
                    <p class="mt-1 text-gray-800">{{ $business->address ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Created At</p>
                    <p class="mt-1 text-gray-800">{{ $business->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Last Updated</p>
                    <p class="mt-1 text-gray-800">{{ $business->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>

        <!-- Subscription Details Section -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-crown text-yellow-500 mr-2"></i>Subscription History</h3>
            @if($business->subscriptions && $business->subscriptions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 rounded-tl-lg">Plan</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Start Date</th>
                                <th class="px-4 py-3 rounded-tr-lg">End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($business->subscriptions as $sub)
                                <tr class="border-b">
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $sub->plan->name ?? 'Deleted Plan' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $sub->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }} font-medium">
                                            {{ ucfirst($sub->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-400">{{ $sub->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-gray-400">{{ $sub->ends_at ? $sub->ends_at->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-400 text-sm text-center py-6">No subscriptions found for this business.</p>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Owner Info -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Owner Details</h3>
            <div class="text-center mb-4">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-indigo-600 text-2xl font-bold">
                        {{ strtoupper(substr($business->user->name ?? 'U', 0, 1)) }}
                    </span>
                </div>
                <p class="font-semibold text-gray-800">{{ $business->user->name ?? 'Unknown' }}</p>
                <p class="text-sm text-gray-500">{{ $business->user->email ?? 'N/A' }}</p>
            </div>
            <div class="border-t pt-4 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Joined</span>
                    <span class="text-gray-800">{{ $business->user->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Role</span>
                    <span class="text-gray-800">
                        @if($business->user->is_admin)
                            <span class="text-purple-600 font-medium">Admin</span>
                        @else
                            User
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Current Plan Info -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Current Plan</h3>
            @if($business->subscription && $business->subscription->status === 'active')
                <div class="text-center py-4">
                    <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-crown text-yellow-600 text-xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">{{ $business->subscription->plan->name ?? 'Plan' }}</h4>
                    <p class="text-2xl font-bold text-indigo-600 mt-2">
                        ₹{{ number_format($business->subscription->plan->price ?? 0) }}
                        <span class="text-sm font-normal text-gray-400">/{{ $business->subscription->plan->billing_cycle ?? 'month' }}</span>
                    </p>
                </div>
                <div class="border-t pt-4 mt-4 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Status</span>
                        <span class="text-green-600 font-medium">Active</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Expires On</span>
                        <span class="text-gray-800">{{ $business->subscription->ends_at ? $business->subscription->ends_at->format('M d, Y') : 'N/A' }}</span>
                    </div>
                </div>
            @else
                <div class="text-center py-6">
                    <i class="fas fa-exclamation-circle text-gray-300 text-4xl mb-3 block"></i>
                    <p class="text-gray-500 text-sm">No active subscription</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection