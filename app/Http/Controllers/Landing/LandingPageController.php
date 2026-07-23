<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\QRCode;
use App\Models\QRScan;
use App\Models\Review;
use App\Services\QR\QRCodeService;
use App\Services\AI\AIService;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function reviewPage(string $slug)
    {
        $qrCode = QRCode::where('slug', $slug)
            ->where('is_active', true)
            ->with('business')
            ->firstOrFail();

        // Record the scan using our Service
        // $scanService = app(QRCodeService::class);
        // $scan = $scanService->recordScan($qrCode, [
        //     'ip_address' => request()->ip(),
        // ]);

        // session(['current_scan_id' => $scan->id]);

        return view('landing.review-page', [
            'qrCode' => $qrCode,
            'business' => $qrCode->business,
            'scan' => null,
        ]);
    }

    public function generateReview(Request $request, string $slug)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'language' => 'nullable|in:en,hi,gu',
        ]);

        $qrCode = QRCode::where('slug', $slug)->firstOrFail();
        $aiService = app(AIService::class);

        try {
            $suggestions = $aiService->generateReviewSuggestions(
                $qrCode->business,
                $request->rating,
                $request->language ?? 'en'
            );

            // Mark scan as converted
            if ($scanId = session('current_scan_id')) {
                QRScan::where('id', $scanId)->update(['converted_to_review' => true]);
                $qrCode->increment('review_count');
            }

            return response()->json([
                'success' => true,
                'suggestions' => $suggestions,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

        public function submitReview(Request $request, string $slug)
    {
        try {
            $qrCode = QRCode::where('slug', $slug)->firstOrFail();

            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'review_text' => 'required|string|max:1000',
                'customer_name' => 'nullable|string|max:100',
            ]);

            $review = Review::create([
                'business_id' => $qrCode->business_id,
                'qr_code_id' => $qrCode->id,
                'rating' => $validated['rating'],
                'review_text' => $validated['review_text'],
                'customer_name' => $validated['customer_name'] ?? null,
                'source' => 'qr_scan',
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'review_id' => $review->id,
                'google_review_link' => $qrCode->business->google_review_link ?? null,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'DB Error: ' . $e->getMessage()], 500);
        }
    }
    public function markReviewPublished(Request $request, string $slug)
    {
        $request->validate(['review_id' => 'required|exists:reviews,id']);

        $review = Review::find($request->review_id);
        $review->update(['status' => 'published']);

        // Track customer loyalty here if needed

        return response()->json(['success' => true]);
    }
}