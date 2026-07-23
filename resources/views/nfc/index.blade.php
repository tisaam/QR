@extends('layouts.app')

@section('title', 'NFC Cards')

@section('content')
<style>
    .nfc-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .nfc-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        background: #4f46e5;
        color: #ffffff;
        text-decoration: none;
        font-weight: 600;
        transition: background 0.2s ease;
    }
    .btn-primary:hover {
        background: #4338ca;
    }
    .card-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 1.5rem;
    }
    @media (min-width: 768px) {
        .card-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (min-width: 1024px) {
        .card-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    .card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 1.25rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .card-icon {
        width: 5rem;
        height: 8rem;
        background: #f3f4f6;
        border-radius: 1rem;
        border: 2px dashed #d1d5db;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }
    .card-icon i {
        font-size: 2rem;
        color: #9ca3af;
        transform: rotate(90deg);
    }
    .card-title {
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .card-meta-text {
        font-size: 0.75rem;
        color: #6b7280;
        margin: 0.25rem 0 0;
    }
    .card-link {
        font-size: 0.9375rem;
        color: #2563eb;
        margin-top: 0.5rem;
    }
    .card-summary {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-top: 1rem;
        font-size: 0.9375rem;
        color: #4b5563;
        flex-wrap: wrap;
        justify-content: center;
    }
    .card-summary i {
        margin-right: 0.25rem;
    }
    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-active {
        background: #d1fae5;
        color: #047857;
    }
    .status-inactive {
        background: #fee2e2;
        color: #b91c1c;
    }
    .card-actions {
        margin-top: 1rem;
        width: 100%;
        text-align: center;
    }
    .btn-remove {
        color: #dc2626;
        font-size: 0.9375rem;
        text-decoration: none;
        font-weight: 600;
        border: none;
        background: transparent;
        cursor: pointer;
    }
    .btn-remove:hover {
        text-decoration: underline;
    }
    .empty-state {
        grid-column: 1 / -1;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 1.25rem;
        padding: 2.5rem;
        text-align: center;
        color: #9ca3af;
    }
</style>

<div class="nfc-header">
    <h1 class="nfc-title">NFC Tap Cards</h1>
    <a href="{{ route('nfc-cards.create') }}" class="btn-primary">
        <i class="fas fa-plus"></i>Link New Card
    </a>
</div>

<div class="card-grid">
    @forelse($cards as $card)
        <div class="card">
            <div class="card-icon">
                <i class="fas fa-wifi"></i>
            </div>
            <h3 class="card-title">{{ $card->name }}</h3>
            <p class="card-meta-text">UID: {{ $card->card_uid }}</p>
            <p class="card-link">Linked to: {{ $card->qrCode->name ?? 'Deleted QR' }}</p>

            <div class="card-summary">
                <span><i class="fas fa-hand-pointer"></i>{{ $card->tap_count }} Taps</span>
                <span class="status-pill {{ $card->status === 'active' ? 'status-active' : 'status-inactive' }}">{{ ucfirst($card->status) }}</span>
            </div>

            <div class="card-actions">
                <form action="{{ route('nfc-cards.destroy', $card) }}" method="POST" onsubmit="return confirm('Remove this NFC card?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-remove">Remove Card</button>
                </form>
            </div>
        </div>
    @empty
        <div class="empty-state">
            No NFC cards linked yet.
        </div>
    @endforelse
</div>
@endsection