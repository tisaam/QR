@extends('layouts.admin')

@section('title', 'AI Credits Management')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: var(--child-bg);
        border: 1px solid var(--child-border);
        border-radius: 16px;
        padding: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        border-radius: 16px 16px 0 0;
    }

    .stat-card.cyan::before { background: linear-gradient(90deg, #06b6d4, #22d3ee); }
    .stat-card.purple::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
    .stat-card.amber::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .stat-card.emerald::before { background: linear-gradient(90deg, #10b981, #34d399); }

    .stat-icon {
        width: 42px; height: 42px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        margin-bottom: 14px;
    }

    .stat-card.cyan .stat-icon { background: rgba(6,182,212,0.1); color: #06b6d4; }
    .stat-card.purple .stat-icon { background: rgba(139,92,246,0.1); color: #8b5cf6; }
    .stat-card.amber .stat-icon { background: rgba(245,158,11,0.1); color: #f59e0b; }
    .stat-card.emerald .stat-icon { background: rgba(16,185,129,0.1); color: #10b981; }

    .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--child-text);
        line-height: 1;
        margin-bottom: 4px;
        letter-spacing: -0.02em;
    }

    .stat-label {
        font-size: 13px;
        color: var(--child-text-sec);
        font-weight: 500;
    }

    /* Table Container */
    .table-container {
        background: var(--child-bg);
        border: 1px solid var(--child-border);
        border-radius: 16px;
        overflow: hidden;
    }

    .table-header {
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--child-border);
        flex-wrap: wrap;
        gap: 12px;
    }

    .table-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--child-text);
    }

    .btn-grant {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: linear-gradient(135deg, #06b6d4, #8b5cf6);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(6,182,212,0.25);
    }

    .btn-grant:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(6,182,212,0.35);
    }

    /* Table */
    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table thead th {
        padding: 14px 24px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--child-text-sec);
        background: rgba(128,128,128,0.03);
        border-bottom: 1px solid var(--child-border);
    }

    .modern-table tbody tr {
        border-bottom: 1px solid var(--child-border);
        transition: background 0.15s ease;
    }

    .modern-table tbody tr:last-child { border-bottom: none; }

    .modern-table tbody tr:hover {
        background: var(--accent-glow-soft);
    }

    .modern-table tbody td {
        padding: 16px 24px;
        font-size: 14px;
        color: var(--child-text);
        vertical-align: middle;
    }

    .business-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .business-avatar {
        width: 38px; height: 38px;
        border-radius: 10px;
        background: linear-gradient(135deg, #06b6d4, #8b5cf6);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 14px; color: #fff;
        flex-shrink: 0;
    }

    .business-name-text { font-weight: 600; }
    .business-email-text { font-size: 12px; color: var(--child-text-sec); margin-top: 2px; }

    .credit-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
    }

    .credit-badge.high { background: rgba(16,185,129,0.1); color: #10b981; }
    .credit-badge.medium { background: rgba(245,158,11,0.1); color: #f59e0b; }
    .credit-badge.low { background: rgba(239,68,68,0.1); color: #ef4444; }

    .progress-bar-credits {
        width: 100%;
        max-width: 120px;
        height: 6px;
        background: var(--child-border);
        border-radius: 10px;
        overflow: hidden;
        margin-top: 6px;
    }

    .progress-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    .progress-fill.high { background: linear-gradient(90deg, #10b981, #34d399); }
    .progress-fill.medium { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .progress-fill.low { background: linear-gradient(90deg, #ef4444, #f87171); }

    /* Pagination */
    .pagination-wrapper {
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid var(--child-border);
        flex-wrap: wrap;
        gap: 12px;
    }

    .pagination-info {
        font-size: 13px;
        color: var(--child-text-sec);
    }

    .pagination-links {
        display: flex;
        gap: 6px;
    }

    .pagination-links a, .pagination-links span {
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid var(--child-border);
        background: transparent;
        color: var(--child-text-sec);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .pagination-links a:hover {
        background: var(--accent-glow-soft);
        color: var(--accent-glow);
        border-color: var(--accent-glow);
    }

    .pagination-links .active {
        background: var(--accent-glow);
        color: #fff;
        border-color: var(--accent-glow);
    }

    .pagination-links .disabled {
        opacity: 0.3;
        pointer-events: none;
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(8px);
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .modal-overlay.is-active {
        opacity: 1;
        visibility: visible;
    }

    .modal-box {
        background: var(--child-bg);
        border: 1px solid var(--child-border);
        border-radius: 20px;
        width: 100%;
        max-width: 480px;
        box-shadow: 0 25px 60px var(--shadow-color);
        transform: translateY(20px) scale(0.95);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .modal-overlay.is-active .modal-box {
        transform: translateY(0) scale(1);
    }

    .modal-header {
        padding: 24px 24px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--child-text);
    }

    .modal-close {
        width: 36px; height: 36px;
        border-radius: 10px;
        border: 1px solid var(--child-border);
        background: transparent;
        color: var(--child-text-sec);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .modal-close:hover {
        background: rgba(239,68,68,0.1);
        color: #ef4444;
        border-color: rgba(239,68,68,0.2);
    }

    .modal-body { padding: 24px; }

    .form-group { margin-bottom: 20px; }

    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--child-text-sec);
        margin-bottom: 8px;
    }

    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 12px 16px;
        background: var(--bg-base);
        border: 1px solid var(--child-border);
        border-radius: 10px;
        color: var(--child-text);
        font-size: 14px;
        font-family: inherit;
        outline: none;
        transition: all 0.2s ease;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: var(--accent-glow);
        box-shadow: 0 0 0 3px var(--accent-glow-soft);
    }

    .form-select { cursor: pointer; }
    .form-textarea { min-height: 80px; resize: vertical; }

    .form-error {
        font-size: 12px;
        color: #ef4444;
        margin-top: 6px;
        display: none;
    }

    .modal-footer {
        padding: 0 24px 24px;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .btn-cancel {
        padding: 10px 20px;
        border-radius: 10px;
        border: 1px solid var(--child-border);
        background: transparent;
        color: var(--child-text-sec);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .btn-cancel:hover { background: var(--accent-glow-soft); color: var(--child-text); }

    .btn-submit {
        padding: 10px 24px;
        border-radius: 10px;
        border: none;
        background: linear-gradient(135deg, #06b6d4, #8b5cf6);
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(6,182,212,0.25);
        font-family: inherit;
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(6,182,212,0.35);
    }

    .empty-state {
        padding: 60px 24px;
        text-align: center;
    }

    .empty-icon {
        width: 64px; height: 64px;
        border-radius: 16px;
        background: var(--accent-glow-soft);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
        font-size: 24px;
        color: var(--accent-glow);
    }

    .empty-text {
        font-size: 15px;
        font-weight: 600;
        color: var(--child-text);
        margin-bottom: 4px;
    }

    .empty-sub {
        font-size: 13px;
        color: var(--child-text-sec);
    }

    @media (max-width: 768px) {
        .modern-table thead { display: none; }
        .modern-table tbody tr {
            display: block;
            padding: 16px;
            border-bottom: 1px solid var(--child-border);
        }
        .modern-table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid var(--child-border);
        }
        .modern-table tbody td:last-child { border-bottom: none; }
        .modern-table tbody td::before {
            content: attr(data-label);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--child-text-sec);
        }
    }
</style>
@endpush

@section('content')
    
    {{-- Stats Cards --}}
    <div class="stats-grid">
        <div class="stat-card cyan">
            <div class="stat-icon"><i class="fas fa-building"></i></div>
            <div class="stat-value">{{ $credits->total() }}</div>
            <div class="stat-label">Total Businesses</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-icon"><i class="fas fa-coins"></i></div>
            <div class="stat-value">{{ number_format($credits->sum('total_credits')) }}</div>
            <div class="stat-label">Total Granted</div>
        </div>
        <div class="stat-card amber">
            <div class="stat-icon"><i class="fas fa-fire"></i></div>
            <div class="stat-value">{{ number_format($credits->sum('total_credits') - $credits->sum('remaining_credits')) }}</div>
            <div class="stat-label">Total Used</div>
        </div>
        <div class="stat-card emerald">
            <div class="stat-icon"><i class="fas fa-wallet"></i></div>
            <div class="stat-value">{{ number_format($credits->sum('remaining_credits')) }}</div>
            <div class="stat-label">Total Remaining</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-container">
        <div class="table-header">
            <div class="table-title"><i class="fas fa-robot" style="margin-right: 8px; color: var(--accent-glow);"></i>AI Credit Ledger</div>
            <button class="btn-grant" onclick="openModal()">
                <i class="fas fa-plus"></i> Grant Credits
            </button>
        </div>

        @if($credits->count() > 0)
            <div style="overflow-x: auto;">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Business</th>
                            <th>Total Credits</th>
                            <th>Used</th>
                            <th>Remaining</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($credits as $credit)
                            @php
                                $used = $credit->total_credits - $credit->remaining_credits;
                                $percentage = $credit->total_credits > 0 ? round(($credit->remaining_credits / $credit->total_credits) * 100) : 0;
                                
                                if($percentage > 50) { $statusClass = 'high'; $statusText = 'Healthy'; }
                                elseif($percentage > 15) { $statusClass = 'medium'; $statusText = 'Low'; }
                                else { $statusClass = 'low'; $statusText = 'Critical'; }
                            @endphp
                            <tr>
                                <td data-label="Business">
                                    <div class="business-info">
                                        <div class="business-avatar">
                                            {{ strtoupper(substr($credit->business->name ?? 'B', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="business-name-text">{{ $credit->business->name ?? 'N/A' }}</div>
                                            <div class="business-email-text">{{ $credit->business->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Total">
                                    <span style="font-weight: 700;">{{ number_format($credit->total_credits) }}</span>
                                </td>
                                <td data-label="Used">
                                    <span style="color: var(--child-text-sec);">{{ number_format($used) }}</span>
                                </td>
                                <td data-label="Remaining">
                                    <div>
                                        <span class="credit-badge {{ $statusClass }}">
                                            <i class="fas fa-bolt"></i>
                                            {{ number_format($credit->remaining_credits) }}
                                        </span>
                                        <div class="progress-bar-credits">
                                            <div class="progress-fill {{ $statusClass }}" style="width: {{ $percentage }}%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Status">
                                    <span class="credit-badge {{ $statusClass }}" style="font-size: 12px; padding: 4px 10px;">
                                        {{ $statusText }} ({{ $percentage }}%)
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Showing {{ $credits->firstItem() }} to {{ $credits->lastItem() }} of {{ $credits->total() }} results
                </div>
                <div class="pagination-links">
                    {{ $credits->links() }}
                </div>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-robot"></i></div>
                <div class="empty-text">No AI Credits Found</div>
                <div class="empty-sub">Grant credits to businesses so they can generate AI reviews.</div>
            </div>
        @endif
    </div>

    {{-- Grant Modal --}}
    <div id="grantModal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title"><i class="fas fa-plus-circle" style="color: var(--accent-glow); margin-right: 8px;"></i>Grant AI Credits</div>
                <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
            </div>
            
            <form id="grantForm" method="POST" action="{{ route('admin.ai-credits.grant') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Select Business</label>
                        <select name="business_id" class="form-select" required>
                            <option value="">Choose a business...</option>
                            @foreach(\App\Models\Business::where('status', 'active')->orderBy('name')->get() as $biz)
                                <option value="{{ $biz->id }}" {{ old('business_id') == $biz->id ? 'selected' : '' }}>
                                    {{ $biz->name }} ({{ $biz->email }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-error">Please select a business.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Credits Amount</label>
                        <input type="number" name="amount" class="form-input" placeholder="e.g. 50" min="1" required value="{{ old('amount') }}">
                        <div class="form-error">Enter a valid amount (minimum 1).</div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Reason</label>
                        <textarea name="reason" class="form-textarea" placeholder="e.g. Monthly subscription bonus, Support gesture..." required>{{ old('reason') }}</textarea>
                        <div class="form-error">Please provide a reason.</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane" style="margin-right: 6px;"></i> Grant Now
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const modal = document.getElementById('grantModal');
    const form = document.getElementById('grantForm');

    function openModal() {
        modal.classList.add('is-active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('is-active');
        document.body.style.overflow = '';
    }

    // Close on overlay click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    // Close on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });

    // Form Submission with Validation UI
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const inputs = form.querySelectorAll('[required]');
        
        inputs.forEach(input => {
            const error = input.parentElement.querySelector('.form-error');
            if (!input.value.trim()) {
                isValid = false;
                if (error) error.style.display = 'block';
                input.style.borderColor = '#ef4444';
            } else {
                if (error) error.style.display = 'none';
                input.style.borderColor = '';
            }
        });

        if (!isValid) {
            e.preventDefault();
        } else {
            // Submit normally
            const submitBtn = form.querySelector('.btn-submit');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 6px;"></i> Granting...';
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.7';
        }
    });

    // Reset input borders on focus
    form.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(el => {
        el.addEventListener('focus', function() {
            this.style.borderColor = '';
            const error = this.parentElement.querySelector('.form-error');
            if (error) error.style.display = 'none';
        });
    });
</script>
@endpush