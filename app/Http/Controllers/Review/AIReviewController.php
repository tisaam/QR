<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Models\AICredit;
use App\Models\Business;
use App\Services\AI\AIService; // ✅ Correct service path
use Illuminate\Http\Request;

class AIReviewController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'rating'      => 'required|integer|min:1|max:5',
            'language'    => 'nullable|in:en,hi,gu',
        ]);

        $language   = $request->language ?? 'en';
        $businessId = $request->business_id;
        $rating     = $request->rating;

        $business = Business::find($businessId);

        // Credit check
        $credit = AICredit::firstOrCreate(
            ['business_id' => $businessId],
            ['user_id' => $business->user_id, 'total_credits' => 0, 'used_credits' => 0, 'remaining_credits' => 0]
        );

        if (!$credit->hasCredits()) {
            return response()->json([
                'success' => false,
                'message' => 'No AI credits remaining.',
            ], 403);
        }

        try {
            // ✅ Aapka existing AIService use ho raha hai
            $aiService = app(AIService::class);
            $suggestions = $aiService->generateReviewSuggestions($business, $rating, $language);

            return response()->json([
                'success' => true,
                'reviews' => $suggestions, // ✅ Array of 3 reviews
                'credits' => $credit->fresh()->remaining_credits,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}