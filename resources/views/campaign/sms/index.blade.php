@extends('layouts.app')

@section('title', 'SMS Campaign')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border p-6 h-fit">
        <h2 class="font-bold text-gray-800 mb-4"><i class="fas fa-sms text-blue-500 mr-2"></i>Send SMS</h2>
        <form action="{{ route('sms.send') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer Phone *</label>
                <input type="text" name="customer_phone" required class="w-full border rounded-lg px-4 py-2" placeholder="9876543210">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                <input type="text" name="customer_name" class="w-full border rounded-lg px-4 py-2" placeholder="Rahul">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 font-semibold">
                <i class="fas fa-paper-plane mr-2"></i>Send SMS
            </button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border p-6">
        <h3 class="font-bold text-gray-800 mb-4">SMS History</h3>
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-4 py-3">To</th>
                    <th class="px-4 py-3">Message</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Sent At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                    <tr class="border-b">
                        <td class="px-4 py-3 font-medium">{{ $msg->customer_phone }}</td>
                        <td class="px-4 py-3 truncate max-w-xs">{{ $msg->message_content }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full {{ $msg->status === 'sent' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($msg->status) }}</span></td>
                        <td class="px-4 py-3">{{ $msg->sent_at ? $msg->sent_at->diffForHumans() : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No SMS sent yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection