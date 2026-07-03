@extends('layouts.app')

@section('title', 'Create QR Code')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Generate QR Code</h1>

    <!-- Single QR Form -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <h2 class="font-semibold text-lg mb-4">Single QR Code</h2>
        <form action="{{ route('qr-codes.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">QR Name *</label>
                    <input type="text" name="name" required class="w-full border rounded-lg px-4 py-2 focus:ring-blue-500" placeholder="e.g., Table 5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                    <select name="type" required class="w-full border rounded-lg px-4 py-2 bg-white focus:ring-blue-500">
                        <option value="table">Restaurant Table</option>
                        <option value="room">Hotel Room</option>
                        <option value="counter">Billing Counter</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Identifier (Optional)</label>
                    <input type="text" name="identifier" class="w-full border rounded-lg px-4 py-2 focus:ring-blue-500" placeholder="e.g., T-05">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Branch (Optional)</label>
                    <select name="branch_id" class="w-full border rounded-lg px-4 py-2 bg-white focus:ring-blue-500">
                        <option value="">Main Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-qrcode mr-2"></i>Generate QR Code
            </button>
        </form>
    </div>

    <!-- Bulk QR Form -->
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h2 class="font-semibold text-lg mb-4 text-purple-700"><i class="fas fa-layer-group mr-2"></i>Bulk Generate</h2>
        <form action="{{ route('qr-codes.bulk-generate') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prefix</label>
                    <input type="text" name="prefix" value="Table" required class="w-full border rounded-lg px-4 py-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Number</label>
                    <input type="number" name="start_number" value="1" min="1" required class="w-full border rounded-lg px-4 py-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Number</label>
                    <input type="number" name="end_number" value="10" min="1" required class="w-full border rounded-lg px-4 py-2 focus:ring-purple-500">
                </div>
                <div>
                    <button type="submit" class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                        Generate Bulk
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection