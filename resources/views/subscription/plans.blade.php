@extends('layouts.app')

@section('title', 'Pricing Plans')

@section('content')
<div class="text-center mb-10">
    <h1 class="text-3xl font-bold text-gray-800">Choose Your Plan</h1>
    <p class="text-gray-500 mt-2">Scale your review generation as your business grows</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach($plans as $plan)
        <div class="bg-white rounded-2xl shadow-sm border p-6 flex flex-col relative 
            {{ $plan->slug === 'premium' ? 'border-blue-500 ring-2 ring-blue-100' : '' }}">
            
            @if($plan->slug === 'premium')
                <span class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white text-xs px-3 py-1 rounded-full">Most Popular</span>
            @endif

            <h3 class="text-xl font-bold text-gray-800">{{ $plan->name }}</h3>
            <p class="text-gray-500 text-sm mt-1 mb-4">{{ $plan->description }}</p>
            
            <div class="mb-6">
                <span class="text-4xl font-bold text-gray-900">
                    {{ $plan->price > 0 ? '₹' . number_format($plan->price) : 'Free' }}
                </span>
                @if($plan->price > 0)
                    <span class="text-gray-500 text-sm">/month</span>
                @endif
            </div>

            <ul class="text-sm text-gray-600 space-y-2 mb-8 flex-1">
                @foreach($plan->features as $feature)
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 mr-2"></i>
                        {{ $feature }}
                    </li>
                @endforeach
            </ul>

            <div>
                @if(isset($currentPlan) && $currentPlan->id === $plan->id)
                    <button class="w-full py-2 bg-gray-100 text-gray-500 rounded-lg cursor-not-allowed font-semibold" disabled>
                        Current Plan
                    </button>
                @elseif($plan->slug === 'enterprise')
                    <a href="#" class="block w-full py-2 bg-gray-900 text-white text-center rounded-lg hover:bg-gray-800 font-semibold">
                        Contact Sales
                    </a>
                @else
                    <form action="{{ route('subscription.subscribe') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <button type="submit" class="w-full py-2 {{ $plan->slug === 'premium' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-800 hover:bg-gray-900' }} text-white rounded-lg font-semibold transition">
                            {{ $plan->price > 0 ? 'Upgrade Now' : 'Activate Free' }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection