@extends('layouts.app')

@section('title', 'SMS Campaign')

@section('content')
<style>
.sms-page { margin: 0 -24px; }
.sms-grid { display: grid; gap: 24px; grid-template-columns: 1fr; }
@media (min-width: 1024px) { .sms-grid { grid-template-columns: 320px minmax(0, 1fr); } }
.sms-card { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 20px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06); padding: 28px; }
.sms-card h2, .sms-card h3 { margin: 0 0 18px; font-size: 20px; font-weight: 700; color: #111827; }
.sms-card h2 i, .sms-card h3 i { margin-right: 10px; }
.sms-form label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #374151; }
.sms-form input[type="text"], .sms-form textarea { width: 100%; border: 1px solid #d1d5db; border-radius: 14px; padding: 12px 14px; font-size: 15px; color: #111827; background: #ffffff; outline: none; transition: border-color 0.2s ease, box-shadow 0.2s ease; }
.sms-form input[type="text"]:focus, .sms-form textarea:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12); }
.sms-form .field-group { margin-bottom: 20px; }
.sms-button { width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 10px; padding: 14px 18px; border: none; border-radius: 14px; background: #2563eb; color: #ffffff; font-size: 15px; font-weight: 700; cursor: pointer; transition: background 0.2s ease; }
.sms-button:hover { background: #1d4ed8; }
.sms-table { width: 100%; border-collapse: collapse; font-size: 14px; color: #4b5563; }
.sms-table th, .sms-table td { padding: 16px 14px; border-bottom: 1px solid #e5e7eb; }
.sms-table th { background: #f9fafb; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; font-size: 12px; text-align: left; }
.sms-table td { vertical-align: top; }
.sms-table .phone-cell { font-weight: 600; color: #111827; }
.sms-table .message-cell { max-width: 380px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.status-badge { display: inline-flex; align-items: center; justify-content: center; padding: 6px 10px; border-radius: 9999px; font-size: 12px; font-weight: 700; }
.status-badge--sent { background: #d1fae5; color: #047857; }
.status-badge--failed { background: #fee2e2; color: #b91c1c; }
.sms-empty { text-align: center; padding: 40px 0; color: #9ca3af; }
</style>
<div class="sms-page">
    <div class="sms-grid">
        <div class="sms-card sms-form-card">
            <h2><i class="fas fa-sms" style="color: #2563eb;"></i>Send SMS</h2>
            <form class="sms-form" action="{{ route('sms.send') }}" method="POST">
                @csrf
                <div class="field-group">
                    <label for="customer_phone">Customer Phone *</label>
                    <input id="customer_phone" type="text" name="customer_phone" required placeholder="9876543210">
                </div>
                <div class="field-group">
                    <label for="customer_name">Customer Name</label>
                    <input id="customer_name" type="text" name="customer_name" placeholder="Rahul">
                </div>
                <button type="submit" class="sms-button">
                    <i class="fas fa-paper-plane"></i>Send SMS
                </button>
            </form>
        </div>

        <div class="sms-card sms-history-card">
            <h3>SMS History</h3>
            <table class="sms-table">
                <thead>
                    <tr>
                        <th>To</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Sent At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $msg)
                        <tr>
                            <td class="phone-cell">{{ $msg->customer_phone }}</td>
                            <td class="message-cell">{{ $msg->message_content }}</td>
                            <td>
                                <span class="status-badge {{ $msg->status === 'sent' ? 'status-badge--sent' : 'status-badge--failed' }}">
                                    {{ ucfirst($msg->status) }}
                                </span>
                            </td>
                            <td>{{ $msg->sent_at ? $msg->sent_at->diffForHumans() : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="sms-empty">No SMS sent yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection