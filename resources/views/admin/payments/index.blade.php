@extends('layouts.admin')

@section('title', 'Payments')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Payments</h1>
    <div class="flex items-center space-x-3">
        <select id="status-filter" class="px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">All Status</option>
            <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
        </select>
        <div class="relative">
            <input type="text" id="search" placeholder="Search..." 
                   class="pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 w-64">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
        <p class="text-sm text-green-600">Successful</p>
        <p class="text-2xl font-bold text-green-700">₹{{ number_format($payments->where('status', 'success')->sum('amount'), 0) }}</p>
    </div>
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
        <p class="text-sm text-yellow-600">Pending</p>
        <p class="text-2xl font-bold text-yellow-700">₹{{ number_format($payments->where('status', 'pending')->sum('amount'), 0) }}</p>
    </div>
    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
        <p class="text-sm text-red-600">Failed</p>
        <p class="text-2xl font-bold text-red-700">₹{{ number_format($payments->where('status', 'failed')->sum('amount'), 0) }}</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-4">Transaction ID</th>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Plan</th>
                    <th class="px-6 py-4">Amount</th>
                    <th class="px-6 py-4">Gateway</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Action</th>
                </tr>
            </thead>
            <tbody id="payments-table">
                @forelse($payments as $payment)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-xs text-gray-500">
                            {{ Str::limit($payment->transaction_id ?? 'N/A', 15) }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <span class="text-indigo-600 text-sm font-semibold">
                                        {{ strtoupper(substr($payment->user->name ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                                <span class="font-medium text-gray-900">{{ $payment->user->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            {{ $payment->plan->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900">₹{{ number_format($payment->amount) }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs uppercase">{{ $payment->gateway ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'success' => 'bg-green-100 text-green-700',
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'failed' => 'bg-red-100 text-red-700',
                                ];
                                $color = $statusColors[$payment->status] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="px-2.5 py-1 text-xs rounded-full {{ $color }} font-medium">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-400">{{ $payment->created_at->format('M d, Y h:i A') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.payments.show', $payment) }}" 
                               class="text-indigo-600 hover:text-indigo-800 text-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-credit-card text-4xl mb-3 block"></i>
                            No payments found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 bg-gray-50 border-t flex items-center justify-between">
        <p class="text-sm text-gray-500">Showing {{ $payments->count() }} of {{ $payments->total() }} payments</p>
        {{ $payments->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('status-filter').addEventListener('change', function(e) {
    window.location.href = `{{ route('admin.payments.index') }}?status=` + e.target.value;
});

document.getElementById('search').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#payments-table tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(search) ? '' : 'none';
    });
});
</script>
@endpush