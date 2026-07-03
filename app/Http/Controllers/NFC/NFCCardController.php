<?php

namespace App\Http\Controllers\NFC;

use App\Http\Controllers\Controller;
use App\Models\NFCCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NFCCardController extends Controller
{
    public function index()
    {
        $cards = Auth::user()->business->nfcCards()->latest()->paginate(10);
        return view('nfc.index', compact('cards'));
    }

    public function create()
    {
        $qrCodes = Auth::user()->business->qrCodes()->where('is_active', true)->get();
        return view('nfc.create', compact('qrCodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'card_uid' => 'required|string|unique:nfc_cards,card_uid',
            'name' => 'required|string',
            'qr_code_id' => 'required|exists:qr_codes,id',
        ]);

        Auth::user()->business->nfcCards()->create($request->all());

        return redirect()->route('nfc-cards.index')->with('success', 'NFC Card linked successfully!');
    }

    public function destroy(NFCCard $nfcCard)
    {
        if ($nfcCard->business_id !== Auth::user()->business->id) abort(403);
        $nfcCard->delete();
        return back()->with('success', 'NFC Card removed.');
    }
}