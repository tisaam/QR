@extends('layouts.app')

@section('title', 'AI Credits')

@push('styles')
<style>
    .credit-page { max-width: 48rem; margin: 0 auto; padding: 1.5rem 1rem; }
    .credit-card {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 1.25rem; padding: 2rem; color: #fff;
        position: relative; overflow: hidden; margin-bottom: 1.5rem;
    }
    .credit-card::before {
        content: ''; position: absolute; top: -50%; right: -20%;
        width: 300px; height: 300px; border-radius: 50%; background: rgba(255,255,255,0.08);
    }
    .credit-label { font-size: 0.8rem; opacity: 0.8; font-weight: 500; margin-bottom: 0.5rem; }
    .credit-number { font-size: 3rem; font-weight: 800; line-height: 1; position: relative; z-index: 1; }
    .credit-stats { display: flex; gap: 2rem; margin-top: 1.25rem; position: relative; z-index: 1; }
    .credit-stat-label { font-size: 0.7rem; opacity: 0.7; }
    .credit-stat-value { font-size: 1rem; font-weight: 700; }
    .section-card {
        background: #fff; border: 1px solid #e5e7eb; border-radius: 1rem; overflow: hidden; margin-bottom: 1.5rem;
    }
    .section-header {
        padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6;
        font-size: 0.9rem; font-weight: 700; color: #111827;
        display: flex; justify-content: space-between; align-items: center;
    }
    .history-row {
        display: flex; align-items: flex-start; gap: 1rem; padding: 0.85rem 1.25rem;
        border-bottom: 1px solid #f8fafc; font-size: 0.85rem;
    }
    .history-row:last-child { border-bottom: none; }
    .history-icon {
        width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 12px;
    }
    .history-icon.credit { background: #dcfce7; color: #16a34a; }
    .history-icon.debit { background: #fee2e2; color: #dc2626; }
    .history-text { color: #4b5563; line-height: 1.5; flex: 1; }
    .history-meta { color: #9ca3af; font-size: 0.75rem; white-space: nowrap; }
    .empty-history { text-align: center; padding: 2.5rem 1rem; color: #9ca3af; font-size: 0.85rem; }
</style>
@endpush

@section('content')
<div class="credit-page">

    <h1 style="font-size:1.5rem; font-weight:800; color:#111827; margin:0 0 1.25rem;">AI Credits</h1>

    <div class="credit-card">
        <div class="credit-label">Available Credits</div>
        <div class="credit-number">{{ $credit->remaining_credits }}</div>
        <div class="credit-stats">
            <div>
                <div class="credit-stat-value">{{ $credit->used_credits }}</div>
                <div class="credit-stat-label">Used</div>
            </div>
            <div>
                <div class="credit-stat-value">{{ $credit->total_credits }}</div>
                <div class="credit-stat-label">Total Granted</div>
            </div>
        </div>
    </div>

    <div class="section-card">
        <div class="section-header">
            <span>Credit History</span>
            <span style="font-size:0.75rem; color:#9ca3b8; font-weight:500;">{{ $logs->count() }} records</span>
        </div>
        @if($logs->count() > 0)
            @foreach($logs as $log)
            <div class="history-row">
                <div class="history-icon {{ $log->type }}">
                    <i class="fas fa-{{ $log->type === 'credit' ? 'plus' : 'minus' }}"></i>
                </div>
                <div style="flex:1; min-width:0;">
                    <div class="history-text">{{ $log->reason }}</div>
                    <div class="history-meta">{{ $log->created_at->diffForHumans() }}</div>
                </div>
                <div style="font-weight:700; color:{{ $log->type === 'credit' ? '#16a34a' : '#dc2626' }}; font-size:0.85rem;">
                    {{ $log->type === 'credit' ? '+' : '-' }}{{ $log->amount }}
                </div>
            </div>
            @endforeach
            <div style="padding:1rem 1.25rem;">
                {{ $logs->links() }}
            </div>
        @else
            <div class="empty-history">
                <i class="fas fa-robot" style="font-size:1.5rem; display:block; margin-bottom:0.5rem; color:#e2e8f0;"></i>
                No credit history yet.
            </div>
        @endif
    </div>

</div>
@endsection