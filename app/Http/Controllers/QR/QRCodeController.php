<?php

namespace App\Http\Controllers\QR;

use App\Http\Controllers\Controller;
use App\Models\QRCode;
use App\Services\QR\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QRCodeController extends Controller
{
    public function index()
    {
        $business = Auth::user()->business;
        $qrCodes = QRCode::where('business_id', $business->id)
            ->with(['branch', 'employee'])
            ->latest()
            ->paginate(10);

        return view('qr.index', compact('qrCodes'));
    }

    public function create()
    {
        $user = auth()->user();

        if (!$user->activeSubscription) {
            return redirect()->route('plans.index')
                ->with('error', 'Please subscribe to a plan first to generate QR codes.');
        }

        $currentQrCount = $user->business->qrCodes()->count(); 
        $qrLimit = $user->activeSubscription->plan->limits['qr_codes'] ?? 0;
        $isUnlimited = ($qrLimit <= 0);

        if (!$isUnlimited && $currentQrCount >= $qrLimit) {
            return redirect()->route('plans.index')
                ->with('error', "You have reached your QR Code limit ({$qrLimit}). Please upgrade your plan.");
        }

        $branches = $user->business->branches()->orderBy('name')->get();

        return view('qr.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // ✅ PLAN LIMIT CHECK
        if (!$user->activeSubscription) {
            return redirect()->route('plans.index')
                ->with('error', 'Please subscribe to a plan first to generate QR codes.');
        }

        $currentQrCount = $user->business->qrCodes()->count(); 
        $qrLimit = $user->activeSubscription->plan->limits['qr_codes'] ?? 0;
        $isUnlimited = ($qrLimit <= 0);

        if (!$isUnlimited && $currentQrCount >= $qrLimit) {
            return back()->with('error', "You have reached your QR Code limit ({$qrLimit}). Please upgrade your plan to generate more.");
        }

        // ✅ 1. Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'identifier' => 'nullable|string|max:255',
            'destination_url' => 'required|url|max:500', 
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        // ✅ 2. SERVICE KO CALL KARO (DB Save + Image Generate dono service karega)
        $qrService = app(QRCodeService::class);
        $qrService->generate($user->business, $request->all());

        // ✅ 3. Redirect
        return redirect()->route('qr-codes.index')
            ->with('success', 'QR Code generated successfully!');
    }

    public function show(QRCode $qrCode)
    {
        if ($qrCode->business_id !== Auth::user()->business->id) abort(403);
        return view('qr.show', compact('qrCode'));
    }

    public function download(QRCode $qrCode, string $format)
    {
        if ($qrCode->business_id !== Auth::user()->business->id) abort(403);

        $qrService = app(QRCodeService::class);
        return $qrService->download($qrCode, $format);
    }

    public function bulkGenerate(Request $request)
    {
        $request->validate([
            'prefix' => 'required|string|max:10',
            'type' => 'required|in:table,room,custom',
            'start_number' => 'required|integer|min:1',
            'end_number' => 'required|integer|min:1|gte:start_number',
            'branch_id' => 'nullable|exists:branches,id',
            'destination_url' => 'required|url|max:500',
        ]);

        $business = Auth::user()->business;
        $qrService = app(QRCodeService::class);
        $qrService->generateBulk($business, $request->all());

        return redirect()->back()->with('success', 'Bulk QR Codes generated successfully!');
    }

    public function destroy(QRCode $qrCode)
    {
        if ($qrCode->business_id !== Auth::user()->business->id) abort(403);

        if ($qrCode->qr_image_path) Storage::delete('public/' . $qrCode->qr_image_path);
        if ($qrCode->qr_svg_path) Storage::delete('public/' . $qrCode->qr_svg_path);

        $qrCode->delete();

        return redirect()->route('qr-codes.index')->with('success', 'QR Code deleted.');
    }
}