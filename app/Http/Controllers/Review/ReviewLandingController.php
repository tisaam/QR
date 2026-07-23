<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\QRCode;
use App\Services\Google\GooglePlaceService;
use Illuminate\Http\Request;

class ReviewLandingController extends Controller
{
    // Jab QR scan hoga toh ye page khulega
    public function show($slug)
    {
        // Slug ke basis par QR code dhundho
        $qrCode = QRCode::where('slug', $slug)->firstOrFail();

        // Customer form view return karo
        return view('reviews.customer-form', compact('qrCode'));
    }

       public function storeFeedback(Request $request, $slug)
    {
        die('Ye Review wala controller chal raha hai');
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