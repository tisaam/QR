@extends('layouts.app')

@section('title', 'QR Codes')

@push('styles')
<style>
    .qr-index-page {
        max-width: 72rem;
        margin: 0 auto;
        padding: 0 1rem;
    }
    .page-header {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    @media (min-width: 640px) {
        .page-header {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
    }
    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .header-subtitle {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    .header-subtitle.text-danger {
        color: #dc2626;
        font-weight: 500;
    }
    .header-subtitle.text-warning {
        color: #d97706;
        font-weight: 500;
    }
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        background: #2563eb;
        color: #ffffff;
        font-weight: 600;
        border: none;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.2s ease;
        font-family: inherit;
        font-size: 0.875rem;
    }
    .btn-primary:hover {
        background: #1d4ed8;
    }
    .btn-orange {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        background: #f97316;
        color: #ffffff;
        font-weight: 600;
        border: none;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.2s ease;
        font-family: inherit;
        font-size: 0.875rem;
    }
    .btn-orange:hover {
        background: #ea580c;
    }
    .table-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 1.25rem;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
    }
    .table-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .qr-table {
        width: 100%;
        min-width: 650px;
        border-collapse: collapse;
        font-size: 0.95rem;
        color: #4b5563;
    }
    .qr-table th,
    .qr-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    .qr-table thead th {
        background: #f9fafb;
        text-align: left;
        color: #6b7280;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        border-bottom: 1px solid #e5e7eb;
    }
    .qr-table tbody tr:hover {
        background: #f9fafb;
    }
    .qr-table tbody tr:last-child td {
        border-bottom: none;
    }
    .qr-preview,
    .qr-status,
    .qr-actions {
        white-space: nowrap;
    }
    .qr-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    .qr-badge.active {
        background: #dcfce7;
        color: #166534;
    }
    .qr-badge.disabled {
        background: #fee2e2;
        color: #b91c1c;
    }
    .qr-image {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        padding: 0.25rem;
        object-fit: contain;
        background: #ffffff;
    }
    .qr-empty {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.75rem;
        background: #f3f4f6;
        color: #9ca3af;
        font-size: 0.75rem;
        border: 1px solid #e5e7eb;
    }
    .qr-name {
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
    }
    .qr-subtitle {
        font-size: 0.75rem;
        color: #6b7280;
    }
    .action-link,
    .action-button {
        border: none;
        background: transparent;
        color: inherit;
        cursor: pointer;
        text-decoration: none;
        font-size: 1rem;
        padding: 0;
    }
    .action-link:hover,
    .action-button:hover {
        color: #1d4ed8;
    }
    .qr-list-empty td {
        padding: 2.5rem 1.25rem;
        text-align: center;
        color: #9ca3af;
    }
    .qr-list-empty a {
        color: #2563eb;
        text-decoration: underline;
    }

    /* ── Approval States ── */
    .state-card {
        border-radius: 1.25rem;
        padding: 3rem 1.5rem;
        text-align: center;
        max-width: 420px;
        margin: 2rem auto;
    }
    .state-icon {
        width: 4rem;
        height: 4rem;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1.25rem;
        position: relative;
    }
    .state-title {
        font-size: 1.125rem;
        font-weight: 800;
        margin: 0 0 0.5rem;
        letter-spacing: -0.02em;
    }
    .state-desc {
        font-size: 0.8125rem;
        margin: 0 0 0.25rem;
        line-height: 1.6;
        max-width: 320px;
        margin-left: auto;
        margin-right: auto;
    }
    .state-hint {
        font-size: 0.75rem;
        font-weight: 600;
        margin: 0;
    }
    .state-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.75rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        font-family: inherit;
        border: none;
        margin-top: 1.5rem;
        transition: all 0.2s ease;
    }
    .state-btn:active { transform: scale(0.97); }

    /* Pending */
    .state-pending { background: #fff; border: 2px dashed #e2e8f0; }
    .state-pending .state-icon { background: #eef2ff; color: #6366f1; }
    .state-pending .state-title { color: #0f172a; }
    .state-pending .state-desc { color: #94a3b8; }
    .state-pending .state-btn { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; box-shadow: 0 4px 16px rgba(99,102,241,0.3); }

    /* Requested */
    .state-requested { background: #fffbeb; border: 1px solid #fde68a; }
    .state-requested .state-icon { background: #fef3c7; color: #d97706; }
    .state-requested .state-title { color: #92400e; }
    .state-requested .state-desc { color: #b45309; }
    .state-requested .state-hint { color: #d97706; }

    /* Rejected */
    .state-rejected { background: #fef2f2; border: 1px solid #fecaca; }
    .state-rejected .state-icon { background: #fee2e2; color: #dc2626; }
    .state-rejected .state-title { color: #991b1b; }
    .state-rejected .state-desc { color: #b91c1c; }
    .state-rejected .state-btn { background: #fff; color: #dc2626; border: 1.5px solid #fecaca; }

    /* Blocked */
    .state-blocked { background: #fef2f2; border: 1px solid #fecaca; }
    .state-blocked .state-icon { background: #fee2e2; color: #dc2626; }
    .state-blocked .state-title { color: #991b1b; }
    .state-blocked .state-desc { color: #b91c1c; }
    .state-blocked .state-btn { background: #fff; color: #dc2626; border: 1.5px solid #fecaca; }

    /* Suspended */
    .state-suspended { background: #fffbeb; border: 1px solid #fde68a; }
    .state-suspended .state-icon { background: #fef3c7; color: #d97706; }
    .state-suspended .state-title { color: #92400e; }
    .state-suspended .state-desc { color: #b45309; }
    .state-suspended .state-btn { background: #fff; color: #d97706; border: 1.5px solid #fde68a; }

    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    .spin-ring {
        position: absolute;
        inset: -6px;
        border-radius: 50%;
        border: 2px dashed #fbbf24;
        opacity: 0.3;
        animation: spin 15s linear infinite;
    }
    /* ==========================================================
   QR INDEX PAGE - DARK MODE ONLY
========================================================== */

body.dark-mode .qr-index-page{
    color:#e5e7eb !important;
}

/* Header */
body.dark-mode .page-title{
    color:#ffffff !important;
}

body.dark-mode .header-subtitle{
    color:#94a3b8 !important;
}

body.dark-mode .header-subtitle.text-danger{
    color:#f87171 !important;
}

body.dark-mode .header-subtitle.text-warning{
    color:#fbbf24 !important;
}

/* Buttons */
body.dark-mode .btn-primary{
    background:#6366f1 !important;
    color:#ffffff !important;
}

body.dark-mode .btn-primary:hover{
    background:#4f46e5 !important;
}

body.dark-mode .btn-orange{
    background:#ea580c !important;
    color:#ffffff !important;
}

body.dark-mode .btn-orange:hover{
    background:#c2410c !important;
}

/* Table Card */
body.dark-mode .table-card{
    background:#1e293b !important;
    border:1px solid #334155 !important;
    box-shadow:none !important;
}

/* Table */
body.dark-mode .qr-table{
    color:#cbd5e1 !important;
}

body.dark-mode .qr-table thead th{
    background:#0f172a !important;
    color:#94a3b8 !important;
    border-bottom:1px solid #334155 !important;
}

body.dark-mode .qr-table td{
    border-bottom:1px solid #334155 !important;
}

body.dark-mode .qr-table tbody tr:hover{
    background:#273449 !important;
}

/* QR Name */
body.dark-mode .qr-name{
    color:#ffffff !important;
}

body.dark-mode .qr-subtitle{
    color:#94a3b8 !important;
}

/* QR Image */
body.dark-mode .qr-image{
    background:#0f172a !important;
    border:1px solid #334155 !important;
}

body.dark-mode .qr-empty{
    background:#334155 !important;
    border:1px solid #475569 !important;
    color:#94a3b8 !important;
}

/* Status Badge */
body.dark-mode .qr-badge.active{
    background:rgba(34,197,94,.15) !important;
    color:#4ade80 !important;
}

body.dark-mode .qr-badge.disabled{
    background:rgba(239,68,68,.15) !important;
    color:#f87171 !important;
}

/* Actions */
body.dark-mode .action-link,
body.dark-mode .action-button{
    color:#cbd5e1 !important;
}

body.dark-mode .action-link:hover,
body.dark-mode .action-button:hover{
    color:#818cf8 !important;
}

/* Empty State */
body.dark-mode .qr-list-empty td{
    color:#94a3b8 !important;
}

body.dark-mode .qr-list-empty a{
    color:#818cf8 !important;
}

/* ==========================================================
   APPROVAL STATES
========================================================== */

/* Pending */
body.dark-mode .state-pending{
    background:#1e293b !important;
    border:1px solid #334155 !important;
}

body.dark-mode .state-pending .state-icon{
    background:#312e81 !important;
    color:#a5b4fc !important;
}

body.dark-mode .state-pending .state-title{
    color:#ffffff !important;
}

body.dark-mode .state-pending .state-desc{
    color:#94a3b8 !important;
}

body.dark-mode .state-pending .state-btn{
    background:#6366f1 !important;
    color:#ffffff !important;
}

/* Requested */
body.dark-mode .state-requested{
    background:#422006 !important;
    border:1px solid #92400e !important;
}

body.dark-mode .state-requested .state-icon{
    background:#78350f !important;
    color:#fbbf24 !important;
}

body.dark-mode .state-requested .state-title{
    color:#fde68a !important;
}

body.dark-mode .state-requested .state-desc,
body.dark-mode .state-requested .state-hint{
    color:#fcd34d !important;
}

/* Rejected */
body.dark-mode .state-rejected{
    background:#3f0d12 !important;
    border:1px solid #7f1d1d !important;
}

body.dark-mode .state-rejected .state-icon{
    background:#7f1d1d !important;
    color:#f87171 !important;
}

body.dark-mode .state-rejected .state-title{
    color:#fecaca !important;
}

body.dark-mode .state-rejected .state-desc{
    color:#fca5a5 !important;
}

body.dark-mode .state-rejected .state-btn{
    background:#1e293b !important;
    border:1px solid #7f1d1d !important;
    color:#f87171 !important;
}

/* Blocked */
body.dark-mode .state-blocked{
    background:#3f0d12 !important;
    border:1px solid #7f1d1d !important;
}

body.dark-mode .state-blocked .state-icon{
    background:#7f1d1d !important;
    color:#f87171 !important;
}

body.dark-mode .state-blocked .state-title{
    color:#fecaca !important;
}

body.dark-mode .state-blocked .state-desc{
    color:#fca5a5 !important;
}

body.dark-mode .state-blocked .state-btn{
    background:#1e293b !important;
    border:1px solid #7f1d1d !important;
    color:#f87171 !important;
}

/* Suspended */
body.dark-mode .state-suspended{
    background:#422006 !important;
    border:1px solid #92400e !important;
}

body.dark-mode .state-suspended .state-icon{
    background:#78350f !important;
    color:#fbbf24 !important;
}

body.dark-mode .state-suspended .state-title{
    color:#fde68a !important;
}

body.dark-mode .state-suspended .state-desc{
    color:#fcd34d !important;
}

body.dark-mode .state-suspended .state-btn{
    background:#1e293b !important;
    border:1px solid #92400e !important;
    color:#fbbf24 !important;
}

/* Spinner */
body.dark-mode .spin-ring{
    border-color:#fbbf24 !important;
    opacity:.45;
}
</style>
@endpush

@section('content')
<div class="qr-index-page">

    @php
        $business = \App\Models\Business::where('user_id', auth()->id())->first();
        
        // Approval checks
        $isApproved = $business && $business->canGenerateQr();
        $isPending = $business && $business->isPendingSetup();
        $hasRequested = $business && $business->hasRequestedApproval();
        $isRejected = $business && $business->status === 'rejected';
        $isBlocked = $business && $business->status === 'blocked';
        $isSuspended = $business && $business->status === 'suspended';

        // Plan checks (sirf approved hone ke baad)
        if ($isApproved) {
            $user = auth()->user();
            $hasPlan = $user->activeSubscription ? true : false;
            $qrLimit = $hasPlan ? ($user->activeSubscription->plan->limits['qr_codes'] ?? 0) : 0;
            $currentQrCount = $business->qrCodes()->count();
            $isUnlimited = ($qrLimit <= 0);
            $canGenerate = $hasPlan && ($isUnlimited || $currentQrCount < $qrLimit);
        }
    @endphp

    @if($isApproved)
        <!-- ═══════════════════════════════════════════ -->
        <!-- NORMAL QR UI — Business approved hai        -->
        <!-- ═══════════════════════════════════════════ -->

        <div class="page-header">
            <div>
                <h1 class="page-title">My QR Codes</h1>
                <p class="header-subtitle {{ !$hasPlan ? 'text-danger' : '' }}">
                    @if(!$hasPlan)
                        No active plan
                    @elseif($isUnlimited)
                        Unlimited QR Codes
                    @else
                        {{ $currentQrCount }} of {{ $qrLimit }} QR Codes used
                    @endif
                </p>
            </div>

            @if($canGenerate)
                <a href="{{ route('qr-codes.create') }}" class="btn-primary">
                    <i class="fas fa-plus"></i> Create QR Code
                </a>
            @else
                <a href="{{ route('plans.index') }}" class="btn-orange">
                    <i class="fas fa-lock"></i>
                    {{ !$hasPlan ? 'Subscribe to Plan' : 'Upgrade Plan (Limit Reached)' }}
                </a>
            @endif
        </div>

        <div class="table-card">
            <div class="table-wrapper">
                <table class="qr-table">
                    <thead>
                        <tr>
                            <th>Preview</th>
                            <th>Name / Type</th>
                            <th>Destination</th>
                            <th>Scans</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($qrCodes as $qr)
                            <tr>
                                <td class="qr-preview">
                                    @if($qr->qr_image_path)
                                        <img src="{{ asset('storage/' . $qr->qr_image_path) }}"
                                             alt="QR"
                                             class="qr-image"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="qr-empty" style="display:none;">Err</div>
                                    @else
                                        <div class="qr-empty">None</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="qr-name">{{ $qr->name }}</div>
                                    <div class="qr-subtitle">{{ strtoupper($qr->type) }}{{ $qr->identifier ? ' - ' . $qr->identifier : '' }}</div>
                                </td>
                                <td>
                                    <div class="qr-subtitle" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $qr->landing_page_url }}">
                                        {{ $qr->landing_page_url }}
                                    </div>
                                </td>
                                <td class="font-semibold">{{ $qr->scan_count }}</td>
                                <td class="qr-status">
                                    <span class="qr-badge {{ $qr->is_active ? 'active' : 'disabled' }}">
                                        {{ $qr->is_active ? 'Active' : 'Disabled' }}
                                    </span>
                                </td>
                                <td class="qr-actions" style="text-align:right;">
                                    <a href="{{ route('qr-codes.download', [$qr, 'jpg']) }}" class="action-link" title="Download JPG">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <form action="{{ route('qr-codes.destroy', $qr) }}" method="POST" style="display:inline-block; margin-left:0.75rem;" onsubmit="return confirm('Delete this QR code?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-button" title="Delete QR Code">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr class="qr-list-empty">
                                <td colspan="6">
                                    No QR codes yet. <a href="{{ route('qr-codes.create') }}">Create one now</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @elseif($isPending)
        <!-- ═══════════════════════════════════ -->
        <!-- PENDING — Request QR Access         -->
        <!-- ═══════════════════════════════════ -->
        <div class="page-header">
            <div>
                <h1 class="page-title">My QR Codes</h1>
                <p class="header-subtitle text-warning">Approval required to generate QR codes</p>
            </div>
        </div>

        <div class="state-card state-pending">
            <div class="state-icon">
                <i class="fas fa-qrcode"></i>
            </div>
            <h3 class="state-title">Generate Your First QR Code</h3>
            <p class="state-desc">
                To start generating QR codes, you need admin approval. Click below to send a request.
            </p>
            <form method="POST" action="{{ route('qr-approval.request') }}">
                @csrf
                <button type="submit" class="state-btn">
                    <i class="fas fa-paper-plane"></i> Request QR Access
                </button>
            </form>
        </div>

    @elseif($hasRequested)
        <!-- ═══════════════════════════════════ -->
        <!-- AWAITING APPROVAL                   -->
        <!-- ═══════════════════════════════════ -->
        <div class="page-header">
            <div>
                <h1 class="page-title">My QR Codes</h1>
                <p class="header-subtitle text-warning">Waiting for admin approval</p>
            </div>
        </div>

        <div class="state-card state-requested">
            <div class="state-icon">
                <i class="fas fa-clock"></i>
                <div class="spin-ring"></div>
            </div>
            <h3 class="state-title">Approval Pending</h3>
            <p class="state-desc">
                Your request to generate QR codes has been sent to the admin.
            </p>
            <p class="state-hint">You'll be notified once it's approved.</p>
        </div>

    @elseif($isRejected)
        <!-- ═══════════════════════════════════ -->
        <!-- REJECTED                            -->
        <!-- ═══════════════════════════════════ -->
        <div class="page-header">
            <div>
                <h1 class="page-title">My QR Codes</h1>
                <p class="header-subtitle text-danger">Request was rejected</p>
            </div>
        </div>

        <div class="state-card state-rejected">
            <div class="state-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <h3 class="state-title">Request Rejected</h3>
            <p class="state-desc">
                Your QR access request was rejected. Please contact support for more information.
            </p>
            <a href="{{ route('settings.index') }}" class="state-btn">
                <i class="fas fa-headset"></i> Contact Support
            </a>
        </div>

    @elseif($isBlocked)
        <!-- ═══════════════════════════════════ -->
        <!-- BLOCKED                             -->
        <!-- ═══════════════════════════════════ -->
        <div class="page-header">
            <div>
                <h1 class="page-title">My QR Codes</h1>
                <p class="header-subtitle text-danger">Business is blocked</p>
            </div>
        </div>

        <div class="state-card state-blocked">
            <div class="state-icon">
                <i class="fas fa-ban"></i>
            </div>
            <h3 class="state-title">Business Blocked</h3>
            <p class="state-desc">
                Your business has been blocked by the admin. QR generation and other features are restricted.
            </p>
            <a href="{{ route('settings.index') }}" class="state-btn">
                <i class="fas fa-headset"></i> Contact Support
            </a>
        </div>

    @elseif($isSuspended)
        <!-- ═══════════════════════════════════ -->
        <!-- SUSPENDED                           -->
        <!-- ═══════════════════════════════════ -->
        <div class="page-header">
            <div>
                <h1 class="page-title">My QR Codes</h1>
                <p class="header-subtitle text-warning">Account is suspended</p>
            </div>
        </div>

        <div class="state-card state-suspended">
            <div class="state-icon">
                <i class="fas fa-pause-circle"></i>
            </div>
            <h3 class="state-title">Account Suspended</h3>
            <p class="state-desc">
                Your account is temporarily suspended. Please contact support for assistance.
            </p>
            <a href="{{ route('settings.index') }}" class="state-btn">
                <i class="fas fa-headset"></i> Contact Support
            </a>
        </div>

    @else
        <!-- ═══════════════════════════════════ -->
        <!-- NO BUSINESS FOUND                   -->
        <!-- ═══════════════════════════════════ -->
        <div class="page-header">
            <div>
                <h1 class="page-title">My QR Codes</h1>
                <p class="header-subtitle">No business found</p>
            </div>
        </div>

        <div class="state-card state-pending">
            <div class="state-icon">
                <i class="fas fa-building"></i>
            </div>
            <h3 class="state-title">Complete Your Profile</h3>
            <p class="state-desc">
                You need to set up your business profile before generating QR codes.
            </p>
            <a href="{{ route('onboarding') }}" class="state-btn" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; box-shadow: 0 4px 16px rgba(99,102,241,0.3);">
                <i class="fas fa-arrow-right"></i> Start Setup
            </a>
        </div>
    @endif

</div>
@endsection