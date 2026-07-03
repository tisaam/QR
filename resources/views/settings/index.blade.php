@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Business Settings</h1>

<div class="max-w-2xl space-y-6">
    <!-- Profile Settings -->
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h3 class="font-bold text-gray-800 mb-4">Business Profile</h3>
        <form action="{{ route('business.update', Auth::user()->business) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Business Name</label>
                    <input type="text" name="name" value="{{ Auth::user()->business->name }}" required class="w-full border rounded-lg px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ Auth::user()->business->phone }}" required class="w-full border rounded-lg px-4 py-2">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Google Review Link</label>
                    <input type="url" name="google_review_link" value="{{ Auth::user()->business->google_review_link }}" class="w-full border rounded-lg px-4 py-2" placeholder="https://search.google.com/local/writereview?placeid=...">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Logo</label>
                    <input type="file" name="logo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>
            <button type="submit" class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Save Profile</button>
        </form>
    </div>

    <!-- AI Settings -->
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h3 class="font-bold text-gray-800 mb-4">AI Review Settings</h3>
        <a href="{{ route('ai-templates.index') }}" class="block w-full text-left p-4 border rounded-lg hover:bg-gray-50 transition mb-2">
            <i class="fas fa-robot text-purple-500 mr-3"></i> Manage AI Review Templates (English, Hindi, Gujarati)
            <i class="fas fa-chevron-right text-gray-400 float-right mt-1"></i>
        </a>
        <a href="{{ route('ai-credits.index') }}" class="block w-full text-left p-4 border rounded-lg hover:bg-gray-50 transition">
            <i class="fas fa-coins text-yellow-500 mr-3"></i> View AI Credit Balance & Usage
            <i class="fas fa-chevron-right text-gray-400 float-right mt-1"></i>
        </a>
    </div>
</div>
@endsection