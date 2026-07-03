<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Services\AI\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AIReviewController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'language' => 'nullable|in:en,hi,gu',
        ]);

        $aiService = app(AIService::class);

        try {
            $suggestions = $aiService->generateReviewSuggestions(
                Auth::user()->business,
                $request->rating,
                $request->language ?? 'en'
            );

            return response()->json(['success' => true, 'suggestions' => $suggestions]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}