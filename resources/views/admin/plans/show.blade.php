@extends('layouts.admin')

@section('title', 'Plan Details - ' . $plan->name)

@section('content')
<style>
    .plan-page { font-family: Arial, sans-serif; color: #1f2937; }
    .plan-back { margin-bottom: 1rem; }
    .plan-back a { color: #4f46e5; text-decoration: none; font-weight: 600; font-size: 0.95rem; }
    .plan-back a:hover { color: #3730a3; }
    .plan-layout { display: grid; grid-template-columns: 1fr; gap: 1rem; }
    .plan-main { display: flex; flex-direction: column; gap: 1rem; }
    .plan-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.8rem; padding: 1.1rem; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
    .plan-card h2, .plan-card h3 { margin: 0 0 0.7rem; color: #111827; }
    .plan-pill { display: inline-block; padding: 0.28rem 0.6rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; }
    .plan-pill.active { background: #dcfce7; color: #166534; }
    .plan-pill.inactive { background: #f3f4f6; color: #4b5563; }
    .plan-price { font-size: 1.85rem; font-weight: 700; color: #4f46e5; }
    .plan-meta { color: #6b7280; font-size: 0.92rem; margin: 0.25rem 0 0; }
    .plan-feature-grid { display: grid; grid-template-columns: 1fr; gap: 0.6rem; }
    .plan-feature-item { display: flex; align-items: center; gap: 0.6rem; padding: 0.7rem; background: #f9fafb; border-radius: 0.65rem; }
    .plan-feature-icon { width: 1.6rem; height: 1.6rem; border-radius: 999px; background: #dcfce7; color: #166534; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem; }
    .stats-box { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; border-radius: 0.65rem; margin-bottom: 0.65rem; }
    .stats-box.indigo { background: #eef2ff; }
    .stats-box.green { background: #ecfdf5; }
    .stats-box.yellow { background: #fef3c7; }
    .stats-box .label { font-size: 0.72rem; text-transform: uppercase; font-weight: 700; }
    .stats-box .value { font-size: 1.25rem; font-weight: 700; }
    .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 0.45rem 0; border-bottom: 1px solid #f3f4f6; }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { color: #6b7280; font-size: 0.9rem; }
    .detail-value { color: #111827; font-weight: 600; font-size: 0.92rem; }
    .danger-card { border: 1px solid #fecaca; }
    .danger-btn { width: 100%; padding: 0.75rem 0.9rem; border: 1px solid #fecaca; background: #fef2f2; color: #b91c1c; border-radius: 0.65rem; font-weight: 700; cursor: pointer; }
    @media (min-width: 992px) { .plan-layout { grid-template-columns: 2fr 1fr; } .plan-feature-grid { grid-template-columns: repeat(2, 1fr); } }
</style>

<div class="plan-page">
    <div class="plan-back">
        <a href="{{ route('admin.plans.index') }}"><i class="fas fa-arrow-left"></i> Back to Plans</a>
    </div>

    <div class="plan-layout">
        <div class="plan-main">
            <div class="plan-card">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:1rem;">
                    <div>
                        <div style="display:flex; align-items:center; gap:0.6rem; margin-bottom:0.45rem; flex-wrap:wrap;">
                            <h2>{{ $plan->name }}</h2>
                            <span class="plan-pill {{ $plan->is_active ? 'active' : 'inactive' }}">{{ $plan->is_active ? 'Active' : 'Inactive' }}</span>
                        </div>
                        <p class="plan-meta">Slug: {{ $plan->slug }}</p>
                        <div style="margin:0.6rem 0 0.4rem;">
                            <span class="plan-price">₹{{ number_format($plan->price) }}</span>
                            <span style="color:#6b7280; font-weight:600;">/ {{ ucfirst($plan->billing_cycle) }}</span>
                        </div>
                        @if($plan->annual_price)
                            <p class="plan-meta"><i class="fas fa-calendar-alt"></i> Yearly Price: ₹{{ number_format($plan->annual_price) }}</p>
                        @endif
                        @if($plan->trial_days > 0)
                            <p class="plan-meta"><i class="fas fa-clock"></i> Trial Period: {{ $plan->trial_days }} Days</p>
                        @endif
                    </div>
                    <a href="{{ route('admin.plans.edit', $plan) }}" class="plan-btn" style="padding:0.6rem 0.8rem; text-decoration:none; display:inline-block;">Edit Plan</a>
                </div>
                @if($plan->description)
                    <div style="margin-top:0.9rem; padding-top:0.9rem; border-top:1px solid #e5e7eb;">
                        <p style="margin:0 0 0.25rem; color:#6b7280; font-size:0.85rem;">Description</p>
                        <p style="margin:0; color:#111827;">{{ $plan->description }}</p>
                    </div>
                @endif
            </div>

            <div class="plan-card">
                <h3><i class="fas fa-list-check" style="color:#16a34a;"></i> Plan Features</h3>
                @if($plan->features && count($plan->features) > 0)
                    <div class="plan-feature-grid">
                        @foreach($plan->features as $feature)
                            <div class="plan-feature-item">
                                <span class="plan-feature-icon"><i class="fas fa-check"></i></span>
                                <span>{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="margin:0; color:#9ca3af; text-align:center; padding:1rem 0;">No features listed for this plan.</p>
                @endif
            </div>
        </div>

        <div class="plan-main">
            <div class="plan-card">
                <h3>Statistics</h3>
                <div class="stats-box indigo">
                    <div>
                        <div class="label" style="color:#4338ca;">Total Subscribers</div>
                        <div class="value" style="color:#4338ca;">{{ $plan->subscriptions->count() }}</div>
                    </div>
                    <i class="fas fa-users" style="color:#4338ca;"></i>
                </div>
                <div class="stats-box green">
                    <div>
                        <div class="label" style="color:#166534;">Active Subscribers</div>
                        <div class="value" style="color:#166534;">{{ $plan->subscriptions->where('status', 'active')->count() }}</div>
                    </div>
                    <i class="fas fa-check-circle" style="color:#166534;"></i>
                </div>
                <div class="stats-box yellow">
                    <div>
                        <div class="label" style="color:#92400e;">Est. Monthly Revenue</div>
                        <div class="value" style="color:#92400e;">₹{{ number_format($plan->subscriptions->where('status', 'active')->count() * $plan->price) }}</div>
                    </div>
                    <i class="fas fa-rupee-sign" style="color:#92400e;"></i>
                </div>
            </div>

            <div class="plan-card">
                <h3>Limits & Access</h3>
                @php $limits = $plan->limits ?? []; @endphp
                <div class="detail-row"><span class="detail-label">QR Codes</span><span class="detail-value">{{ ($limits['qr_codes'] ?? -1) == -1 ? 'Unlimited' : ($limits['qr_codes'] ?? 0) }}</span></div>
                <div class="detail-row"><span class="detail-label">Reviews / Month</span><span class="detail-value">{{ ($limits['reviews_per_month'] ?? -1) == -1 ? 'Unlimited' : ($limits['reviews_per_month'] ?? 0) }}</span></div>
                <div class="detail-row"><span class="detail-label">Branches</span><span class="detail-value">{{ $limits['branches'] ?? 1 }}</span></div>
                <div class="detail-row"><span class="detail-label">Employees</span><span class="detail-value">{{ $limits['employees'] ?? 0 }}</span></div>
                <div class="detail-row"><span class="detail-label">AI Credits</span><span class="detail-value">{{ $limits['ai_credits'] ?? 0 }}</span></div>
                <div class="detail-row"><span class="detail-label">Analytics Days</span><span class="detail-value">{{ $limits['analytics_days'] ?? 7 }} Days</span></div>
                <div style="margin-top:0.8rem;">
                    <p style="font-size:0.75rem; text-transform:uppercase; color:#9ca3af; margin:0 0 0.4rem; font-weight:700;">Integrations</p>
                    <div class="detail-row"><span class="detail-label">WhatsApp</span><span class="detail-value">{{ ($limits['whatsapp'] ?? false) ? 'Enabled' : 'Disabled' }}</span></div>
                    <div class="detail-row"><span class="detail-label">SMS</span><span class="detail-value">{{ ($limits['sms'] ?? false) ? 'Enabled' : 'Disabled' }}</span></div>
                    <div class="detail-row"><span class="detail-label">NFC Support</span><span class="detail-value">{{ ($limits['nfc'] ?? false) ? 'Enabled' : 'Disabled' }}</span></div>
                </div>
                <div style="margin-top:0.8rem;">
                    <p style="font-size:0.75rem; text-transform:uppercase; color:#9ca3af; margin:0 0 0.4rem; font-weight:700;">Branding</p>
                    <div class="detail-row"><span class="detail-label">White Label</span><span class="detail-value">{{ ($limits['white_label'] ?? false) ? 'Yes' : 'No' }}</span></div>
                    <div class="detail-row"><span class="detail-label">Custom Branding</span><span class="detail-value">{{ ($limits['custom_branding'] ?? false) ? 'Yes' : 'No' }}</span></div>
                    <div class="detail-row"><span class="detail-label">Remove Branding</span><span class="detail-value">{{ ($limits['remove_branding'] ?? false) ? 'Yes' : 'No' }}</span></div>
                </div>
            </div>

            <div class="plan-card">
                <h3>Meta Info</h3>
                <div class="detail-row"><span class="detail-label">Sort Order</span><span class="detail-value">{{ $plan->sort_order }}</span></div>
                <div class="detail-row"><span class="detail-label">Created</span><span class="detail-value">{{ $plan->created_at->format('M d, Y') }}</span></div>
                <div class="detail-row"><span class="detail-label">Last Updated</span><span class="detail-value">{{ $plan->updated_at->diffForHumans() }}</span></div>
            </div>

            <div class="plan-card danger-card">
                <h3 style="color:#b91c1c;">Danger Zone</h3>
                <p style="margin:0 0 0.8rem; color:#6b7280; font-size:0.9rem;">Once deleted, this action cannot be undone.</p>
                <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Are you absolutely sure you want to delete this plan?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="danger-btn"><i class="fas fa-trash"></i> Delete Plan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection