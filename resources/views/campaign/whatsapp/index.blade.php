@extends('layouts.app')

@section('title', 'WhatsApp Campaign')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Send Form -->
    <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border p-6 h-fit">
        <h2 class="font-bold text-gray-800 mb-4"><i class="fab fa-whatsapp text-green-500 mr-2"></i>Send Request</h2>
        <form action="{{ route('whatsapp.send') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer Phone *</label>
                <input type="text" name="customer_phone" required class="w-full border rounded-lg px-4 py-2 focus:ring-green-500" placeholder="9876543210">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                <input type="text" name="customer_name" class="w-full border rounded-lg px-4 py-2 focus:ring-green-500" placeholder="Rahul">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Specific QR Code (Optional)</label>
                <select name="qr_slug" class="w-full border rounded-lg px-4 py-2 bg-white focus:ring-green-500">
                    <option value="">Use Default Business Link</option>
                    @foreach(Auth::user()->business->qrCodes as $qr)
                        <option value="{{ $qr->slug }}">{{ $qr->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 font-semibold">
                <i class="fas fa-paper-plane mr-2"></i>Send WhatsApp
            </button>
        </form>
    </div>

    <!-- Message History -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border p-6">
        <h3 class="font-bold text-gray-800 mb-4">Message History</h3>
        <div class="space-y-3">
            @forelse($messages as $msg)
                <div class="flex items-start p-3 border rounded-lg">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600 mr-4">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">{{ $msg->customer_phone }} <span class="text-gray-400 font-normal">({{ $msg->customer_name ?? 'Unknown' }})</span></p>
                        <p class="text-xs text-gray-500 mt-1 truncate">{{ Str::limit($msg->message_content, 80) }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full {{ $msg->status === 'sent' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($msg->status) }}
                    </span>
                </div>
            @empty
                <p class="text-gray-400 text-sm text-center py-8">No messages sent yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection