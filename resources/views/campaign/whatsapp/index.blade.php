@extends('layouts.app')

@section('title', 'WhatsApp Campaign')

@section('content')
<style>
.whatsapp-page { margin: 0 -24px; }
.whatsapp-grid { display: grid; gap: 24px; grid-template-columns: 1fr; }
@media (min-width: 1024px) { .whatsapp-grid { grid-template-columns: 360px minmax(0, 1fr); } }
.whatsapp-card { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 20px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06); padding: 28px; }
.whatsapp-card h2, .whatsapp-card h3 { margin: 0 0 20px; font-size: 20px; font-weight: 700; color: #111827; }
.whatsapp-card h2 i, .whatsapp-card h3 i { margin-right: 10px; }
.whatsapp-form .field-group { margin-bottom: 20px; }
.whatsapp-form label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #374151; }
.whatsapp-form input[type="text"], .whatsapp-form select { width: 100%; border: 1px solid #d1d5db; border-radius: 14px; padding: 12px 14px; font-size: 15px; color: #111827; background: #ffffff; outline: none; transition: border-color 0.2s ease, box-shadow 0.2s ease; }
.whatsapp-form input[type="text"]:focus, .whatsapp-form select:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.16); }
.whatsapp-button { width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 10px; padding: 14px 18px; border: none; border-radius: 14px; background: #16a34a; color: #ffffff; font-size: 15px; font-weight: 700; cursor: pointer; transition: background 0.2s ease; }
.whatsapp-button:hover { background: #15803d; }
.message-list { display: flex; flex-direction: column; gap: 16px; }
.message-item { display: flex; align-items: flex-start; gap: 14px; padding: 18px; border: 1px solid #e5e7eb; border-radius: 18px; }
.message-avatar { width: 42px; height: 42px; border-radius: 50%; background: #dcfce7; color: #166534; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
.message-content { flex: 1; }
.message-phone { margin: 0 0 6px; font-size: 14px; font-weight: 700; color: #111827; }
.message-phone span { font-weight: 400; color: #6b7280; }
.message-text { margin: 0; font-size: 13px; color: #4b5563; line-height: 1.6; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.message-status { display: inline-flex; align-items: center; justify-content: center; padding: 6px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; }
.status-sent { background: #dcfce7; color: #166534; }
.status-failed { background: #fee2e2; color: #b91c1c; }
.empty-message { text-align: center; color: #9ca3af; font-size: 14px; padding: 40px 0; }
</style>
<div class="whatsapp-page">
    <div class="whatsapp-grid">
        <div class="whatsapp-card whatsapp-form-card">
            <h2><i class="fab fa-whatsapp" style="color: #16a34a;"></i>Send Request</h2>
            <form class="whatsapp-form" action="{{ route('whatsapp.send') }}" method="POST">
                @csrf
                <div class="field-group">
                    <label for="customer_phone">Customer Phone *</label>
                    <input id="customer_phone" type="text" name="customer_phone" required placeholder="9876543210">
                </div>
                <div class="field-group">
                    <label for="customer_name">Customer Name</label>
                    <input id="customer_name" type="text" name="customer_name" placeholder="Rahul">
                </div>
                <div class="field-group">
                    <label for="qr_slug">Specific QR Code (Optional)</label>
                    <select id="qr_slug" name="qr_slug">
                        <option value="">Use Default Business Link</option>
                        @foreach(Auth::user()->business->qrCodes as $qr)
                            <option value="{{ $qr->slug }}">{{ $qr->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="whatsapp-button">
                    <i class="fas fa-paper-plane"></i>Send WhatsApp
                </button>
            </form>
        </div>

        <div class="whatsapp-card whatsapp-history-card">
            <h3>Message History</h3>
            <div class="message-list">
                @forelse($messages as $msg)
                    <div class="message-item">
                        <div class="message-avatar"><i class="fab fa-whatsapp"></i></div>
                        <div class="message-content">
                            <p class="message-phone">{{ $msg->customer_phone }} <span>({{ $msg->customer_name ?? 'Unknown' }})</span></p>
                            <p class="message-text">{{ Str::limit($msg->message_content, 80) }}</p>
                        </div>
                        <span class="message-status {{ $msg->status === 'sent' ? 'status-sent' : 'status-failed' }}">
                            {{ ucfirst($msg->status) }}
                        </span>
                    </div>
                @empty
                    <p class="empty-message">No messages sent yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection