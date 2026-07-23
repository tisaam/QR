@extends('layouts.admin')

@section('title', 'Create Coupon')

@section('content')
<style>
    .coupon-page { font-family: Arial, sans-serif; color: #1f2937; }
    .coupon-header { margin-bottom: 1.25rem; }
    .coupon-back { color: #4f46e5; text-decoration: none; font-size: 0.95rem; font-weight: 600; }
    .coupon-back:hover { color: #3730a3; }
    .coupon-title { font-size: 1.5rem; font-weight: 700; margin: 0.5rem 0 0; color: #111827; }
    .coupon-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.8rem; padding: 1.25rem; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
    .coupon-form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
    .coupon-field { display: flex; flex-direction: column; gap: 0.35rem; }
    .coupon-label { font-size: 0.92rem; font-weight: 600; color: #374151; }
    .coupon-input, .coupon-select, .coupon-textarea { width: 100%; border: 1px solid #d1d5db; border-radius: 0.6rem; padding: 0.7rem 0.8rem; font-size: 0.95rem; box-sizing: border-box; }
    .coupon-input:focus, .coupon-select:focus, .coupon-textarea:focus { outline: none; border-color: #4f46e5; box-shadow: 0 0 0 2px rgba(79,70,229,0.15); }
    .coupon-toggle { display: flex; align-items: center; gap: 0.6rem; margin-top: 1rem; }
    .coupon-actions { display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.25rem; }
    .btn-secondary, .btn-primary { padding: 0.7rem 1rem; border-radius: 0.6rem; text-decoration: none; font-weight: 600; border: 1px solid #d1d5db; background: #fff; color: #374151; }
    .btn-primary { background: #4f46e5; color: #fff; border-color: #4f46e5; }
    .btn-primary:hover { background: #4338ca; }
    .helper-text { font-size: 0.82rem; color: #6b7280; }
    @media (max-width: 768px) { .coupon-form-grid { grid-template-columns: 1fr; } .coupon-actions { justify-content: stretch; flex-direction: column; } .btn-secondary, .btn-primary { width: 100%; text-align: center; } }
/* ===========================================================
   COUPON CREATE / EDIT PAGE - DARK MODE ONLY
   (Light mode remains EXACTLY the same)
=========================================================== */

body:not(.light-mode) .coupon-page{
    color: var(--child-text);
}

/* Header */

body:not(.light-mode) .coupon-back{
    color:#818cf8;
}

body:not(.light-mode) .coupon-back:hover{
    color:#a5b4fc;
}

body:not(.light-mode) .coupon-title{
    color:var(--child-text);
}

/* Card */

body:not(.light-mode) .coupon-card{
    background:var(--child-bg);
    border:1px solid var(--child-border);
    box-shadow:0 10px 25px rgba(0,0,0,.35);
}

/* Labels */

body:not(.light-mode) .coupon-label{
    color:var(--child-text);
}

/* Inputs */

body:not(.light-mode) .coupon-input,
body:not(.light-mode) .coupon-select,
body:not(.light-mode) .coupon-textarea{
    background:var(--child-bg);
    color:var(--child-text);
    border:1px solid var(--child-border);
}

body:not(.light-mode) .coupon-input::placeholder,
body:not(.light-mode) .coupon-textarea::placeholder{
    color:var(--child-text-sec);
}

body:not(.light-mode) .coupon-input:focus,
body:not(.light-mode) .coupon-select:focus,
body:not(.light-mode) .coupon-textarea:focus{
    outline:none;
    border-color:var(--accent-glow);
    box-shadow:0 0 0 3px var(--accent-glow-soft);
}

/* Select */

body:not(.light-mode) .coupon-select option{
    background:#111827;
    color:#f8fafc;
}

/* Toggle */

body:not(.light-mode) .coupon-toggle{
    color:var(--child-text);
}

body:not(.light-mode) .coupon-toggle input[type="checkbox"]{
    accent-color:var(--accent-glow);
}

/* Buttons */

body:not(.light-mode) .btn-secondary{
    background:rgba(255,255,255,.03);
    color:var(--child-text);
    border:1px solid var(--child-border);
}

body:not(.light-mode) .btn-secondary:hover{
    background:rgba(255,255,255,.08);
}

body:not(.light-mode) .btn-primary{
    background:#4f46e5;
    color:#fff;
    border-color:#4f46e5;
}

body:not(.light-mode) .btn-primary:hover{
    background:#4338ca;
}

/* Helper Text */

body:not(.light-mode) .helper-text{
    color:var(--child-text-sec);
}
</style>

<div class="coupon-page">
    <div class="coupon-header">
        <a href="{{ route('admin.coupons.index') }}" class="coupon-back">
            <i class="fas fa-arrow-left"></i> Back to Coupons
        </a>
        <h1 class="coupon-title">Create New Coupon</h1>
    </div>

    <div class="coupon-card">
        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf
            <div class="coupon-form-grid">
                <div class="coupon-field">
                    <label class="coupon-label">Coupon Code *</label>
                    <input type="text" name="code" required class="coupon-input" placeholder="e.g., DIWALI50">
                </div>

                <div class="coupon-field">
                    <label class="coupon-label">Display Name *</label>
                    <input type="text" name="name" required class="coupon-input" placeholder="e.g., Diwali 50% Off">
                </div>

                <div class="coupon-field">
                    <label class="coupon-label">Discount Type *</label>
                    <select name="discount_type" id="discount_type" required class="coupon-select">
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount (₹)</option>
                    </select>
                </div>

                <div class="coupon-field">
                    <label class="coupon-label">Discount Value *</label>
                    <input type="number" name="discount_value" step="0.01" min="0" required class="coupon-input" placeholder="e.g., 50">
                </div>

                <div class="coupon-field" id="max-discount-field">
                    <label class="coupon-label">Max Discount (₹) <span class="helper-text">(For %)</span></label>
                    <input type="number" name="max_discount" step="0.01" min="0" class="coupon-input" placeholder="e.g., 200">
                </div>

                <div class="coupon-field">
                    <label class="coupon-label">Min Order Amount (₹)</label>
                    <input type="number" name="min_order_amount" step="0.01" min="0" value="0" class="coupon-input">
                </div>

                <div class="coupon-field">
                    <label class="coupon-label">Total Usage Limit <span class="helper-text">(Leave blank for unlimited)</span></label>
                    <input type="number" name="usage_limit" min="1" class="coupon-input" placeholder="e.g., 100">
                </div>

                <div class="coupon-field">
                    <label class="coupon-label">Applicable Plan <span class="helper-text">(Optional)</span></label>
                    <select name="plan_id" class="coupon-select">
                        <option value="">All Plans</option>
                        @foreach(\App\Models\Plan::where('is_active', true)->get() as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} (₹{{ $plan->price }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="coupon-field">
                    <label class="coupon-label">Valid From *</label>
                    <input type="date" name="valid_from" required class="coupon-input">
                </div>

                <div class="coupon-field">
                    <label class="coupon-label">Valid Until *</label>
                    <input type="date" name="valid_until" required class="coupon-input">
                </div>
            </div>

            <div class="coupon-toggle">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                <label for="is_active">Coupon is active immediately</label>
            </div>

            <div class="coupon-actions">
                <a href="{{ route('admin.coupons.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Create Coupon</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Simple JS to hide/show Max Discount field based on Discount Type
    const discountType = document.getElementById('discount_type');
    const maxDiscountField = document.getElementById('max-discount-field');
    
    function toggleMaxDiscount() {
        if (discountType.value === 'percentage') {
            maxDiscountField.style.display = 'block';
        } else {
            maxDiscountField.style.display = 'none';
        }
    }

    discountType.addEventListener('change', toggleMaxDiscount);
    toggleMaxDiscount(); // Run on page load
</script>
@endpush