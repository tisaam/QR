<?php

namespace App\Http\Controllers\QR;

use App\Http\Controllers\Controller;
use App\Models\QRScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QRScanController extends Controller
{
    public function index(Request $request)
    {
        $scans = QRScan::where('business_id', Auth::user()->business->id)
            ->with('qrCode')
            ->when($request->device_type, fn($q, $d) => $q->where('device_type', $d))
            ->latest('scanned_at')
            ->paginate(20);

        return view('qr.scans', compact('scans'));
    }
}