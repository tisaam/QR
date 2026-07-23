@extends('layouts.app')

@push('styles')
<style>
    /* Modern Utilities missing from your main layout */
    .max-w-2xl { max-width: 42rem; }
    .mx-auto { margin-left: auto; margin-right: auto; }
    .modern-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; }
    @media (max-width: 768px) { .modern-grid { grid-template-columns: 1fr; } }
    
    /* Modern Form Inputs */
    .form-group { position: relative; }
    .form-icon {
        position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
        color: #9ca3af; font-size: 0.875rem; pointer-events: none; transition: color 0.2s;
    }
    .form-input {
        width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; 
        background: #f9fafb; border: 1.5px solid #e5e7eb; border-radius: 0.75rem; 
        color: #1f2937; font-size: 0.9rem; transition: all 0.25s ease;
    }
    .form-input:focus {
        background: #ffffff; border-color: #3b82f6; outline: none;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .form-input:focus + .form-icon { color: #3b82f6; }
    .form-input::placeholder { color: #9ca3af; }
    
    textarea.form-input { padding-top: 1rem; }
    textarea.form-icon { top: 1.15rem; transform: none; }

    /* Modern Buttons */
    .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
        padding: 0.75rem 1.5rem; font-size: 0.875rem; font-weight: 600; 
        border-radius: 0.75rem; border: none; cursor: pointer; transition: all 0.2s ease;
    }
    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white;
        box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.35);
    }
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px 0 rgba(37, 99, 235, 0.45);
    }
    .btn-secondary {
        background: transparent; color: #4b5563; border: 1.5px solid #d1d5db;
    }
    .btn-secondary:hover { background: #f3f4f6; border-color: #9ca3af; }
    
    .error-text { color: #dc2626; font-size: 0.75rem; margin-top: 0.375rem; padding-left: 0.25rem; }
</style>
@endpush

@section('title', 'Add Branch')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <!-- Header -->
    <div class="flex items-center mb-8">
        <a href="{{ route('branches.index') }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700 mr-4 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-800">Add New Branch</h1>
            <p class="text-sm text-gray-500 mt-0.5">Fill in the details below to add a new location.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 sm:p-8">
            <form method="POST" action="{{ route('branches.store') }}">
                @csrf

                <div class="space-y-5">
                    
                    <!-- Branch Name -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Branch Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                            class="form-input"
                            placeholder="e.g. Downtown Branch">
                        <i class="fas fa-code-branch form-icon"></i>
                        @error('name')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Address</label>
                        <textarea name="address" rows="3" 
                            class="form-input"
                            placeholder="Full street address">{{ old('address') }}</textarea>
                        <i class="fas fa-map-marker-alt form-icon"></i>
                        @error('address')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- City & State Grid -->
                    <div class="modern-grid">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">City</label>
                            <input type="text" name="city" value="{{ old('city') }}" 
                                class="form-input"
                                placeholder="City name">
                            <i class="fas fa-city form-icon"></i>
                            @error('city')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">State</label>
                            <input type="text" name="state" value="{{ old('state') }}" 
                                class="form-input"
                                placeholder="State name">
                            <i class="fas fa-map form-icon"></i>
                            @error('state')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" 
                            class="form-input"
                            placeholder="+1 234 567 8900">
                        <i class="fas fa-phone-alt form-icon"></i>
                        @error('phone')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('branches.index') }}" class="btn btn-secondary mr-3">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> 
                        Save Branch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection