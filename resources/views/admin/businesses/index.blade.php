@extends('layouts.admin')

@section('title', 'Manage Businesses')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Businesses</h1>
    <div class="relative">
        <input type="text" id="search" placeholder="Search businesses..." 
               class="pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 w-64">
        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-4">Business</th>
                    <th class="px-6 py-4">Owner</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Subscription</th>
                    <th class="px-6 py-4">Joined</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="businesses-table">
                @forelse($businesses as $business)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                @if($business->logo)
                                    <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}" class="w-10 h-10 rounded-lg object-cover border">
                                @else
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-building text-indigo-600"></i>
                                    </div>
                                @endif
                                <span class="font-medium text-gray-900">{{ $business->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-7 h-7 bg-gray-100 rounded-full flex items-center justify-center">
                                    <span class="text-gray-600 text-xs font-semibold">{{ strtoupper(substr($business->user->name ?? 'U', 0, 1)) }}</span>
                                </div>
                                <span>{{ $business->user->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">{{ $business->email ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            @if($business->status === 'active')
                                <span class="px-2.5 py-1 text-xs rounded-full bg-green-100 text-green-700 font-medium">Active</span>
                            @else
                                <span class="px-2.5 py-1 text-xs rounded-full bg-gray-100 text-gray-600 font-medium">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($business->subscription && $business->subscription->status === 'active')
                                <span class="text-indigo-600 font-medium">{{ $business->subscription->plan->name ?? 'Active' }}</span>
                            @else
                                <span class="text-gray-400">No Plan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400">{{ $business->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.businesses.show', $business) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-building text-4xl mb-3 block"></i>
                            No businesses found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 bg-gray-50 border-t flex items-center justify-between">
        <p class="text-sm text-gray-500">Showing {{ $businesses->count() }} of {{ $businesses->total() }} businesses</p>
        {{ $businesses->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('search').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    document.querySelectorAll('#businesses-table tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(search) ? '' : 'none';
    });
});
</script>
@endpush