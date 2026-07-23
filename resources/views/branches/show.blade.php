@extends('layouts.app')

@section('title', $branch->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('branches.index') }}" class="text-gray-500 hover:text-gray-700 mr-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-xl font-bold text-gray-800">Branch Details</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 bg-gray-50 border-b flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-code-branch text-blue-600"></i>
                </div>
                <div>
                    <h2 class="font-bold text-gray-800">{{ $branch->name }}</h2>
                    @if($branch->is_main)
                        <span class="text-xs font-medium text-green-600 bg-green-100 px-2 py-0.5 rounded-full">Main Branch</span>
                    @endif
                </div>
            </div>
            <a href="{{ route('branches.edit', $branch) }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
        </div>

        <!-- Details -->
        <div class="p-6 space-y-5">
            <div class="flex items-start">
                <div class="w-8 text-gray-400 mt-0.5">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Address</p>
                    <p class="text-gray-800">{{ $branch->address }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="flex items-start">
                    <div class="w-8 text-gray-400 mt-0.5">
                        <i class="fas fa-city"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">City</p>
                        <p class="text-gray-800">{{ $branch->city }}</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="w-8 text-gray-400 mt-0.5">
                        <i class="fas fa-map"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">State</p>
                        <p class="text-gray-800">{{ $branch->state }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-start">
                <div class="w-8 text-gray-400 mt-0.5">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Phone</p>
                    <p class="text-gray-800">{{ $branch->phone }}</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="w-8 text-gray-400 mt-0.5">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Created</p>
                    <p class="text-gray-800">{{ $branch->created_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection