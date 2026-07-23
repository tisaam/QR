@extends('layouts.app')

@section('title', 'Branches')

@push('styles')
<style>
    .branches-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .page-title i { color: var(--accent-glow); }

    /* --- Modern Glowing Button --- */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 0.70rem;
        background: var(--accent-glow);
        color: #ffffff;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 0.75rem;
        border: none;
        cursor: pointer;
        box-shadow: 0 0 20px var(--accent-glow-soft);
        transition: all 0.3s ease;
        text-decoration: none;
        margin-left: 5px;
    }
    .btn-primary:hover {
        background: #0891b2;
        box-shadow: 0 0 30px rgba(6, 182, 212, 0.4);
        transform: translateY(-2px);
    }

    /* --- Glass Card --- */
    .glass-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-glass);
        border-radius: 1.25rem;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 0 20px 30px rgba(15, 23, 42, 0.1);
        overflow: hidden;
    }

    /* --- Modern Table --- */
    .modern-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .modern-table thead th {
        padding: 1rem 1.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-secondary);
        background: rgba(255, 255, 255, 0.02);
        border-bottom: 1px solid var(--border-glass);
    }
    .modern-table tbody tr {
        border-bottom: 1px solid var(--border-glass);
        transition: all 0.2s ease;
    }
    .modern-table tbody tr:last-child {
        border-bottom: none;
    }
    .modern-table tbody tr:hover {
        background: var(--accent-glow-soft);
    }
    .modern-table tbody td {
        padding: 1.25rem 1.5rem;
        font-size: 0.9375rem;
    }
    
    .branch-name {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }
    .branch-address {
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }
    .text-secondary-custom {
        color: var(--text-secondary);
    }

    /* --- Status Badges --- */
    .badge-main {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 9999px;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }
    .badge-action {
        display: inline-flex;
        align-items: center;
        background: transparent;
        border: 1px solid var(--border-glass);
        color: var(--text-secondary);
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .badge-action:hover {
        border-color: var(--accent-glow);
        color: var(--accent-glow);
        background: var(--accent-glow-soft);
    }

    /* --- Action Links --- */
    .action-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
    }
    .action-edit {
        color: var(--accent-glow);
    }
    .action-edit:hover {
        background: var(--accent-glow-soft);
    }
    .action-delete {
        color: #f87171;
    }
    .action-delete:hover {
        background: rgba(239, 68, 68, 0.1);
    }

    /* --- Empty State --- */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }
    .empty-icon {
        width: 5rem;
        height: 5rem;
        border-radius: 9999px;
        background: var(--accent-glow-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: var(--accent-glow);
        font-size: 2rem;
    }
    .empty-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }
    .empty-text {
        font-size: 0.9375rem;
        color: var(--text-secondary);
        margin: 0 0 1.5rem 0;
    }

    /* --- Pagination Override --- */
    .pagination-wrapper {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }
    .pagination-wrapper a, .pagination-wrapper span {
        color: var(--text-secondary) !important;
        transition: color 0.2s;
    }
    .pagination-wrapper a:hover {
        color: var(--accent-glow) !important;
    }
</style>
@endpush

@section('content')
<div class="branches-header">
    <h1 class="page-title"><i class="fas fa-code-branch"></i> Branches</h1>
    <a href="{{ route('branches.create') }}" class="btn-primary">
        <i class="fas fa-plus"></i> Add Branch
    </a>
</div>

@if($branches->count() > 0)
    <div class="glass-card">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Branch Name</th>
                    <th>City / State</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($branches as $branch)
                    <tr>
                        <td>
                            <div class="branch-name">{{ $branch->name }}</div>
                            <div class="branch-address">{{ $branch->address }}</div>
                        </td>
                        <td class="text-secondary-custom">{{ $branch->city }}, {{ $branch->state }}</td>
                        <td class="text-secondary-custom">{{ $branch->phone }}</td>
                        <td>
                            @if($branch->is_main)
                                <span class="badge-main">
                                    <i class="fas fa-check-circle" style="font-size: 10px;"></i> Main Branch
                                </span>
                            @else
                                <button onclick="setMain({{ $branch->id }})" class="badge-action">
                                    Set as Main
                                </button>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('branches.edit', $branch) }}" class="action-link action-edit">
                                <i class="fas fa-pen-to-square"></i> Edit
                            </a>
                            <button onclick="deleteBranch({{ $branch->id }})" class="action-link action-delete" style="margin-left: 0.5rem; border: none; background: none; cursor: pointer; font: inherit;">
                                <i class="fas fa-trash-can"></i> Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $branches->links() }}
    </div>
@else
    <div class="glass-card">
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-code-branch"></i></div>
            <h3 class="empty-title">No Branches Yet</h3>
            <p class="empty-text">Add your first branch to get started.</p>
            <a href="{{ route('branches.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i> Add Branch
            </a>
        </div>
    </div>
@endif

<form id="delete-form" method="POST" action="" class="hidden">
    @csrf
    @method('DELETE')
</form>

<form id="main-form" method="POST" action="" class="hidden">
    @csrf
    @method('PATCH')
</form>
@endsection

@push('scripts')
<script>
    function deleteBranch(id) {
        if (confirm('Are you sure you want to delete this branch?')) {
            const form = document.getElementById('delete-form');
            form.action = '/branches/' + id;
            form.submit();
        }
    }

    function setMain(id) {
        if (confirm('Set this as the main branch?')) {
            const form = document.getElementById('main-form');
            form.action = '/branches/' + id + '/set-main';
            form.submit();
        }
    }
</script>
@endpush