@extends('layouts.admin')

@section('title', 'Edit Plan')

@section('content')
    <style>
        .plan-page {
            font-family: Arial, sans-serif;
            color: #1f2937;
        }

        .plan-back {
            margin-bottom: 1rem;
        }

        .plan-back a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .plan-back a:hover {
            color: #3730a3;
        }

        .plan-form {
            display: block;
        }

        .plan-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        .plan-main {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .plan-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.8rem;
            padding: 1.25rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .plan-card h2,
        .plan-card h3 {
            margin: 0 0 0.9rem;
            color: #111827;
            font-size: 1.05rem;
        }

        .plan-card label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.35rem;
        }

        .plan-card .field-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.9rem;
        }

        .plan-card .field-col-2 {
            grid-column: 1 / -1;
        }

        .plan-card input,
        .plan-card select,
        .plan-card textarea {
            width: 100%;
            padding: 0.7rem 0.8rem;
            border: 1px solid #d1d5db;
            border-radius: 0.6rem;
            font-size: 0.95rem;
            box-sizing: border-box;
            background: #fff;
        }

        .plan-card textarea {
            min-height: 100px;
            resize: vertical;
        }

        .plan-card .hint {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 0.35rem;
        }

        .plan-card .check-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.35rem 0;
        }

        .plan-card .check-row input {
            width: auto;
        }

        .plan-btn {
            width: 100%;
            padding: 0.8rem 1rem;
            border: none;
            border-radius: 0.7rem;
            background: #4f46e5;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        .plan-btn:hover {
            background: #4338ca;
        }

        .plan-required {
            color: #ef4444;
        }

        @media (min-width: 992px) {
            .plan-layout {
                grid-template-columns: 2fr 1fr;
            }
        }

        /* ===========================================================
       PLAN CREATE / EDIT - DARK MODE ONLY
       (Light mode remains EXACTLY the same)
    =========================================================== */

        body:not(.light-mode) .plan-page {
            color: var(--child-text);
        }

        /* Back Link */

        body:not(.light-mode) .plan-back a {
            color: #818cf8;
        }

        body:not(.light-mode) .plan-back a:hover {
            color: #a5b4fc;
        }

        /* Cards */

        body:not(.light-mode) .plan-card {
            background: var(--child-bg);
            border: 1px solid var(--child-border);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .35);
        }

        /* Headings */

        body:not(.light-mode) .plan-card h2,
        body:not(.light-mode) .plan-card h3 {
            color: var(--child-text);
        }

        /* Labels */

        body:not(.light-mode) .plan-card label {
            color: var(--child-text);
        }

        /* Inputs */

        body:not(.light-mode) .plan-card input,
        body:not(.light-mode) .plan-card select,
        body:not(.light-mode) .plan-card textarea {
            background: var(--child-bg);
            color: var(--child-text);
            border: 1px solid var(--child-border);
        }

        body:not(.light-mode) .plan-card input::placeholder,
        body:not(.light-mode) .plan-card textarea::placeholder {
            color: var(--child-text-sec);
        }

        body:not(.light-mode) .plan-card input:focus,
        body:not(.light-mode) .plan-card select:focus,
        body:not(.light-mode) .plan-card textarea:focus {
            border-color: var(--accent-glow);
            box-shadow: 0 0 0 3px var(--accent-glow-soft);
            outline: none;
        }

        /* Select Arrow */

        body:not(.light-mode) .plan-card select {
            appearance: none;
        }

        /* Hint */

        body:not(.light-mode) .plan-card .hint {
            color: var(--child-text-sec);
        }

        /* Check Row */

        body:not(.light-mode) .plan-card .check-row {
            color: var(--child-text);
            border-bottom: 1px solid var(--child-border);
        }

        body:not(.light-mode) .plan-card .check-row:last-child {
            border-bottom: none;
        }

        /* Checkbox */

        body:not(.light-mode) .plan-card input[type="checkbox"] {
            accent-color: var(--accent-glow);
        }

        /* Button */

        body:not(.light-mode) .plan-btn {
            background: #4f46e5;
            color: #fff;
        }

        body:not(.light-mode) .plan-btn:hover {
            background: #4338ca;
        }

        /* Required */

        body:not(.light-mode) .plan-required {
            color: #f87171;
        }
    </style>

    <div class="plan-page">
        <div class="plan-back">
            <a href="{{ route('admin.plans.index') }}"><i class="fas fa-arrow-left"></i> Back to Plans</a>
        </div>

        <form class="plan-form" method="POST" action="{{ route('admin.plans.update', $plan) }}">
            @csrf
            @method('PUT')
            <div class="plan-layout">
                <div class="plan-main">
                    <div class="plan-card">
                        <h2>Plan Details</h2>
                        <div class="field-grid">
                            <div class="field-col-2">
                                <label>Plan Name <span class="plan-required">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $plan->name) }}" required>
                            </div>
                            <div class="field-col-2">
                                <label>Description</label>
                                <textarea name="description">{{ old('description', $plan->description) }}</textarea>
                            </div>
                            <div>
                                <label>Monthly Price (₹) <span class="plan-required">*</span></label>
                                <input type="number" step="0.01" name="price" value="{{ old('price', $plan->price) }}"
                                    required>
                            </div>
                            <div>
                                <label>Annual Price (₹)</label>
                                <input type="number" step="0.01" name="annual_price"
                                    value="{{ old('annual_price', $plan->annual_price) }}">
                            </div>
                            <div>
                                <label>Billing Cycle</label>
                                <select name="billing_cycle">
                                    <option value="monthly" {{ old('billing_cycle', $plan->billing_cycle) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="yearly" {{ old('billing_cycle', $plan->billing_cycle) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    <option value="one_time" {{ old('billing_cycle', $plan->billing_cycle) == 'one_time' ? 'selected' : '' }}>One Time</option>
                                </select>
                            </div>
                            <div>
                                <label>Trial Days</label>
                                <input type="number" name="trial_days" value="{{ old('trial_days', $plan->trial_days) }}">
                            </div>
                        </div>
                    </div>

                    <div class="plan-card">
                        <h2>Features</h2>
                        <p class="hint">Enter one feature per line.</p>
                        <textarea name="features"
                            placeholder="Custom QR Codes&#10;Google Review Integration">{{ old('features', $plan->features ? implode("\n", $plan->features) : '') }}</textarea>
                    </div>
                </div>

                <div class="plan-main">
                    <div class="plan-card">
                        <h2>Settings</h2>
                        <div class="check-row">
                            <label>Active</label>
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }}>
                        </div>
                        <div style="margin-top:0.8rem;">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $plan->sort_order) }}">
                            <p class="hint">Lower number shows first</p>
                        </div>
                    </div>

                    <div class="plan-card">
                        <h2>Limits & Addons</h2>
                        <div style="display:flex; flex-direction:column; gap:0.7rem;">
                            <div>
                                <label>QR Codes (-1 for unlimited)</label>
                                <input type="number" name="limits[qr_codes]"
                                    value="{{ old('limits.qr_codes', $plan->limits['qr_codes'] ?? 0) }}">
                            </div>
                            <div>
                                <label>Reviews / Month</label>
                                <input type="number" name="limits[reviews_per_month]"
                                    value="{{ old('limits.reviews_per_month', $plan->limits['reviews_per_month'] ?? 0) }}">
                            </div>
                            <div>
                                <label>Branches</label>
                                <input type="number" name="limits[branches]"
                                    value="{{ old('limits.branches', $plan->limits['branches'] ?? 0) }}">
                            </div>
                            <div>
                                <label>Employees</label>
                                <input type="number" name="limits[employees]"
                                    value="{{ old('limits.employees', $plan->limits['employees'] ?? 0) }}">
                            </div>
                            <div>
                                <label>AI Credits</label>
                                <input type="number" name="limits[ai_credits]"
                                    value="{{ old('limits.ai_credits', $plan->limits['ai_credits'] ?? 0) }}">
                            </div>
                            <div>
                                <label>Analytics Days</label>
                                <input type="number" name="limits[analytics_days]"
                                    value="{{ old('limits.analytics_days', $plan->limits['analytics_days'] ?? 7) }}">
                            </div>
                            <div
                                style="border-top:1px solid #e5e7eb; padding-top:0.8rem; display:flex; flex-direction:column; gap:0.45rem;">
                                <div class="check-row"><span>WhatsApp</span><input type="checkbox" name="limits.whatsapp"
                                        value="1" {{ old('limits.whatsapp', $plan->limits['whatsapp'] ?? false) ? 'checked' : '' }}></div>
                                <div class="check-row"><span>SMS</span><input type="checkbox" name="limits.sms" value="1" {{ old('limits.sms', $plan->limits['sms'] ?? false) ? 'checked' : '' }}></div>
                                <div class="check-row"><span>NFC Support</span><input type="checkbox" name="limits.nfc"
                                        value="1" {{ old('limits.nfc', $plan->limits['nfc'] ?? false) ? 'checked' : '' }}>
                                </div>
                                <div class="check-row"><span>White Label</span><input type="checkbox"
                                        name="limits.white_label" value="1" {{ old('limits.white_label', $plan->limits['white_label'] ?? false) ? 'checked' : '' }}></div>
                                <div class="check-row"><span>Custom Branding</span><input type="checkbox"
                                        name="limits.custom_branding" value="1" {{ old('limits.custom_branding', $plan->limits['custom_branding'] ?? false) ? 'checked' : '' }}></div>
                                <div class="check-row"><span>Remove Branding</span><input type="checkbox"
                                        name="limits.remove_branding" value="1" {{ old('limits.remove_branding', $plan->limits['remove_branding'] ?? false) ? 'checked' : '' }}></div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="plan-btn"><i class="fas fa-save"></i> Update Plan</button>
                </div>
            </div>
        </form>
    </div>
@endsection