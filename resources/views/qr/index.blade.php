@extends('layouts.app')

@section('title', 'QR Codes')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">My QR Codes</h1>
    <a href="{{ route('qr-codes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
        <i class="fas fa-plus mr-2"></i>Create QR Code
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th class="px-6 py-3">Preview</th>
                <th class="px-6 py-3">Name / Type</th>
                <th class="px-6 py-3">Scans</th>
                <th class="px-6 py-3">Reviews</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($qrCodes as $qr)
                               <tr class="bg-white border-b hover:bg-gray-50">
                    <!-- Preview Image -->
                    <td class="px-6 py-4">
                        @if($qr->qr_image_path)
                            <img src="{{ Storage::disk('public')->url($qr->qr_image_path) }}" alt="QR" class="w-10 h-10 rounded border p-1 bg-white object-contain">
                        @else
                            <div class="w-10 h-10 bg-gray-100 rounded border flex items-center justify-center text-gray-400 text-xs">None</div>
                        @endif
                    </td>
                    
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $qr->name }}</div>
                        <div class="text-xs text-gray-400">{{ strtoupper($qr->type) }} {{ $qr->identifier ? '- ' . $qr->identifier : '' }}</div>
                    </td>
                    <td class="px-6 py-4 font-semibold">{{ $qr->scan_count }}</td>
                    <td class="px-6 py-4 font-semibold text-green-600">{{ $qr->review_count }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $qr->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $qr->is_active ? 'Active' : 'Disabled' }}
                        </span>
                    </td>
                    <!-- Actions: Only JPG Download -->
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('qr-codes.download', [$qr, 'jpg']) }}" class="text-blue-600 hover:text-blue-800 text-lg" title="Download JPG">
                            <i class="fas fa-download"></i>
                        </a>
                        <form action="{{ route('qr-codes.destroy', $qr) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this QR code?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-lg"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                        No QR codes yet. <a href="{{ route('qr-codes.create') }}" class="text-blue-600 underline">Create one now</a>.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection