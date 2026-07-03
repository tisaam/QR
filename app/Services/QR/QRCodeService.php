<?php

namespace App\Services\QR;

use App\Models\QRCode;
use App\Models\Business;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QRCodeGenerator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    public function generate(Business $business, array $data): QRCode
    {
        $slug = $this->generateUniqueSlug($data['name']);
        $landingUrl = url('/r/' . $slug);
        
        $qrCode = QRCode::create([
            'business_id' => $business->id,
            'branch_id' => $data['branch_id'] ?? null,
            'employee_id' => $data['employee_id'] ?? null,
            'name' => $data['name'],
            'slug' => $slug,
            'type' => $data['type'] ?? 'custom',
            'identifier' => $data['identifier'] ?? null,
            'landing_page_url' => $landingUrl,
            'is_active' => true,
        ]);
        
        $this->generateQRImages($qrCode, $landingUrl);
        return $qrCode->load('business');
    }

    public function generateBulk(Business $business, array $data): array
    {
        $qrCodes = [];
        $prefix = $data['prefix'] ?? 'QR';
        for ($i = $data['start_number']; $i <= $data['end_number']; $i++) {
            $identifier = $prefix . ' ' . $i;
            $qrCodes[] = $this->generate($business, [
                'name' => $identifier,
                'type' => $data['type'] ?? 'custom',
                'identifier' => $identifier,
                'branch_id' => $data['branch_id'] ?? null,
            ]);
        }
        return $qrCodes;
    }
   public function generateQRImages(QRCode $qrCode, string $url)
    {
        // Generate SVG (Does NOT require GD extension, works instantly)
        $svgPath = 'qr/' . $qrCode->slug . '.svg';
        $svgImage = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(400)
            ->margin(2)
            ->generate($url);
        Storage::put('public/' . $svgPath, $svgImage);
        $qrCode->update(['qr_svg_path' => $svgPath]);

        // For the PNG download button, we will just use the SVG for now
        // You can set the png path to the svg path so the download button doesn't break
        $qrCode->update(['qr_image_path' => $svgPath]); 
    }

    public function download(QRCode $qrCode, string $format = 'png')
    {
        $path = $format === 'svg' ? $qrCode->qr_svg_path : $qrCode->qr_image_path;
        return Storage::disk('public')->download($path, $qrCode->slug . '.' . $format);
    }

    public function recordScan(QRCode $qrCode, array $scanData): \App\Models\QRScan
    {
        $scan = \App\Models\QRScan::create([
            'qr_code_id' => $qrCode->id,
            'business_id' => $qrCode->business_id,
            'ip_address' => $scanData['ip_address'] ?? request()->ip(),
            'device_type' => $this->detectDevice(),
            'browser' => $this->detectBrowser(),
            'os' => $this->detectOS(),
            'scanned_at' => now(),
        ]);
        $qrCode->increment('scan_count');
        return $scan;
    }

    private function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name) . '-' . Str::random(6);
        while (QRCode::where('slug', $slug)->exists()) {
            $slug = Str::slug($name) . '-' . Str::random(6);
        }
        return $slug;
    }

    private function detectDevice(): string
    {
        $ua = request()->userAgent();
        if (preg_match('/Mobile/i', $ua)) return 'mobile';
        if (preg_match('/Tablet/i', $ua)) return 'tablet';
        return 'desktop';
    }

    private function detectBrowser(): string
    {
        $ua = request()->userAgent();
        if (preg_match('/Chrome/i', $ua)) return 'Chrome';
        if (preg_match('/Firefox/i', $ua)) return 'Firefox';
        if (preg_match('/Safari/i', $ua)) return 'Safari';
        return 'Other';
    }

    private function detectOS(): string
    {
        $ua = request()->userAgent();
        if (preg_match('/Android/i', $ua)) return 'Android';
        if (preg_match('/iPhone|iPad/i', $ua)) return 'iOS';
        if (preg_match('/Windows/i', $ua)) return 'Windows';
        if (preg_match('/Mac/i', $ua)) return 'macOS';
        return 'Other';
    }
}