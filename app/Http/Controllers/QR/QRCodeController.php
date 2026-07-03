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
        $business = Auth::user()->business;
        $branches = $business->branches;
        $employees = $business->employees ?? []; // Adjust based on your employee logic

        return view('qr.create', compact('branches', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:table,room,employee,counter,custom',
            'identifier' => 'nullable|string|max:50',
            'branch_id' => 'nullable|exists:branches,id',
            'employee_id' => 'nullable|exists:users,id',
        ]);

        $business = Auth::user()->business;
        $qrService = app(QRCodeService::class);

        $qrCode = $qrService->generate($business, $request->all());

        return redirect()->route('qr-codes.index')
            ->with('success', 'QR Code generated successfully!');
    }

    public function show(QRCode $qrCode)
    {
        // Ensure the user owns this QR code
        if ($qrCode->business_id !== Auth::user()->business->id) {
            abort(403);
        }

        return view('qr.show', compact('qrCode'));
    }

    public function download(QRCode $qrCode, string $format)
    {
        if ($qrCode->business_id !== Auth::user()->business->id) {
            abort(403);
        }

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
        ]);

        $business = Auth::user()->business;
        $qrService = app(QRCodeService::class);

        $qrService->generateBulk($business, $request->all());

        return redirect()->back()->with('success', 'Bulk QR Codes generated successfully!');
    }

    public function destroy(QRCode $qrCode)
    {
        if ($qrCode->business_id !== Auth::user()->business->id) {
            abort(403);
        }

        // Delete files from storage
        if ($qrCode->qr_image_path) Storage::delete('public/' . $qrCode->qr_image_path);
        if ($qrCode->qr_svg_path) Storage::delete('public/' . $qrCode->qr_svg_path);

        $qrCode->delete();

        return redirect()->route('qr-codes.index')->with('success', 'QR Code deleted.');
    }
}