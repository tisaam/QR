@extends('layouts.admin')

@section('title', 'Payment Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.payments.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back to Payments
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-6">Payment Information</h2>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Transaction ID</p>
                    <p class="font-mono text-sm mt-1">{{ $payment->transaction_id ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Amount</p>
                    <p class="text-xl font-bold text-gray-800 mt-1">₹{{ number_format($payment->amount) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Gateway</p>
                    <p class="mt-1">{{ strtoupper($payment->gateway ?? 'N/A') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="mt-1">
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            {{ $payment->status === 'success' ? 'bg-green-100 text-green-700' : 
                               ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Created At</p>
                    <p class="mt-1">{{ $payment->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Currency</p>
                    <p class="mt-1">INR</p>
                </div>
            </div>

            @if($payment->gateway_response)
                <div class="mt-6">
                    <p class="text-sm text-gray-500 mb-2">Gateway Response</p>
                    <pre class="bg-gray-100 p-4 rounded-lg text-xs overflow-x-auto">{{ json_encode($payment->gateway_response, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif
        </div>
    </div>

    <div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">User Details</h3>
            <div class="text-center mb-4">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-indigo-600 text-2xl font-bold">
                        {{ strtoupper(substr($payment->user->name ?? 'U', 0, 1)) }}
                    </span>
                </div>
                <p class="font-semibold text-gray-800">{{ $payment->user->name ?? 'Unknown' }}</p>
                <p class="text-sm text-gray-500">{{ $payment->user->email ?? 'N/A' }}</p>
            </div>
            
            @if($payment->plan)
                <div class="border-t pt-4 mt-4">
                    <p class="text-sm text-gray-500">Plan</p>
                    <p class="font-semibold text-indigo-600 mt-1">{{ $payment->plan->name }}</p>
                    <p class="text-sm text-gray-400">{{ $payment->plan->billing_cycle ?? '' }} billing</p>
                </div>
            @endif

            @if($payment->user && $payment->user->business)
                <div class="border-t pt-4 mt-4">
                    <p class="text-sm text-gray-500">Business</p>
                    <a href="{{ route('admin.businesses.show', $payment->user->business) }}" 
                       class="font-semibold text-indigo-600 hover:underline mt-1 block">
                        {{ $payment->user->business->name }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection