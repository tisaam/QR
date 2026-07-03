@extends('layouts.admin')

@section('title', 'Create Coupon')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.coupons.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back to Coupons
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Create New Coupon</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Coupon Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Coupon Code *</label>
                    <input type="text" name="code" required class="w-full border rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase" placeholder="e.g., DIWALI50">
                </div>

                <!-- Coupon Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Display Name *</label>
                    <input type="text" name="name" required class="w-full border rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., Diwali 50% Off">
                </div>

                <!-- Discount Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type *</label>
                    <select name="discount_type" id="discount_type" required class="w-full border rounded-lg px-4 py-2 bg-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount (₹)</option>
                    </select>
                </div>

                <!-- Discount Value -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Discount Value *</label>
                    <input type="number" name="discount_value" step="0.01" min="0" required class="w-full border rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., 50">
                </div>

                <!-- Max Discount (Only for Percentage) -->
                <div id="max-discount-field">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Discount (₹) <span class="text-gray-400 font-normal">(For %)</span></label>
                    <input type="number" name="max_discount" step="0.01" min="0" class="w-full border rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., 200">
                </div>

                <!-- Min Order Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Min Order Amount (₹)</label>
                    <input type="number" name="min_order_amount" step="0.01" min="0" value="0" class="w-full border rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Usage Limit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Usage Limit <span class="text-gray-400 font-normal">(Leave blank for unlimited)</span></label>
                    <input type="number" name="usage_limit" min="1" class="w-full border rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., 100">
                </div>

                <!-- Applicable Plan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Applicable Plan <span class="text-gray-400 font-normal">(Optional)</span></label>
                    <select name="plan_id" class="w-full border rounded-lg px-4 py-2 bg-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Plans</option>
                        @foreach(\App\Models\Plan::where('is_active', true)->get() as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} (₹{{ $plan->price }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Valid From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valid From *</label>
                    <input type="date" name="valid_from" required class="w-full border rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Valid Until -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valid Until *</label>
                    <input type="date" name="valid_until" required class="w-full border rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <!-- Active Toggle -->
            <div class="mt-6 flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <label for="is_active" class="ml-2 text-sm text-gray-700">Coupon is active immediately</label>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.coupons.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                    Create Coupon
                </button>
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