<?php

namespace App\Http\Controllers\QR;

use App\Http\Controllers\Controller;
use App\Models\QRCode;
use App\Models\NegativeReview;
use App\Models\Review;
use App\Services\Google\GooglePlaceService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ReviewLandingController extends Controller
{
    public function show($slug)
    {
        $qrCode = QRCode::where('slug', $slug)->firstOrFail();
        $qrCode->increment('scan_count'); // Scan count badhayeega

        return view('public.review-landing', compact('qrCode'));
    }

      public function storeFeedback(Request $request, $slug)
    {

     // === TEMP DEBUG — BAAD ME HATA DENA ===
    \Log::info('=== FEEDBACK DEBUG ===');
    \Log::info('Rating: ' . $request->rating);
    \Log::info('Is AJAX: ' . ($request->ajax() ? 'YES' : 'NO'));
    // === TEMP DEBUG END ===

    $qrCode = QRCode::where('slug', $slug)->firstOrFail();

    // === TEMP DEBUG ===
    \Log::info('QR ID: ' . $qrCode->id);
    \Log::info('Business ID: ' . ($qrCode->business_id ?? 'NULL'));
    \Log::info('Business exists: ' . ($qrCode->business ? 'YES' : 'NO'));
    \Log::info('Place ID: ' . ($qrCode->business->google_place_id ?? 'NULL'));
    // === TEMP DEBUG END ===

    $request->validate([
        'customer_name' => 'nullable|string|max:255',
        'rating'        => 'required|integer|min:1|max:5',
        'review_text'   => 'nullable|string|max:1000',
    ]);
        
        $qrCode = QRCode::where('slug', $slug)->firstOrFail();

        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        // 1. Main Reviews Table mein Data Save karo
        Review::create([
            'business_id'           => $qrCode->business_id,
            'branch_id'             => $qrCode->branch_id ?? null,
            'qr_code_id'            => $qrCode->id,
            'rating'                => $request->rating,
            'review_text'           => $request->review_text,
            'customer_name'         => $request->customer_name,
            'source'                => 'qr_scan',
            'status'                => 'pending',
            'is_returning_customer' => false,
        ]);

        // 2. Google Redirect Logic (Agar rating 4 ya 5 hai)
        if ($request->rating >= 4) {
            $googleService = new GooglePlaceService();
            $placeId = optional($qrCode->business)->google_place_id; 
            
            if ($placeId) {
                $googleReviewUrl = $googleService->getReviewLink($placeId);
                
                // SMART CHECK: AJAX hai ya Simple Form?
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true, 
                        'action'  => 'google_redirect', 
                        'url'     => $googleReviewUrl
                    ]);
                } else {
                    // Simple form hai toh direct redirect karo
                    return redirect()->away($googleReviewUrl);
                }
            }
        }

        // 3. Agar rating low hai (1,2,3) ya place_id nahi mila
        // SMART CHECK: AJAX hai ya Simple Form?
        if ($request->ajax()) {
            return response()->json([
                'success' => true, 
                'action'  => 'show_thank_you'
            ]);
        } else {
            // Simple form hai toh Thank you view dikhao
            // (Ye view maine pehle banaya tha, ensure karoo ye file exists: resources/views/reviews/thank-you.blade.php)
            return view('reviews.thank-you'); 
        }
    }
}