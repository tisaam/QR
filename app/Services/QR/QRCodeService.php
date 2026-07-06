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
        if (!extension_loaded('gd')) {
            throw new \Exception('PHP GD extension is missing. You cannot generate JPG files without it.');
        }

        $jpgPath = 'qr/' . $qrCode->slug . '.jpg';
        
        // Get the absolute real path on your hard drive (e.g., C:\xampp\htdocs\project\storage\app\public\qr\...)
        $absolutePath = Storage::disk('public')->path($jpgPath);

        // Make sure the folder exists
        if (!file_exists(dirname($absolutePath))) {
            mkdir(dirname($absolutePath), 0775, true);
        }

        // 1. Generate the raw PNG string
        $pngString = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
            ->size(400)
            ->margin(2)
            ->generate($url);

        // 2. Load the PNG string into PHP GD
        $img = @imagecreatefromstring($pngString);
        
        if (!$img) {
            // If GD fails to read it, just save the raw PNG data with a .jpg extension 
            // (Most image viewers will still open it correctly based on its internal data)
            file_put_contents($absolutePath, $pngString);
        } else {
            // 3. Create a white background (JPGs don't support transparency)
            $w = imagesx($img);
            $h = imagesy($img);
            $whiteBg = imagecreatetruecolor($w, $h);
            $white = imagecolorallocate($whiteBg, 255, 255, 255);
            imagefill($whiteBg, 0, 0, $white);
            
            // 4. Merge the QR code onto the white background
            imagecopy($whiteBg, $img, 0, 0, 0, 0, $w, $h);

            // 5. Write the JPG DIRECTLY to the hard drive (bypassing Laravel Storage)
            imagejpeg($whiteBg, $absolutePath, 90);

            // 6. Free up memory
            imagedestroy($img);
            imagedestroy($whiteBg);
        }

        // Update the database
        $qrCode->update([
            'qr_svg_path' => null, 
            'qr_image_path' => $jpgPath  
        ]); 
    }     
   
            public function download(QRCode $qrCode, string $format = 'jpg')
    {
        $path = $qrCode->qr_image_path;

        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404, 'QR Code file not found.');
        }

        $fullPath = Storage::disk('public')->path($path);
        $fileName = $qrCode->slug . '.jpg';

        // THIS IS THE FIX: Clear any invisible spaces/newlines that break image downloads
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Use streamDownload to safely send the pure binary image data
        return response()->streamDownload(function () use ($fullPath) {
            readfile($fullPath);
        }, $fileName, [
            'Content-Type' => 'image/jpeg',
            'Content-Length' => filesize($fullPath),
        ]);
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