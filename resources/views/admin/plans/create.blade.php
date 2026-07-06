@extends('layouts.admin')

@section('title', 'Create Plan')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.plans.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back to Plans
    </a>
</div>

<form method="POST" action="{{ route('admin.plans.store') }}">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Plan Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Plan Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required 
                               class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Price (₹) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', 0) }}" required 
                               class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Annual Price (₹)</label>
                        <input type="number" step="0.01" name="annual_price" value="{{ old('annual_price') }}" 
                               class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Billing Cycle</label>
                        <select name="billing_cycle" class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="monthly" {{ old('billing_cycle') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ old('billing_cycle') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            <option value="one_time" {{ old('billing_cycle') == 'one_time' ? 'selected' : '' }}>One Time</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Trial Days</label>
                        <input type="number" name="trial_days" value="{{ old('trial_days', 0) }}" 
                               class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Features</h2>
                <p class="text-sm text-gray-500 mb-3">Enter one feature per line.</p>
                <textarea name="features" rows="5" 
                          class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                          placeholder="Custom QR Codes&#10;Google Review Integration&#10;Analytics Dashboard">{{ old('features') }}</textarea>
            </div>
        </div>

        <!-- Sidebar Settings -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Settings</h2>
                <div class="space-y-4">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" 
                               class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <p class="text-xs text-gray-400 mt-1">Lower number shows first</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Limits & Addons</h2>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">QR Codes (-1 for unlimited)</label>
                        <input type="number" name="limits[qr_codes]" value="{{ old('limits.qr_codes', 0) }}" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Reviews / Month</label>
                        <input type="number" name="limits[reviews_per_month]" value="{{ old('limits.reviews_per_month', 0) }}" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Branches</label>
                        <input type="number" name="limits[branches]" value="{{ old('limits.branches', 0) }}" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Employees</label>
                        <input type="number" name="limits[employees]" value="{{ old('limits.employees', 0) }}" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">AI Credits</label>
                        <input type="number" name="limits[ai_credits]" value="{{ old('limits.ai_credits', 0) }}" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Analytics Days</label>
                        <input type="number" name="limits[analytics_days]" value="{{ old('limits.analytics_days', 7) }}" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    
                    <div class="border-t pt-3 mt-3 space-y-3">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-gray-700">WhatsApp</span>
                            <input type="checkbox" name="limits.whatsapp" value="1" {{ old('limits.whatsapp') ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded">
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-gray-700">SMS</span>
                            <input type="checkbox" name="limits.sms" value="1" {{ old('limits.sms') ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded">
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-gray-700">NFC Support</span>
                            <input type="checkbox" name="limits.nfc" value="1" {{ old('limits.nfc') ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded">
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-gray-700">White Label</span>
                            <input type="checkbox" name="limits.white_label" value="1" {{ old('limits.white_label') ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded">
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-gray-700">Custom Branding</span>
                            <input type="checkbox" name="limits.custom_branding" value="1" {{ old('limits.custom_branding') ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded">
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-gray-700">Remove Branding</span>
                            <input type="checkbox" name="limits.remove_branding" value="1" {{ old('limits.remove_branding') ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded">
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition shadow-sm">
                <i class="fas fa-save mr-2"></i>Create Plan
            </button>
        </div>
    </div>
</form>
@endsection