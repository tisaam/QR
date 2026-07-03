<?php

namespace App\Services\NFC;

use App\Models\NFCCard;
use App\Models\QRScan;
use App\Services\QR\QRCodeService;

class NFCService
{
    public function handleTap(string $cardUid)
    {
        $nfcCard = NFCCard::where('card_uid', $cardUid)->where('status', 'active')->firstOrFail();
        
        $qrCode = $nfcCard->qrCode;
        if (!$qrCode || !$qrCode->is_active) {
            abort(404, 'QR code linked to this NFC is inactive.');
        }

        // Increment tap count
        $nfcCard->increment('tap_count');

        // Record the scan just like a normal QR scan
        $scanService = app(QRCodeService::class);
        $scan = $scanService->recordScan($qrCode, [
            'ip_address' => request()->ip(),
        ]);

        // Redirect to the standard review landing page
        return redirect()->route('landing.review', $qrCode->slug);
    }
}