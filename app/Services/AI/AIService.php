<?php

namespace App\Services\AI;

use App\Models\AICredit;
use App\Models\AICreditLog;
use App\Models\Business;
use App\Models\AIReviewTemplate;
use App\Models\Review;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    private $apiKey;
    private $model;

    public function __construct()
    {
        // ✅ FREE Google Gemini API Key
        $this->apiKey = env('GEMINI_API_KEY');
        // Gemini 2.0 Flash (Fastest & Free)
        $this->model  = 'gemini-2.0-flash';
    }

    public function generateReviewSuggestions(Business $business, int $rating, string $language = 'en'): array
    {
        if (!$this->hasCredits($business)) {
            throw new \Exception('Insufficient AI credits. Please upgrade your plan.');
        }

        $template = $this->getTemplate($business, $rating, $language);
        $prompt = $this->buildPrompt($business, $template, $rating, $language);
        
        $response = $this->callAI($prompt);
        $suggestions = $this->parseSuggestions($response);
        
        $this->deductCredits($business, 1, 'Review generation for rating: ' . $rating);
        
        return $suggestions;
    }

    public function generateReplyToReview(Review $review): string
    {
        $business = $review->business;
        if (!$this->hasCredits($business)) throw new \Exception('Insufficient AI credits.');

        $prompt = "You are a business owner responding to a Google review.\nBusiness: {$review->business->name}\nRating: {$review->rating} stars\nReview: {$review->review_text}\n\nWrite a professional, concise 2-3 sentence response. If rating is low, be empathetic. Return ONLY the response text.";

        $reply = $this->callAI($prompt);
        $this->deductCredits($business, 1, 'Auto-reply to review');
        return $reply;
    }

    private function buildPrompt(Business $business, AIReviewTemplate $template, int $rating, string $language): string
    {
        $langName = ['en' => 'English', 'hi' => 'Hindi', 'gu' => 'Gujarati'][$language] ?? 'English';
        return "Generate 3 different Google reviews for '{$business->name}' (Type: {$business->business_type}) in {$langName} for a {$rating}-star experience.\nInstructions: {$template->prompt_template}\nReturn ONLY a JSON array like: [{\"text\": \"...\"}, {\"text\": \"...\"}, {\"text\": \"...\"}]";
    }

    private function callAI(string $prompt): string
    {
        try {
            // ✅ Google Gemini API Format
            $response = Http::post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}",
                [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ],
                    'generationConfig' => [
                        'temperature'     => 0.7,
                        'maxOutputTokens' => 500,
                        'responseMimeType' => "application/json"
                    ]
                ]
            );

            if ($response->failed()) {
                throw new \Exception('Gemini API Error: ' . $response->body());
            }

            // ✅ Gemini Response Format
            return $response->json('candidates.0.content.parts.0.text', '');

        } catch (\Exception $e) {
            Log::error('AI Error: ' . $e->getMessage());
            // Fallback agar API fail ho jaye
            return json_encode([['text' => 'Great experience!'], ['text' => 'Excellent service!'], ['text' => 'Highly recommended!']]);
        }
    }

    private function parseSuggestions(string $response): array
    {
        $decoded = json_decode($response, true);
        if (is_array($decoded)) return array_map(fn($item) => $item['text'] ?? $item, $decoded);
        return array_filter(explode("\n", $response));
    }

    private function getTemplate(Business $business, int $rating, string $language): AIReviewTemplate
    {
        return AIReviewTemplate::where('business_id', $business->id)
            ->where('language', $language)
            ->where('min_rating', '<=', $rating)
            ->where('max_rating', '>=', $rating)
            ->where('is_active', true)
            ->first() ?? AIReviewTemplate::firstOrCreate(
                ['business_id' => $business->id, 'language' => $language, 'is_default' => true],
                ['name' => "Default", 'prompt_template' => 'Write a positive review.', 'min_rating' => 4, 'max_rating' => 5, 'is_active' => true]
            );
    }

    private function hasCredits(Business $business): bool
    {
        $credit = AICredit::where('business_id', $business->id)->first();
        return $credit && $credit->remaining_credits > 0;
    }

    private function deductCredits(Business $business, int $amount, string $reason)
    {
        $credit = AICredit::where('business_id', $business->id)->first();
        $credit->decrement('remaining_credits', $amount);
        $credit->increment('used_credits', $amount);
        AICreditLog::create(['ai_credit_id' => $credit->id, 'user_id' => $business->user_id, 'type' => 'debit', 'amount' => $amount, 'reason' => $reason]);
    }
}