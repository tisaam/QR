@extends('layouts.admin')

@section('title', 'Manage Businesses')

@section('content')
<style>
    .business-page { font-family: Arial, sans-serif; color: #1f2937; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; gap: 1rem; }
    .page-title { font-size: 1.5rem; font-weight: 700; color: #1f2937; margin: 0; }
    .search-box { position: relative; }
    .search-box input { width: 16rem; padding: 0.65rem 0.9rem 0.65rem 2.4rem; border: 1px solid #d1d5db; border-radius: 0.6rem; font-size: 0.95rem; }
    .search-box i { position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: #9ca3af; }
    .table-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.8rem; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
    .table-card table { width: 100%; border-collapse: collapse; font-size: 0.95rem; color: #4b5563; }
    .table-card th, .table-card td { padding: 0.85rem 1rem; border-bottom: 1px solid #e5e7eb; text-align: left; }
    .table-card thead { background: #f9fafb; text-transform: uppercase; font-size: 0.75rem; color: #374151; }
    .table-card tbody tr:hover { background: #f9fafb; }
    .business-cell { display: flex; align-items: center; gap: 0.75rem; }
    .business-cell img, .business-cell .icon-box { width: 2.5rem; height: 2.5rem; border-radius: 0.6rem; object-fit: cover; border: 1px solid #e5e7eb; }
    .business-cell .icon-box { display: flex; align-items: center; justify-content: center; background: #eef2ff; color: #4f46e5; }
    .owner-cell { display: flex; align-items: center; gap: 0.6rem; }
    .avatar { width: 1.8rem; height: 1.8rem; border-radius: 999px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; color: #4b5563; }
    .status-pill { display: inline-block; padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
    .status-active { background: #dcfce7; color: #166534; }
    .status-inactive { background: #f3f4f6; color: #4b5563; }
    .view-link { color: #4f46e5; text-decoration: none; font-weight: 600; }
    .view-link:hover { color: #3730a3; }
    .pagination-wrap { padding: 1rem 1.2rem; background: #f9fafb; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
    .pagination-wrap p { margin: 0; color: #6b7280; font-size: 0.95rem; }
    @media (max-width: 768px) { .page-header { flex-direction: column; align-items: flex-start; } .search-box input { width: 100%; } }
</style>

<div class="business-page">
    <div class="page-header">
        <h1 class="page-title">Businesses</h1>
        <div class="search-box">
            <input type="text" id="search" placeholder="Search businesses...">
            <i class="fas fa-search"></i>
        </div>
    </div>

    <div class="table-card">
        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Business</th>
                        <th>Owner</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Subscription</th>
                        <th>Joined</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="businesses-table">
                    @forelse($businesses as $business)
                        <tr>
                            <td>
                                <div class="business-cell">
                                    @if($business->logo)
                                        <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}">
                                    @else
                                        <div class="icon-box">
                                            <i class="fas fa-building"></i>
                                        </div>
                                    @endif
                                    <span style="font-weight:600; color:#111827;">{{ $business->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="owner-cell">
                                    <div class="avatar">{{ strtoupper(substr($business->user->name ?? 'U', 0, 1)) }}</div>
                                    <span>{{ $business->user->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td>{{ $business->email ?? 'N/A' }}</td>
                            <td>
                                @if($business->status === 'active')
                                    <span class="status-pill status-active">Active</span>
                                @else
                                    <span class="status-pill status-inactive">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if($business->subscription && $business->subscription->status === 'active')
                                    <span style="color:#4f46e5; font-weight:600;">{{ $business->subscription->plan->name ?? 'Active' }}</span>
                                @else
                                    <span style="color:#9ca3af;">No Plan</span>
                                @endif
                            </td>
                            <td style="color:#9ca3af;">{{ $business->created_at->format('M d, Y') }}</td>
                            <td style="text-align:center;">
                                <a href="{{ route('admin.businesses.show', $business) }}" class="view-link">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding:2rem 1rem; text-align:center; color:#9ca3af;">
                                <i class="fas fa-building" style="font-size:1.8rem; display:block; margin-bottom:0.5rem;"></i>
                                No businesses found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            <p>Showing {{ $businesses->count() }} of {{ $businesses->total() }} businesses</p>
            {{ $businesses->links() }}
        </div>
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