@extends('layouts.app')

@section('title', 'NFC Cards')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">NFC Tap Cards</h1>
    <a href="{{ route('nfc-cards.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
        <i class="fas fa-plus mr-2"></i>Link New Card
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($cards as $card)
        <div class="bg-white rounded-xl shadow-sm border p-6 flex flex-col items-center text-center">
            <div class="w-20 h-32 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center mb-4">
                <i class="fas fa-wifi text-4xl text-gray-400 rotate-90"></i>
            </div>
            <h3 class="font-bold text-gray-800">{{ $card->name }}</h3>
            <p class="text-xs text-gray-400 mt-1">UID: {{ $card->card_uid }}</p>
            <p class="text-sm text-blue-600 mt-2">Linked to: {{ $card->qrCode->name ?? 'Deleted QR' }}</p>
            
            <div class="flex items-center gap-6 mt-4 text-sm text-gray-600">
                <span><i class="fas fa-hand-pointer mr-1"></i> {{ $card->tap_count }} Taps</span>
                <span class="px-2 py-1 rounded-full text-xs {{ $card->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($card->status) }}</span>
            </div>

            <form action="{{ route('nfc-cards.destroy', $card) }}" method="POST" class="mt-4 text-red-500 text-sm hover:underline" onsubmit="return confirm('Remove this NFC card?')">
                @csrf @method('DELETE')
                <button type="submit">Remove Card</button>
            </form>
        </div>
    @empty
        <div class="col-span-3 bg-white rounded-xl border p-10 text-center text-gray-400">
            No NFC cards linked yet.
        </div>
    @endforelse
</div>
@endsection