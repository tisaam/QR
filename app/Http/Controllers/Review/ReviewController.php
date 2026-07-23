<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\QRCode; // <-- Ye line add kari hai
use App\Services\Google\GooglePlaceService; // <-- Ye line add kari hai
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // ... Aapke purane methods (index, show, export) waise hi rahenge ...

    public function index(Request $request)
    {
        $business = Auth::user()->business;
        
        $reviews = Review::where('business_id', $business->id)
            ->with(['qrCode', 'branch', 'employee'])
            ->when($request->rating, fn($q, $r) => $q->where('rating', $r))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15);

        return view('reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        if ($review->business_id !== Auth::user()->business->id) abort(403);
        return view('reviews.show', compact('review'));
    }

    public function export()
    {
        $business = Auth::user()->business;
        $reviews = Review::where('business_id', $business->id)->get();
        return response()->json($reviews);
    }


    // ==========================================
    // NAYE METHODS QR REVIEW FLOW KE LIYE
    // ==========================================

    public function showCustomerForm(QRCode $qrCode)
    {
        // QR scan hne par yeh page khulega
        return view('reviews.customer-form', compact('qrCode'));
    }

    public function submitCustomerReview(Request $request)
    {
        // 1. Validate Data
        $request->validate([
            'qr_code_id' => 'required|exists:qr_codes,id',
            'customer_name' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        // 2. QR Code dhundo taaki business_id aur place_id mile
        $qrCode = QRCode::findOrFail($request->qr_code_id);

        // 3. Database mein Review Save karo
        Review::create([
            'business_id' => $qrCode->business_id,
            'branch_id'   => $qrCode->branch_id ?? null,
            'qr_code_id'  => $qrCode->id,
            'rating'      => $request->rating,
            'review_text' => $request->review_text,
            'customer_name'=> $request->customer_name,
            'source'      => 'qr_code',
            'status'      => 'published', // ya aap apna custom status rakh sakte hain
        ]);

        // 4. Redirect Logic (Rating 4+ par Google par bhejo)
        if ($request->rating >= 4) {
            $googleService = new GooglePlaceService();
            
            // NOTE: Yahan hum assume kar rahe hain ki aapke QRCode model mein 
            // 'place_id' naam ka column hai. Agar Business model mein hai toh 
            // $qrCode->business->place_id use karein.
            $placeId = $qrCode->place_id; 
            
            if ($placeId) {
                $googleReviewUrl = $googleService->getReviewLink($placeId);
                return redirect()->away($googleReviewUrl);
            }
        }

        // 5. Agar rating low hai (1,2,3) ya place_id nahi mila, toh Thank you page dikhao
        return view('reviews.thank-you');
    }
}