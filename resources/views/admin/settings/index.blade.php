@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<style>
    .settings-page { font-family: Arial, sans-serif; color: #1f2937; }
    .settings-title { margin: 0 0 1rem; font-size: 1.6rem; color: #111827; }
    .settings-form { display: block; }
    .settings-stack { display: flex; flex-direction: column; gap: 1rem; }
    .settings-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.8rem; padding: 1.1rem; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
    .settings-card h2 { margin: 0 0 1rem; color: #111827; font-size: 1.05rem; }
    .settings-grid { display: grid; grid-template-columns: 1fr; gap: 0.9rem; }
    .settings-grid .full { grid-column: 1 / -1; }
    .settings-card label { display: block; font-size: 0.9rem; font-weight: 600; color: #374151; margin-bottom: 0.35rem; }
    .settings-card input, .settings-card select, .settings-card textarea {
        width: 100%; padding: 0.72rem 0.8rem; border: 1px solid #d1d5db; border-radius: 0.6rem; box-sizing: border-box; font-size: 0.95rem; background: #fff;
    }
    .settings-card textarea { min-height: 100px; resize: vertical; }
    .settings-card .thumb { margin-top: 0.6rem; height: 3rem; object-fit: contain; }
    .settings-actions { display: flex; justify-content: flex-end; }
    .settings-submit { padding: 0.8rem 1.2rem; border: none; border-radius: 0.7rem; background: #4f46e5; color: #fff; font-weight: 700; cursor: pointer; }
    .settings-submit:hover { background: #4338ca; }
    @media (min-width: 768px) { .settings-grid { grid-template-columns: repeat(2, 1fr); } }
/* ===========================
   DARK MODE
=========================== */

/* ===========================
   DARK MODE
   =========================== */

body:not(.light-mode) .settings-page{
    color:#e5e7eb !important;
}

body:not(.light-mode) .settings-title{
    color:#ffffff !important;
}

body:not(.light-mode) .settings-card{
    background:#1e293b !important;
    border:1px solid #334155 !important;
    box-shadow:none !important;
}

body:not(.light-mode) .settings-card h2{
    color:#ffffff !important;
}

body:not(.light-mode) .settings-card label{
    color:#cbd5e1 !important;
}

body:not(.light-mode) .settings-card input,
body:not(.light-mode) .settings-card select,
body:not(.light-mode) .settings-card textarea{
    background:#0f172a !important;
    border:1px solid #334155 !important;
    color:#f8fafc !important;
}

body:not(.light-mode) .settings-card input::placeholder,
body:not(.light-mode) .settings-card textarea::placeholder{
    color:#94a3b8 !important;
}

body:not(.light-mode) .settings-card input:focus,
body:not(.light-mode) .settings-card select:focus,
body:not(.light-mode) .settings-card textarea:focus{
    border-color:#6366f1 !important;
    box-shadow:0 0 0 3px rgba(99,102,241,.25) !important;
    outline:none;
}

body:not(.light-mode) .settings-submit{
    background:#6366f1 !important;
    color:#ffffff !important;
}

body:not(.light-mode) .settings-submit:hover{
    background:#4f46e5 !important;
}

body:not(.light-mode) .settings-card .thumb{
    background:#0f172a !important;
    border:1px solid #334155 !important;
    border-radius:8px;
    padding:4px;
}
</style>

<div class="settings-page">
    <h1 class="settings-title">Settings</h1>

    <form class="settings-form" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="settings-stack">
            <div class="settings-card">
                <h2><i class="fas fa-globe" style="color:#4f46e5;"></i> General Settings</h2>
                <div class="settings-grid">
                    <div>
                        <label>Site Name</label>
                        <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? config('app.name')) }}">
                    </div>
                    <div>
                        <label>Site URL</label>
                        <input type="url" name="site_url" value="{{ old('site_url', $settings['site_url'] ?? config('app.url')) }}">
                    </div>
                    <div class="full">
                        <label>Site Description</label>
                        <textarea name="site_description">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label>Support Email</label>
                        <input type="email" name="support_email" value="{{ old('support_email', $settings['support_email'] ?? 'support@example.com') }}">
                    </div>
                    <div>
                        <label>Currency</label>
                        <select name="currency">
                            <option value="INR" {{ ($settings['currency'] ?? 'INR') === 'INR' ? 'selected' : '' }}>INR (₹)</option>
                            <option value="USD" {{ ($settings['currency'] ?? '') === 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="EUR" {{ ($settings['currency'] ?? '') === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="settings-card">
                <h2><i class="fas fa-image" style="color:#4f46e5;"></i> Branding</h2>
                <div class="settings-grid">
                     <div>
                            <label>Site Logo</label>
                            <input type="file" name="site_logo" accept="image/*">
                            @if ($settings['site_logo'] ?? null)
                                <img src="{{ asset($settings['site_logo']) }}" alt="Logo" class="thumb">
                            @endif
                        </div>
                        <div>
                            <label>Favicon</label>
                            <input type="file" name="favicon" accept="image/*">
                            @if ($settings['favicon'] ?? null)
                                <img src="{{ asset($settings['favicon']) }}" alt="Favicon" class="thumb">
                            @endif
                        </div>
 
                </div>
            </div>

            <div class="settings-card">
                <h2><i class="fas fa-credit-card" style="color:#4f46e5;"></i> Razorpay Settings</h2>
                <div class="settings-grid">
                    <div>
                        <label>Razorpay Key ID</label>
                        <input type="text" name="razorpay_key_id" value="{{ old('razorpay_key_id', $settings['razorpay_key_id'] ?? '') }}">
                    </div>
                    <div>
                        <label>Razorpay Key Secret</label>
                        <input type="password" name="razorpay_key_secret" value="{{ old('razorpay_key_secret', $settings['razorpay_key_secret'] ?? '') }}" placeholder="Leave blank to keep current">
                    </div>
                    <div>
                        <label>Payment Mode</label>
                        <select name="razorpay_mode">
                            <option value="test" {{ ($settings['razorpay_mode'] ?? 'test') === 'test' ? 'selected' : '' }}>Test</option>
                            <option value="live" {{ ($settings['razorpay_mode'] ?? '') === 'live' ? 'selected' : '' }}>Live</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="settings-card">
                <h2><i class="fas fa-envelope" style="color:#4f46e5;"></i> Email Settings (SMTP)</h2>
                <div class="settings-grid">
                    <div>
                        <label>SMTP Host</label>
                        <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}">
                    </div>
                    <div>
                        <label>SMTP Port</label>
                        <input type="number" name="smtp_port" value="{{ old('smtp_port', $settings['smtp_port'] ?? 587) }}">
                    </div>
                    <div>
                        <label>SMTP Username</label>
                        <input type="text" name="smtp_username" value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}">
                    </div>
                    <div>
                        <label>SMTP Password</label>
                        <input type="password" name="smtp_password" value="{{ old('smtp_password', $settings['smtp_password'] ?? '') }}" placeholder="Leave blank to keep current">
                    </div>
                    <div>
                        <label>Encryption</label>
                        <select name="smtp_encryption">
                            <option value="tls" {{ ($settings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="" {{ ($settings['smtp_encryption'] ?? '') === '' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                    <div>
                        <label>From Name</label>
                        <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name'] ?? config('app.name')) }}">
                    </div>
                </div>
            </div>

            <div class="settings-actions">
                <button type="submit" class="settings-submit"><i class="fas fa-save"></i> Save Settings</button>
            </div>
        </div>
    </form>
</div>
@endsection