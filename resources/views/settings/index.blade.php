@extends('layouts.app')

@section('title', 'Settings')

@push('styles')
<style>
    .settings-page {
        max-width: 48rem;
        margin: 0 auto;
        padding: 0 1rem;
    }
    .settings-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1.5rem;
    }
    .settings-stack {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    .panel {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 1.25rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
        padding: 1.5rem;
    }
    .panel-heading {
        margin: 0 0 1rem;
        font-size: 1.125rem;
        font-weight: 700;
        color: #111827;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    @media (min-width: 768px) {
        .form-grid-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
    }
    .form-input,
    .form-select,
    .form-file {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        background: #ffffff;
        color: #111827;
        font-size: 0.95rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-input:focus,
    .form-select:focus,
    .form-file:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.75rem;
        background: #2563eb;
        color: #ffffff;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    .btn-action:hover {
        background: #1d4ed8;
    }
    .link-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        width: 100%;
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        background: #ffffff;
        color: #111827;
        text-decoration: none;
        transition: background 0.2s ease, border-color 0.2s ease;
    }
    .link-card:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }
    .link-card-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        border-radius: 0.75rem;
    }
    .link-card-text {
        flex: 1;
        margin: 0;
        font-size: 0.95rem;
        color: #111827;
        text-align: left;
    }
    .link-card-arrow {
        color: #9ca3af;
        font-size: 0.9rem;
    }
    /* ==========================================================
   DARK MODE
========================================================== */

body:not(.light-mode) .settings-page{
    color:#e5e7eb !important;
}

/* Page Title */
body:not(.light-mode) .settings-title{
    color:#f8fafc !important;
}

/* Panels */
body:not(.light-mode) .panel{
    background:#1e293b !important;
    border:1px solid #334155 !important;
    box-shadow:none !important;
}

body:not(.light-mode) .panel-heading{
    color:#f8fafc !important;
}

/* Labels */
body:not(.light-mode) .form-label{
    color:#cbd5e1 !important;
}

/* Inputs */
body:not(.light-mode) .form-input,
body:not(.light-mode) .form-select,
body:not(.light-mode) .form-file{
    background:#0f172a !important;
    border:1px solid #334155 !important;
    color:#f8fafc !important;
}

body:not(.light-mode) .form-input::placeholder{
    color:#94a3b8 !important;
}

body:not(.light-mode) .form-input:focus,
body:not(.light-mode) .form-select:focus,
body:not(.light-mode) .form-file:focus{
    border-color:#6366f1 !important;
    box-shadow:0 0 0 3px rgba(99,102,241,.25) !important;
    outline:none;
}

/* File Upload Button */
body:not(.light-mode) .form-file::file-selector-button{
    background:#334155;
    color:#f8fafc;
    border:none;
    padding:8px 14px;
    border-radius:6px;
    cursor:pointer;
}

body:not(.light-mode) .form-file::file-selector-button:hover{
    background:#475569;
}

/* Button */
body:not(.light-mode) .btn-action{
    background:#6366f1 !important;
    color:#fff !important;
}

body:not(.light-mode) .btn-action:hover{
    background:#4f46e5 !important;
}

/* Link Cards */
body:not(.light-mode) .link-card{
    background:#1e293b !important;
    border:1px solid #334155 !important;
    color:#f8fafc !important;
}

body:not(.light-mode) .link-card:hover{
    background:#273549 !important;
    border-color:#475569 !important;
}

body:not(.light-mode) .link-card-text{
    color:#f8fafc !important;
}

body:not(.light-mode) .link-card-arrow{
    color:#94a3b8 !important;
}

body:not(.light-mode) .link-card-icon{
    background:#312e81 !important;
    color:#c7d2fe !important;
}
</style>
@endpush

@section('content')
<div class="settings-page">
    <h1 class="settings-title">Business Settings</h1>

    <div class="settings-stack">
        <div class="panel">
            <h3 class="panel-heading">Business Profile</h3>
            <form action="{{ route('business.update', Auth::user()->business) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="form-grid form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Business Name</label>
                        <input type="text" name="name" value="{{ Auth::user()->business->name }}" required class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ Auth::user()->business->phone }}" required class="form-input">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Google Review Link</label>
                        <input type="url" name="google_review_link" value="{{ Auth::user()->business->google_review_link }}" class="form-input" placeholder="https://search.google.com/local/writereview?placeid=...">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Upload Logo</label>
                        <input type="file" name="logo" accept="image/*" class="form-file">
                    </div>
                </div>
                <button type="submit" class="btn-action" style="margin-top:1rem;">Save Profile</button>
            </form>
        </div>

        <div class="panel">
            <h3 class="panel-heading">AI Review Settings</h3>
            <a href="{{ route('ai-templates.index') }}" class="link-card">
                <span class="link-card-icon" style="color:#7c3aed;"><i class="fas fa-robot"></i></span>
                <p class="link-card-text">Manage AI Review Templates (English, Hindi, Gujarati)</p>
                <span class="link-card-arrow"><i class="fas fa-chevron-right"></i></span>
            </a>
            <a href="{{ route('ai-credits.index') }}" class="link-card" style="margin-top:0.75rem;">
                <span class="link-card-icon" style="color:#f59e0b;"><i class="fas fa-coins"></i></span>
                <p class="link-card-text">View AI Credit Balance & Usage</p>
                <span class="link-card-arrow"><i class="fas fa-chevron-right"></i></span>
            </a>
        </div>
    </div>
</div>
@endsection