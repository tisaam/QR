@extends('layouts.app')

@section('title', 'Create QR Code')

@push('styles')
<style>
    .qr-page {
        max-width: 48rem;
        margin: 0 auto;
        padding: 0 1rem;
    }
    .qr-heading {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1.5rem;
    }
    .card-panel {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 1.25rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .card-panel h2 {
        margin: 0 0 1rem;
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.5rem;
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
        .form-grid-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }
    /* Makes the URL input span the full width of the 2-column grid */
    .full-width-span {
        grid-column: 1 / -1;
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
    .form-select {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        color: #111827;
        background: #ffffff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
    .btn-primary,
    .btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        width: 100%;
    }
    @media (min-width: 768px) {
        .btn-primary, .btn-secondary {
            width: auto;
        }
    }
    .btn-primary {
        background: #2563eb;
        color: #ffffff;
    }
    .btn-primary:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }
    .btn-secondary {
        background: #7c3aed;
        color: #ffffff;
    }
    .btn-secondary:hover {
        background: #6d28d9;
        transform: translateY(-1px);
    }
    /* ==========================================================
   QR CREATE / EDIT PAGE - DARK MODE ONLY
========================================================== */

body.dark-mode .qr-page{
    color:#e5e7eb !important;
}

/* Heading */
body.dark-mode .qr-heading{
    color:#ffffff !important;
}

/* Cards */
body.dark-mode .card-panel{
    background:#1e293b !important;
    border:1px solid #334155 !important;
    box-shadow:none !important;
}

body.dark-mode .card-panel h2{
    color:#ffffff !important;
}

/* Labels */
body.dark-mode .form-label{
    color:#cbd5e1 !important;
}

/* Inputs & Select */
body.dark-mode .form-input,
body.dark-mode .form-select{
    background:#0f172a !important;
    border:1px solid #334155 !important;
    color:#f8fafc !important;
}

body.dark-mode .form-input::placeholder{
    color:#94a3b8 !important;
}

body.dark-mode .form-input:focus,
body.dark-mode .form-select:focus{
    border-color:#6366f1 !important;
    box-shadow:0 0 0 3px rgba(99,102,241,.25) !important;
    outline:none;
}

/* Select Options */
body.dark-mode .form-select option{
    background:#0f172a !important;
    color:#f8fafc !important;
}

/* Primary Button */
body.dark-mode .btn-primary{
    background:#6366f1 !important;
    color:#ffffff !important;
}

body.dark-mode .btn-primary:hover{
    background:#4f46e5 !important;
}

/* Secondary Button */
body.dark-mode .btn-secondary{
    background:#7c3aed !important;
    color:#ffffff !important;
}

body.dark-mode .btn-secondary:hover{
    background:#6d28d9 !important;
}
</style>
@endpush

@section('content')
<div class="qr-page">
    <h1 class="qr-heading">Generate QR Code</h1>

    <!-- SINGLE QR CODE -->
    <div class="card-panel">
        <h2><i class="fas fa-qrcode"></i> Single QR Code</h2>
        <form action="{{ route('qr-codes.store') }}" method="POST">
            @csrf
            <div class="form-grid form-grid-2">
                
                <!-- NEW: Destination URL Field -->
                <div class="form-group full-width-span">
                    <label class="form-label">Destination URL (Where QR goes when scanned) *</label>
                    <input type="url" name="destination_url" required class="form-input" 
                           value="https://shyaminfotech.co.in/niyatikapadiya_new" 
                           placeholder="https://example.com">
                </div>

                <div class="form-group">
                    <label class="form-label">QR Name *</label>
                    <input type="text" name="name" required class="form-input" placeholder="e.g., Table 5">
                </div>
                <div class="form-group">
                    <label class="form-label">Type *</label>
                    <select name="type" required class="form-select">
                        <option value="table">Restaurant Table</option>
                        <option value="room">Hotel Room</option>
                        <option value="counter">Billing Counter</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Identifier (Optional)</label>
                    <input type="text" name="identifier" class="form-input" placeholder="e.g., T-05">
                </div>
                <div class="form-group">
                    <label class="form-label">Branch (Optional)</label>
                    <select name="branch_id" class="form-select">
                        <option value="">Main Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="margin-top: 1.25rem;">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-plus-circle"></i> Generate QR Code
                </button>
            </div>
        </form>
    </div>

    <!-- BULK QR CODE -->
    <div class="card-panel">
        <h2 style="color:#7c3aed;"><i class="fas fa-layer-group"></i> Bulk Generate</h2>
        <form action="{{ route('qr-codes.bulk-generate') }}" method="POST">
            @csrf
            
            <!-- Bulk URL Field -->
            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label">Destination URL for all Bulk QRs *</label>
                <input type="url" name="destination_url" required class="form-input" 
                       value="https://shyaminfotech.co.in/niyatikapadiya_new" 
                       placeholder="https://example.com">
            </div>

            <div class="form-grid form-grid-4" style="align-items:end; gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Prefix</label>
                    <input type="text" name="prefix" value="Table" required class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Start Number</label>
                    <input type="number" name="start_number" value="1" min="1" required class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">End Number</label>
                    <input type="number" name="end_number" value="10" min="1" required class="form-input">
                </div>
                <div class="form-group" style="align-items:flex-end;">
                    <button type="submit" class="btn-secondary" style="width:100%;">Generate Bulk</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection