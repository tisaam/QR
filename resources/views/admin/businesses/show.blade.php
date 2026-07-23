@extends('layouts.admin')

@section('title', 'Business Details — ' . ($business->name ?? ''))

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    .bd-page { font-family: 'Inter', sans-serif; color: #1e293b; }

    .bd-back {
        display: inline-flex; align-items: center; gap: 6px; color: #6366f1;
        text-decoration: none; font-size: 13px; font-weight: 600; margin-bottom: 20px;
        padding: 6px 0; transition: color 0.15s;
    }
    .bd-back:hover { color: #4f46e5; }

    .bd-grid { display: grid; grid-template-columns: 1fr 360px; gap: 18px; }
    .bd-col { display: flex; flex-direction: column; gap: 18px; }

    /* Card */
    .bd-card {
        background: #fff; border: 1px solid #f1f5f9; border-radius: 16px;
        padding: 22px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    .bd-card-title {
        font-size: 14px; font-weight: 700; color: #0f172a; margin: 0 0 16px;
        display: flex; align-items: center; gap: 8px; letter-spacing: -0.01em;
    }
    .bd-card-title i { font-size: 13px; color: #94a3b8; }

    /* Header Card */
    .bd-header { display: flex; align-items: center; gap: 16px; padding-bottom: 18px; border-bottom: 1px solid #f1f5f9; margin-bottom: 18px; }
    .bd-logo {
        width: 56px; height: 56px; border-radius: 14px; object-fit: cover;
        border: 1px solid #f1f5f9; flex-shrink: 0;
    }
    .bd-logo-placeholder {
        width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center;
        justify-content: center; background: #eef2ff; color: #6366f1; font-size: 20px; flex-shrink: 0;
    }
    .bd-name { font-size: 18px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.02em; }
    .bd-slug { font-size: 12px; color: #94a3b8; margin: 3px 0 0; font-weight: 500; }

    /* Pills */
    .pill {
        display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px;
        border-radius: 8px; font-size: 11px; font-weight: 700; margin-top: 8px;
        border: 1px solid transparent;
    }
    .pill-dot { width: 6px; height: 6px; border-radius: 50%; }
    .pill-active { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
    .pill-active .pill-dot { background: #10b981; }
    .pill-blocked { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
    .pill-blocked .pill-dot { background: #ef4444; }
    .pill-suspended { background: #fffbeb; color: #d97706; border-color: #fde68a; }
    .pill-suspended .pill-dot { background: #f59e0b; }
    .pill-rejected { background: #f8fafc; color: #64748b; border-color: #e2e8f0; }
    .pill-rejected .pill-dot { background: #94a3b8; }
    .pill-inactive { background: #f8fafc; color: #94a3b8; border-color: #e2e8f0; }
    .pill-inactive .pill-dot { background: #cbd5e1; }

    /* Info Grid */
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .info-item {}
    .info-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 4px; }
    .info-value { font-size: 13px; font-weight: 600; color: #0f172a; }
    .info-value.muted { color: #94a3b8; font-weight: 500; }
    .full-w { grid-column: 1 / -1; }

    /* Block Banner */
    .block-banner {
        background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px;
        padding: 14px 16px; display: flex; align-items: flex-start; gap: 12px; margin-bottom: 18px;
    }
    .block-banner i { color: #ef4444; font-size: 16px; margin-top: 2px; }
    .block-banner-text { font-size: 13px; color: #991b1b; line-height: 1.5; }
    .block-banner-text strong { font-weight: 700; }
    .block-banner-time { font-size: 11px; color: #b91c1c; margin-top: 4px; opacity: 0.7; }

    /* Usage Section */
    .usage-row { display: flex; align-items: center; gap: 14px; padding: 12px 0; }
    .usage-row + .usage-row { border-top: 1px solid #f8fafc; }
    .usage-info { flex: 1; min-width: 0; }
    .usage-label { font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 6px; display: flex; justify-content: space-between; }
    .usage-count { font-size: 12px; color: #64748b; font-weight: 500; }
    .usage-bar { width: 100%; height: 8px; background: #f1f5f9; border-radius: 8px; overflow: hidden; }
    .usage-fill { height: 100%; border-radius: 8px; transition: width 0.6s ease; }
    .usage-fill.ok { background: linear-gradient(90deg, #10b981, #34d399); }
    .usage-fill.warn { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .usage-fill.danger { background: linear-gradient(90deg, #ef4444, #f87171); }
    .usage-fill.full { background: linear-gradient(90deg, #ef4444, #dc2626); }
    .usage-pct { font-size: 12px; font-weight: 700; min-width: 40px; text-align: right; }
    .usage-pct.ok { color: #059669; }
    .usage-pct.warn { color: #d97706; }
    .usage-pct.danger, .usage-pct.full { color: #dc2626; }
    .usage-limit-reached {
        display: inline-flex; align-items: center; gap: 4px; font-size: 11px;
        font-weight: 700; color: #dc2626; margin-top: 4px;
    }

    /* Action Buttons */
    .action-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 16px; padding-top: 16px; border-top: 1px solid #f1f5f9; }
    .act-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 10px 14px; border-radius: 10px; font-size: 12px; font-weight: 700;
        cursor: pointer; transition: all 0.15s; border: 1.5px solid transparent;
        font-family: inherit; text-decoration: none;
    }
    .act-btn:active { transform: scale(0.97); }
    .act-approve { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
    .act-approve:hover { background: #d1fae5; }
    .act-warn { background: #fffbeb; color: #d97706; border-color: #fde68a; }
    .act-warn:hover { background: #fef3c7; }
    .act-block { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
    .act-block:hover { background: #fee2e2; }
    .act-unblock { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
    .act-unblock:hover { background: #d1fae5; }
    .act-reject { background: #f8fafc; color: #64748b; border-color: #e2e8f0; }
    .act-reject:hover { background: #f1f5f9; }
    .act-suspend { background: #fffbeb; color: #d97706; border-color: #fde68a; }
    .act-suspend:hover { background: #fef3c7; }
    .act-full { grid-column: 1 / -1; }

    /* Owner Card */
    .owner-center { text-align: center; margin-bottom: 14px; }
    .owner-av-big {
        width: 48px; height: 48px; border-radius: 14px; background: #eef2ff; color: #6366f1;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 18px; font-weight: 800; margin-bottom: 10px;
    }
    .owner-name { font-size: 15px; font-weight: 700; color: #0f172a; margin: 0; }
    .owner-email { font-size: 12px; color: #94a3b8; margin: 3px 0 0; }
    .meta-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 13px; border-bottom: 1px solid #f8fafc; }
    .meta-row:last-child { border-bottom: none; }
    .meta-label { color: #94a3b8; font-weight: 500; }
    .meta-value { color: #0f172a; font-weight: 700; }

    /* Plan Card */
    .plan-center { text-align: center; padding: 8px 0; }
    .plan-icon {
        width: 48px; height: 48px; border-radius: 14px; background: #fef3c7; color: #d97706;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 18px; margin-bottom: 10px;
    }
    .plan-name { font-size: 15px; font-weight: 800; color: #0f172a; margin: 0; }
    .plan-price { font-size: 24px; font-weight: 800; color: #6366f1; margin: 6px 0 0; }
    .plan-price span { font-size: 13px; color: #94a3b8; font-weight: 500; }
    .plan-empty { text-align: center; padding: 16px 0; color: #94a3b8; }
    .plan-empty i { font-size: 28px; color: #e2e8f0; margin-bottom: 8px; display: block; }

    /* Warnings Table */
    .warn-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .warn-table th, .warn-table td { padding: 10px 12px; border-bottom: 1px solid #f8fafc; text-align: left; vertical-align: top; }
    .warn-table thead th { font-size: 10px; text-transform: uppercase; letter-spacing: 0.06em; color: #94a3b8; font-weight: 700; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
    .warn-table tbody tr:last-child td { border-bottom: none; }
    .warn-table tbody tr:hover { background: #f8fafc; }
    .severity-badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; border: 1px solid; }
    .severity-low { background: #fffbeb; color: #d97706; border-color: #fde68a; }
    .severity-medium { background: #fff7ed; color: #ea580c; border-color: #fed7aa; }
    .severity-high { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
    .warn-empty { text-align: center; padding: 24px 0; color: #94a3b8; }
    .warn-empty i { font-size: 24px; color: #e2e8f0; margin-bottom: 8px; display: block; }

    /* Sub Table */
    .sub-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .sub-table th, .sub-table td { padding: 10px 12px; border-bottom: 1px solid #f8fafc; text-align: left; }
    .sub-table thead th { font-size: 10px; text-transform: uppercase; letter-spacing: 0.06em; color: #94a3b8; font-weight: 700; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
    .sub-table tbody tr:last-child td { border-bottom: none; }

    /* Modal */
    .modal-overlay {
        position: fixed; inset: 0; background: rgba(15,23,42,0.5); backdrop-filter: blur(4px);
        z-index: 9999; display: flex; align-items: center; justify-content: center;
        opacity: 0; visibility: hidden; transition: all 0.2s;
    }
    .modal-overlay.open { opacity: 1; visibility: visible; }
    .modal-box {
        background: #fff; border-radius: 20px; padding: 28px; width: 90%; max-width: 460px;
        box-shadow: 0 24px 60px rgba(0,0,0,0.15); transform: scale(0.95) translateY(10px);
        transition: transform 0.25s;
    }
    .modal-overlay.open .modal-box { transform: scale(1) translateY(0); }
    .modal-title { font-size: 17px; font-weight: 800; color: #0f172a; margin: 0 0 6px; }
    .modal-desc { font-size: 13px; color: #94a3b8; margin: 0 0 20px; line-height: 1.5; }
    .modal-label { font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px; display: block; }
    .modal-textarea, .modal-select {
        width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 12px 14px;
        font-size: 13px; font-family: inherit; color: #1e293b; outline: none;
        transition: border-color 0.2s, box-shadow 0.2s; resize: vertical; min-height: 80px;
        background: #fff;
    }
    .modal-select { min-height: auto; cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px; }
    .modal-textarea:focus, .modal-select:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
    .modal-actions { display: flex; gap: 8px; margin-top: 20px; }
    .modal-btn {
        flex: 1; padding: 11px 16px; border-radius: 12px; font-size: 13px; font-weight: 700;
        cursor: pointer; transition: all 0.15s; border: 1.5px solid transparent; font-family: inherit;
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    }
    .modal-btn:active { transform: scale(0.97); }
    .modal-btn-cancel { background: #f8fafc; color: #64748b; border-color: #e2e8f0; }
    .modal-btn-cancel:hover { background: #f1f5f9; }
    .modal-btn-warn { background: #fffbeb; color: #d97706; border-color: #fde68a; }
    .modal-btn-warn:hover { background: #fef3c7; }
    .modal-btn-block { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
    .modal-btn-block:hover { background: #fee2e2; }
    .modal-error { font-size: 12px; color: #dc2626; margin-top: 6px; display: none; }

    /* Toast */
    .toast {
        position: fixed; bottom: 24px; right: 24px; padding: 14px 20px; border-radius: 14px;
        font-size: 13px; font-weight: 600; color: #fff; z-index: 99999;
        transform: translateY(20px); opacity: 0; transition: all 0.3s; pointer-events: none;
        display: flex; align-items: center; gap: 8px;
        box-shadow: 0 12px 40px rgba(0,0,0,0.2);
    }
    .toast.show { transform: translateY(0); opacity: 1; }
    .toast.success { background: #059669; }
    .toast.error { background: #dc2626; }

    @media (max-width: 1024px) { .bd-grid { grid-template-columns: 1fr; } }
    @media (max-width: 640px) { .info-grid { grid-template-columns: 1fr; } .action-grid { grid-template-columns: 1fr; } }

/* ===========================================================
   BUSINESS DETAILS PAGE - DARK MODE ONLY
   (Light mode remains EXACTLY the same)
=========================================================== */

body:not(.light-mode) .bd-page{
    color:var(--child-text);
}

/* Cards */

body:not(.light-mode) .bd-card,
body:not(.light-mode) .modal-box{
    background:var(--child-bg);
    border-color:var(--child-border);
    box-shadow:0 15px 35px rgba(0,0,0,.35);
}

/* Titles */

body:not(.light-mode) .bd-card-title,
body:not(.light-mode) .bd-name,
body:not(.light-mode) .owner-name,
body:not(.light-mode) .plan-name,
body:not(.light-mode) .modal-title{
    color:var(--child-text);
}

body:not(.light-mode) .bd-card-title i,
body:not(.light-mode) .bd-slug,
body:not(.light-mode) .owner-email,
body:not(.light-mode) .modal-desc{
    color:var(--child-text-sec);
}

/* Header */

body:not(.light-mode) .bd-header{
    border-bottom-color:var(--child-border);
}

body:not(.light-mode) .bd-logo{
    border-color:var(--child-border);
}

/* Information */

body:not(.light-mode) .info-label,
body:not(.light-mode) .meta-label{
    color:var(--child-text-sec);
}

body:not(.light-mode) .info-value,
body:not(.light-mode) .meta-value{
    color:var(--child-text);
}

body:not(.light-mode) .info-value.muted{
    color:var(--child-text-sec);
}

/* Usage */

body:not(.light-mode) .usage-row{
    border-color:var(--child-border);
}

body:not(.light-mode) .usage-label{
    color:var(--child-text);
}

body:not(.light-mode) .usage-count{
    color:var(--child-text-sec);
}

body:not(.light-mode) .usage-bar{
    background:#334155;
}

/* Action Grid */

body:not(.light-mode) .action-grid{
    border-top-color:var(--child-border);
}

/* Tables */

body:not(.light-mode) .warn-table,
body:not(.light-mode) .sub-table{
    color:var(--child-text);
}

body:not(.light-mode) .warn-table thead th,
body:not(.light-mode) .sub-table thead th{
    background:rgba(255,255,255,.04);
    color:var(--child-text-sec);
    border-color:var(--child-border);
}

body:not(.light-mode) .warn-table td,
body:not(.light-mode) .warn-table th,
body:not(.light-mode) .sub-table td,
body:not(.light-mode) .sub-table th{
    border-color:var(--child-border);
}

body:not(.light-mode) .warn-table tbody tr:hover{
    background:rgba(255,255,255,.03);
}

/* Owner */

body:not(.light-mode) .owner-av-big{
    background:rgba(99,102,241,.15);
    color:#a5b4fc;
}

/* Plan */

body:not(.light-mode) .plan-icon{
    background:rgba(245,158,11,.15);
}

body:not(.light-mode) .plan-price{
    color:#818cf8;
}

body:not(.light-mode) .plan-price span{
    color:var(--child-text-sec);
}

body:not(.light-mode) .plan-empty{
    color:var(--child-text-sec);
}

body:not(.light-mode) .plan-empty i{
    color:#475569;
}

/* Meta */

body:not(.light-mode) .meta-row{
    border-color:var(--child-border);
}

/* Modal */

body:not(.light-mode) .modal-label{
    color:var(--child-text);
}

body:not(.light-mode) .modal-textarea,
body:not(.light-mode) .modal-select{
    background:var(--child-bg);
    color:var(--child-text);
    border-color:var(--child-border);
}

body:not(.light-mode) .modal-textarea::placeholder{
    color:var(--child-text-sec);
}

body:not(.light-mode) .modal-btn-cancel{
    background:rgba(255,255,255,.05);
    border-color:var(--child-border);
    color:var(--child-text);
}

body:not(.light-mode) .modal-btn-cancel:hover{
    background:rgba(255,255,255,.08);
}

/* Empty States */

body:not(.light-mode) .warn-empty{
    color:var(--child-text-sec);
}

body:not(.light-mode) .warn-empty i{
    color:#475569;
}

/* Banner */

body:not(.light-mode) .block-banner{
    background:rgba(239,68,68,.12);
    border-color:rgba(239,68,68,.25);
}

body:not(.light-mode) .block-banner-text{
    color:#fca5a5;
}

body:not(.light-mode) .block-banner-time{
    color:#f87171;
}
/*==================================================
  RESPONSIVE CSS (NO HTML CHANGES REQUIRED)
===================================================*/

/* Tablets */
@media (max-width:1024px){

    .bd-grid{
        grid-template-columns:1fr !important;
        gap:16px;
    }

    .bd-col{
        gap:16px;
    }

    .bd-card{
        padding:18px;
    }

}


/* Mobile */
/* =====================================
   TABLE RESPONSIVE (NO HTML REQUIRED)
=====================================*/

@media (max-width:768px){

    .warn-table,
    .sub-table{
        display:block;
        width:100%;
        overflow-x:auto;
        overflow-y:hidden;
        white-space:nowrap;
        -webkit-overflow-scrolling:touch;
        border-collapse:collapse;
    }

    .warn-table thead,
    .warn-table tbody,
    .warn-table tr,
    .warn-table th,
    .warn-table td,
    .sub-table thead,
    .sub-table tbody,
    .sub-table tr,
    .sub-table th,
    .sub-table td{
        white-space:nowrap;
    }

    .warn-table th,
    .warn-table td,
    .sub-table th,
    .sub-table td{
        min-width:140px;
    }

}


/* Small Phones */
@media (max-width:480px){

    .bd-page{
        padding:0 8px;
    }

    .bd-card{
        padding:14px;
    }

    .bd-header{
        gap:10px;
    }

    .bd-logo,
    .bd-logo-placeholder{
        width:48px;
        height:48px;
    }

    .bd-name{
        font-size:17px;
    }

    .bd-card-title{
        font-size:13px;
    }

    .info-label{
        font-size:10px;
    }

    .info-value{
        font-size:12px;
        word-break:break-word;
    }

    .owner-name,
    .plan-name{
        font-size:14px;
        word-break:break-word;
    }

    .owner-email{
        word-break:break-all;
    }

    .plan-price{
        font-size:22px;
    }

    .plan-price span{
        display:block;
        margin-top:3px;
    }

    .usage-count,
    .usage-pct{
        font-size:11px;
    }

    .block-banner{
        flex-direction:column;
    }

    .meta-row{
        flex-direction:column;
        gap:4px;
    }

}


/* Very Small Devices */
@media (max-width:360px){

    .bd-card{
        padding:12px;
    }

    .bd-name{
        font-size:16px;
    }

    .act-btn{
        font-size:12px;
        padding:10px;
    }

}
</style>

<div class="bd-page">

    <a href="{{ route('admin.businesses.index') }}" class="bd-back">
        <i class="fas fa-arrow-left"></i> Back to Businesses
    </a>

    <div class="bd-grid">
        {{-- LEFT COLUMN --}}
        <div class="bd-col">

            {{-- Business Info Card --}}
            <div class="bd-card">
                <div class="bd-header">
                    @if($business->logo)
                        <img src="{{ Storage::url($business->logo) }}" alt="" class="bd-logo">
                    @else
                        <div class="bd-logo-placeholder"><i class="fas fa-building"></i></div>
                    @endif
                    <div>
                        <h2 class="bd-name">{{ $business->name }}</h2>
                        <p class="bd-slug">{{ $business->slug ?? 'N/A' }}</p>
                        @php
                            $statusClass = match($business->status) {
                                'active' => 'pill-active', 'blocked' => 'pill-blocked',
                                'suspended' => 'pill-suspended', 'rejected' => 'pill-rejected',
                                default => 'pill-inactive',
                            };
                        @endphp
                        <span class="pill {{ $statusClass }}">
                            <span class="pill-dot"></span>
                            {{ ucfirst($business->status) }}
                        </span>
                    </div>
                </div>

                {{-- Block Banner --}}
                @if($usage['is_blocked'] && $usage['block_reason'])
                    <div class="block-banner">
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <div class="block-banner-text">
                                <strong>This business is blocked.</strong><br>
                                {{ $usage['block_reason'] }}
                            </div>
                            @if($business->blocked_at)
                                <div class="block-banner-time">Blocked on {{ $business->blocked_at->format('M d, Y \a\t h:i A') }}</div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="info-grid">
                    <div class="info-item">
                        <p class="info-label">Email</p>
                        <p class="info-value">{{ $business->email ?? 'N/A' }}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Phone</p>
                        <p class="info-value">{{ $business->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="info-item full-w">
                        <p class="info-label">Address</p>
                        <p class="info-value">{{ $business->address ?? 'N/A' }}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Created</p>
                        <p class="info-value">{{ $business->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Last Updated</p>
                        <p class="info-value">{{ $business->updated_at->diffForHumans() }}</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="action-grid">
                    @if($business->status !== 'active')
                        <form method="POST" action="{{ route('admin.businesses.approve', $business) }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="act-btn act-approve act-full" onclick="return confirm('Approve this business?')">
                                <i class="fas fa-check-circle"></i> Approve
                            </button>
                        </form>
                    @endif

                    @if($business->status !== 'blocked')
                        <button type="button" class="act-btn act-warn" onclick="openWarnModal()">
                            <i class="fas fa-exclamation-triangle"></i> Warn
                        </button>
                        <form method="POST" action="{{ route('admin.businesses.block', $business) }}" style="margin:0;" id="block-form">
                            @csrf
                            <button type="button" class="act-btn act-block" onclick="openBlockModal()">
                                <i class="fas fa-ban"></i> Block
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.businesses.unblock', $business) }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="act-btn act-unblock act-full" onclick="return confirm('Unblock this business?')">
                                <i class="fas fa-unlock"></i> Unblock
                            </button>
                        </form>
                    @endif

                    @if($business->status !== 'rejected' && $business->status !== 'blocked')
                        <form method="POST" action="{{ route('admin.businesses.reject', $business) }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="act-btn act-reject" onclick="return confirm('Reject this business?')">
                                <i class="fas fa-times-circle"></i> Reject
                            </button>
                        </form>
                    @endif

                    @if($business->status !== 'suspended' && $business->status !== 'blocked')
                        <form method="POST" action="{{ route('admin.businesses.suspend', $business) }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="act-btn act-suspend" onclick="return confirm('Suspend this business?')">
                                <i class="fas fa-pause-circle"></i> Suspend
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Plan Usage Card --}}
            <div class="bd-card">
                <h3 class="bd-card-title"><i class="fas fa-chart-bar"></i> Plan Usage</h3>

                @if($plan)
                    @php
                        $qrUsed = $usage['qr_codes']['used'];
                        $qrLimit = $usage['qr_codes']['limit'];
                        $qrPct = $qrLimit > 0 ? round(($qrUsed / $qrLimit) * 100) : 0;
                        $qrClass = $qrPct >= 100 ? 'full' : ($qrPct >= 90 ? 'danger' : ($qrPct >= 70 ? 'warn' : 'ok'));

                        $revUsed = $usage['reviews']['used'];
                        $revLimit = $usage['reviews']['limit'];
                        $revPct = $revLimit > 0 ? round(($revUsed / $revLimit) * 100) : 0;
                        $revClass = $revPct >= 100 ? 'full' : ($revPct >= 90 ? 'danger' : ($revPct >= 70 ? 'warn' : 'ok'));
                    @endphp

                    <div class="usage-row">
                        <div class="usage-info">
                            <div class="usage-label">
                                <span>QR Codes</span>
                                <span class="usage-count">{{ $qrUsed }} / {{ $qrLimit > 0 ? $qrLimit : '∞' }}</span>
                            </div>
                            @if($qrLimit > 0)
                                <div class="usage-bar">
                                    <div class="usage-fill {{ $qrClass }}" style="width:{{ min($qrPct, 100) }}%"></div>
                                </div>
                                @if($qrPct >= 100)
                                    <div class="usage-limit-reached"><i class="fas fa-exclamation-circle"></i> Limit reached</div>
                                @elseif($qrPct >= 90)
                                    <div class="usage-limit-reached" style="color:#d97706;"><i class="fas fa-exclamation-circle"></i> Almost full</div>
                                @endif
                            @else
                                <div class="usage-bar"><div class="usage-fill ok" style="width:5%"></div></div>
                            @endif
                        </div>
                        <div class="usage-pct {{ $qrClass }}">{{ $qrPct }}%</div>
                    </div>

                    <div class="usage-row">
                        <div class="usage-info">
                            <div class="usage-label">
                                <span>Reviews</span>
                                <span class="usage-count">{{ $revUsed }} / {{ $revLimit > 0 ? $revLimit : '∞' }}</span>
                            </div>
                            @if($revLimit > 0)
                                <div class="usage-bar">
                                    <div class="usage-fill {{ $revClass }}" style="width:{{ min($revPct, 100) }}%"></div>
                                </div>
                                @if($revPct >= 100)
                                    <div class="usage-limit-reached"><i class="fas fa-exclamation-circle"></i> Limit reached</div>
                                @elseif($revPct >= 90)
                                    <div class="usage-limit-reached" style="color:#d97706;"><i class="fas fa-exclamation-circle"></i> Almost full</div>
                                @endif
                            @else
                                <div class="usage-bar"><div class="usage-fill ok" style="width:5%"></div></div>
                            @endif
                        </div>
                        <div class="usage-pct {{ $revClass }}">{{ $revPct }}%</div>
                    </div>
                @else
                    <div class="plan-empty" style="padding:16px 0;">
                        <i class="fas fa-chart-pie"></i>
                        <p style="font-size:13px; margin:0;">No active plan — usage not tracked.</p>
                    </div>
                @endif
            </div>

            {{-- Subscription History --}}
            <div class="bd-card">
                <h3 class="bd-card-title"><i class="fas fa-history"></i> Subscription History</h3>
                @if($business->subscriptions && $business->subscriptions->count() > 0)
                    <div style="overflow-x:auto;">
                        <table class="sub-table">
                            <thead>
                                <tr>
                                    <th>Plan</th>
                                    <th>Status</th>
                                    <th>Start</th>
                                    <th>End</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($business->subscriptions as $sub)
                                    <tr>
                                        <td style="font-weight:700; color:#0f172a;">{{ $sub->plan->name ?? 'Deleted' }}</td>
                                        <td>
                                            <span class="pill {{ $sub->status === 'active' ? 'pill-active' : 'pill-inactive' }}" style="margin-top:0; font-size:10px;">
                                                <span class="pill-dot"></span> {{ ucfirst($sub->status) }}
                                            </span>
                                        </td>
                                        <td style="color:#64748b;">{{ $sub->created_at->format('M d, Y') }}</td>
                                        <td style="color:#64748b;">{{ $sub->ends_at?->format('M d, Y') ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="warn-empty">
                        <i class="fas fa-receipt"></i>
                        <p style="font-size:13px; margin:0;">No subscriptions found.</p>
                    </div>
                @endif
            </div>

            {{-- Warnings History --}}
            <div class="bd-card">
                <h3 class="bd-card-title">
                    <i class="fas fa-exclamation-triangle"></i> Warnings
                    @if($usage['warnings'] > 0)
                        <span style="margin-left:auto; background:#fffbeb; color:#d97706; padding:2px 10px; border-radius:20px; font-size:11px; font-weight:700;">{{ $usage['warnings'] }} total</span>
                    @endif
                </h3>

                @if($business->warnings && $business->warnings->count() > 0)
                    <div style="overflow-x:auto;">
                        <table class="warn-table">
                            <thead>
                                <tr>
                                    <th>Severity</th>
                                    <th>Reason</th>
                                    <th>Given By</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($business->warnings as $w)
                                    <tr>
                                        <td>
                                            <span class="severity-badge severity-{{ $w->severity }}">
                                                @if($w->severity === 'low') ⚠️
                                                @elseif($w->severity === 'medium') 🔶
                                                @else 🔴 @endif
                                                {{ ucfirst($w->severity) }}
                                            </span>
                                        </td>
                                        <td style="color:#475569; max-width:220px; line-height:1.4;">{{ $w->reason }}</td>
                                        <td style="color:#64748b; white-space:nowrap;">{{ $w->givenBy->name ?? 'Admin' }}</td>
                                        <td style="color:#94a3b8; white-space:nowrap;">{{ $w->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="warn-empty">
                        <i class="fas fa-shield-alt"></i>
                        <p style="font-size:13px; margin:0;">No warnings issued. Clean record.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="bd-col">

            {{-- Owner Card --}}
            <div class="bd-card">
                <h3 class="bd-card-title"><i class="fas fa-user"></i> Owner</h3>
                <div class="owner-center">
                    <div class="owner-av-big">{{ strtoupper(substr($business->user->name ?? 'U', 0, 1)) }}</div>
                    <p class="owner-name">{{ $business->user->name ?? 'Unknown' }}</p>
                    <p class="owner-email">{{ $business->user->email ?? 'N/A' }}</p>
                </div>
                <div style="border-top:1px solid #f1f5f9; padding-top:10px;">
                    <div class="meta-row">
                        <span class="meta-label">Joined</span>
                        <span class="meta-value">{{ $business->user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Role</span>
                        <span class="meta-value">{{ $business->user->is_admin ? 'Admin' : 'User' }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Warnings Received</span>
                        <span class="meta-value" style="color:{{ $usage['warnings'] > 0 ? '#d97706' : '#059669' }};">{{ $usage['warnings'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Current Plan Card --}}
            <div class="bd-card">
                <h3 class="bd-card-title"><i class="fas fa-crown"></i> Current Plan</h3>
                @if($activeSubscription && $plan)
                    <div class="plan-center">
                        <div class="plan-icon"><i class="fas fa-crown"></i></div>
                        <p class="plan-name">{{ $plan->name }}</p>
                        <p class="plan-price">
                            ₹{{ number_format($plan->price ?? 0) }}
                            <span>/{{ $plan->billing_cycle ?? 'month' }}</span>
                        </p>
                    </div>
                    <div style="border-top:1px solid #f1f5f9; padding-top:10px; margin-top:14px;">
                        <div class="meta-row">
                            <span class="meta-label">Status</span>
                            <span class="meta-value" style="color:#059669;">Active</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">Expires</span>
                            <span class="meta-value">{{ $activeSubscription->ends_at?->format('M d, Y') ?? '—' }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">QR Limit</span>
                            <span class="meta-value">{{ $plan->qr_limit ?? 'Unlimited' }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">Review Limit</span>
                            <span class="meta-value">{{ $plan->review_limit ?? 'Unlimited' }}</span>
                        </div>
                    </div>
                @else
                    <div class="plan-empty">
                        <i class="fas fa-crown"></i>
                        <p style="font-size:13px; margin:0; font-weight:600;">No active plan</p>
                        <p style="font-size:12px; margin:4px 0 0; color:#cbd5e1;">Usage limits not enforced.</p>
                    </div>
                @endif
            </div>

            {{-- Quick Stats Card --}}
            <div class="bd-card">
                <h3 class="bd-card-title"><i class="fas fa-tachometer-alt"></i> Quick Stats</h3>
                <div class="meta-row">
                    <span class="meta-label">Total QR Codes</span>
                    <span class="meta-value">{{ $business->qrCodes->count() }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Total Reviews</span>
                    <span class="meta-value">{{ $business->reviews->count() }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Avg. Rating</span>
                    <span class="meta-value">
                        @php
                            $avg = $business->reviews->avg('rating');
                        @endphp
                        @if($avg)
                            {{ number_format($avg, 1) }} <i class="fas fa-star" style="color:#f59e0b; font-size:11px;"></i>
                        @else
                            <span style="color:#cbd5e1;">—</span>
                        @endif
                    </span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Warnings</span>
                    <span class="meta-value" style="color:{{ $usage['warnings'] > 0 ? '#d97706' : '#059669' }};">{{ $usage['warnings'] }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Status</span>
                    <span class="meta-value" style="color:{{ $usage['is_blocked'] ? '#dc2626' : '#059669' }};">
                        {{ $usage['is_blocked'] ? 'Blocked' : ucfirst($business->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- WARN MODAL --}}
<div class="modal-overlay" id="warn-modal">
    <div class="modal-box">
        <h3 class="modal-title">⚠️ Send Warning</h3>
        <p class="modal-desc">This warning will be sent to <strong>{{ $business->user->name ?? 'the owner' }}</strong> for <strong>{{ $business->name }}</strong>.</p>

        <form method="POST" action="{{ route('admin.businesses.warn', $business) }}" id="warn-form">
            @csrf
            <label class="modal-label">Severity</label>
            <select name="severity" class="modal-select" style="margin-bottom:14px;" required>
                <option value="low">Low — Minor issue</option>
                <option value="medium" selected>Medium — Needs attention</option>
                <option value="high">High — Serious violation</option>
            </select>

            <label class="modal-label">Reason</label>
            <textarea name="reason" class="modal-textarea" placeholder="Explain why you're warning this business..." required></textarea>
            <p class="modal-error" id="warn-error">Please enter a reason.</p>

            <div class="modal-actions">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="closeWarnModal()">Cancel</button>
                <button type="submit" class="modal-btn modal-btn-warn"><i class="fas fa-paper-plane"></i> Send Warning</button>
            </div>
        </form>
    </div>
</div>

{{-- BLOCK MODAL --}}
<div class="modal-overlay" id="block-modal">
    <div class="modal-box">
        <h3 class="modal-title">🚫 Block Business</h3>
        <p class="modal-desc">This will immediately block <strong>{{ $business->name }}</strong>. The owner will be notified.</p>

        <form method="POST" action="{{ route('admin.businesses.block', $business) }}" id="block-form-actual">
            @csrf
            <label class="modal-label">Reason for blocking</label>
            <textarea name="block_reason" class="modal-textarea" placeholder="Why are you blocking this business?" required></textarea>
            <p class="modal-error" id="block-error">Please enter a reason.</p>

            <div class="modal-actions">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="closeBlockModal()">Cancel</button>
                <button type="submit" class="modal-btn modal-btn-block"><i class="fas fa-ban"></i> Block Business</button>
            </div>
        </form>
    </div>
</div>

{{-- TOAST --}}
<div class="toast" id="toast"></div>

@endsection

@push('scripts')
<script>
// Warn Modal
function openWarnModal() {
    document.getElementById('warn-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeWarnModal() {
    document.getElementById('warn-modal').classList.remove('open');
    document.body.style.overflow = '';
}

// Block Modal
function openBlockModal() {
    document.getElementById('block-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeBlockModal() {
    document.getElementById('block-modal').classList.remove('open');
    document.body.style.overflow = '';
}

// Close on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('open');
            document.body.style.overflow = '';
        }
    });
});

// Close on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.open').forEach(m => {
            m.classList.remove('open');
        });
        document.body.style.overflow = '';
    }
});

// Form validation visual
document.getElementById('warn-form').addEventListener('submit', function(e) {
    const reason = this.querySelector('[name="reason"]').value.trim();
    if (!reason) {
        e.preventDefault();
        document.getElementById('warn-error').style.display = 'block';
    } else {
        document.getElementById('warn-error').style.display = 'none';
    }
});

document.getElementById('block-form-actual').addEventListener('submit', function(e) {
    const reason = this.querySelector('[name="block_reason"]').value.trim();
    if (!reason) {
        e.preventDefault();
        document.getElementById('block-error').style.display = 'block';
    } else {
        document.getElementById('block-error').style.display = 'none';
    }
});

// Toast for flash messages
@if(session('success'))
    setTimeout(() => showToast('{{ session("success") }}', 'success'), 300);
@endif

function showToast(msg, type) {
    const toast = document.getElementById('toast');
    toast.textContent = msg;
    toast.className = 'toast ' + type;
    requestAnimationFrame(() => toast.classList.add('show'));
    setTimeout(() => toast.classList.remove('show'), 3500);
}
</script>
@endpush