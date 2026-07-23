@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
<style>
    .users-page { font-family: Arial, sans-serif; color: #1f2937; }
    .users-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; gap: 1rem; }
    .users-title { font-size: 1.5rem; font-weight: 700; color: #1f2937; margin: 0; }
    .users-search { position: relative; }
    .users-search input { width: 16rem; padding: 0.7rem 0.9rem 0.7rem 2.5rem; border: 1px solid #d1d5db; border-radius: 0.7rem; font-size: 0.95rem; background: #fff; box-sizing: border-box; }
    .users-search i { position: absolute; left: 0.9rem; top: 50%; transform: translateY(-50%); color: #9ca3af; }
    .users-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.8rem; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
    .users-table-wrap { overflow-x: auto; }
    .users-table { width: 100%; border-collapse: collapse; font-size: 0.95rem; color: #4b5563; }
    .users-table th, .users-table td { padding: 0.85rem 0.95rem; border-bottom: 1px solid #e5e7eb; text-align: left; }
    .users-table thead { background: #f9fafb; text-transform: uppercase; font-size: 0.72rem; color: #374151; }
    .users-table tbody tr:hover { background: #f9fafb; }
    .user-cell { display: flex; align-items: center; gap: 0.75rem; }
    .user-avatar { width: 2.5rem; height: 2.5rem; border-radius: 999px; display: flex; align-items: center; justify-content: center; background: #e0e7ff; color: #4f46e5; font-size: 0.9rem; font-weight: 700; flex-shrink: 0; }
    .user-name { font-weight: 600; color: #111827; margin: 0; }
    .badge { display: inline-block; padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
    .badge-admin { background: #ede9fe; color: #6d28d9; }
    .badge-user { background: #f3f4f6; color: #4b5563; }
    .business-link { color: #4f46e5; text-decoration: none; font-weight: 600; }
    .business-link:hover { color: #3730a3; text-decoration: underline; }
    .muted-text { color: #9ca3af; }
    .action-group { display: flex; align-items: center; justify-content: center; gap: 0.5rem; flex-wrap: wrap; }
    .action-btn, .danger-btn { border: none; padding: 0.5rem 0.8rem; border-radius: 0.6rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: background 0.2s ease; }
    .action-btn { background: #eef2ff; color: #4338ca; }
    .action-btn:hover { background: #e0e7ff; }
    .danger-btn { background: #fef2f2; color: #dc2626; }
    .danger-btn:hover { background: #fee2e2; }
    .table-footer { display: flex; justify-content: space-between; align-items: center; gap: 1rem; padding: 0.95rem 1rem; background: #f9fafb; border-top: 1px solid #e5e7eb; }
    .table-footer p { margin: 0; color: #6b7280; font-size: 0.95rem; }
    .empty-state { text-align: center; color: #9ca3af; padding: 2rem 0; }
    @media (max-width: 768px) { .users-header { flex-direction: column; align-items: flex-start; } .users-search input { width: 100%; } }
    /* ===========================================================
   USERS PAGE - DARK MODE ONLY
   (Light mode remains EXACTLY the same)
=========================================================== */

body:not(.light-mode) .users-page{
    color: var(--child-text);
}

body:not(.light-mode) .users-title{
    color: var(--child-text);
}

/* Search */

body:not(.light-mode) .users-search input{
    background: var(--child-bg);
    color: var(--child-text);
    border-color: var(--child-border);
}

body:not(.light-mode) .users-search input::placeholder{
    color: var(--child-text-sec);
}

body:not(.light-mode) .users-search i{
    color: var(--child-text-sec);
}

/* Card */

body:not(.light-mode) .users-card{
    background: var(--child-bg);
    border-color: var(--child-border);
    box-shadow: 0 10px 25px rgba(0,0,0,.35);
}

/* Table */

body:not(.light-mode) .users-table{
    color: var(--child-text);
}

body:not(.light-mode) .users-table thead{
    background: rgba(255,255,255,.04);
    color: var(--child-text-sec);
}

body:not(.light-mode) .users-table th,
body:not(.light-mode) .users-table td{
    border-color: var(--child-border);
    color: var(--child-text);
}

body:not(.light-mode) .users-table tbody tr:hover{
    background: rgba(255,255,255,.03);
}

/* User */

body:not(.light-mode) .user-avatar{
    background: rgba(79,70,229,.15);
    color: #a5b4fc;
}

body:not(.light-mode) .user-name{
    color: var(--child-text);
}

body:not(.light-mode) .muted-text{
    color: var(--child-text-sec);
}

/* Badges */

body:not(.light-mode) .badge-user{
    background: rgba(255,255,255,.06);
    color: #cbd5e1;
}

/* Keep Admin badge purple */
body:not(.light-mode) .badge-admin{
    background: rgba(109,40,217,.18);
    color: #c4b5fd;
}

/* Links */

body:not(.light-mode) .business-link{
    color: #818cf8;
}

body:not(.light-mode) .business-link:hover{
    color: #a5b4fc;
}

/* Buttons */

body:not(.light-mode) .action-btn{
    background: rgba(79,70,229,.15);
    color: #a5b4fc;
}

body:not(.light-mode) .action-btn:hover{
    background: rgba(79,70,229,.25);
}

body:not(.light-mode) .danger-btn{
    background: rgba(220,38,38,.15);
    color: #fca5a5;
}

body:not(.light-mode) .danger-btn:hover{
    background: rgba(220,38,38,.25);
}

/* Footer */

body:not(.light-mode) .table-footer{
    background: var(--child-bg);
    border-top-color: var(--child-border);
}

body:not(.light-mode) .table-footer p{
    color: var(--child-text-sec);
}

/* Empty */

body:not(.light-mode) .empty-state{
    color: var(--child-text-sec);
}
</style>

<div class="users-page">
    <div class="users-header">
        <h1 class="users-title">Users</h1>
        <div class="users-search">
            <input type="text" id="search" placeholder="Search users...">
            <i class="fas fa-search"></i>
        </div>
    </div>

    <div class="users-card">
        <div class="users-table-wrap">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Business</th>
                        <th>Joined</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
            <tbody id="users-table">
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">
                                    <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <span class="user-name">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->is_admin)
                                <span class="badge badge-admin">Admin</span>
                            @else
                                <span class="badge badge-user">User</span>
                            @endif
                        </td>
                        <td>
                            @if($user->business)
                                <a href="{{ route('admin.businesses.show', $user->business) }}" class="business-link">
                                    {{ $user->business->name }}
                                </a>
                            @else
                                <span class="muted-text">—</span>
                            @endif
                        </td>
                        <td class="muted-text">{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="action-group">
                                <button onclick="toggleAdmin({{ $user->id }}, {{ $user->is_admin ? 'false' : 'true' }})" class="action-btn">
                                    {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                                </button>
                                @if(!$user->is_admin)
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="danger-btn" type="submit">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            <i class="fas fa-users" style="font-size:1.8rem; display:block; margin-bottom:0.5rem;"></i>
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <p>Showing {{ $users->count() }} of {{ $users->total() }} users</p>
        {{ $users->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleAdmin(userId, makeAdmin) {
    if (!confirm(makeAdmin ? 'Make this user an admin?' : 'Remove admin access?')) return;
    
    fetch(`/admin/users/${userId}/toggle-admin`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ is_admin: makeAdmin })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    });
}

document.getElementById('search').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#users-table tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(search) ? '' : 'none';
    });
});
</script>
@endpush