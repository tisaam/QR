<?php

namespace App\Services\SMS;

use App\Models\SMSMessage;
use App\Models\Business;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    public function sendReviewRequest(Business $business, string $phone, ?string $name = null, ?string $qrSlug = null): SMSMessage
    {
        $link = $qrSlug ? url('/r/' . $qrSlug) : $business->google_review_link;
        $message = ($name ? "Hi {$name}, " : "Hello, ") . "thanks for visiting {$business->name}! Share your experience: {$link}";

        $msgRecord = SMSMessage::create(['business_id' => $business->id, 'customer_phone' => $phone, 'message_content' => $message, 'status' => 'pending']);

        try {
            // Example payload for Msg91 or similar Indian SMS API
            $response = Http::withHeaders(['authkey' => config('services.sms.api_key')])->post(config('services.sms.api_url'), [
                'sender' => config('services.sms.sender_id'), 'route' => 4, 'country' => 91,
                'sms' => [['message' => $message, 'to' => [$phone]]]
            ])->throw()->json();

            $msgRecord->update(['status' => 'sent', 'provider_message_id' => $response['message_id'] ?? null, 'sent_at' => now()]);
        } catch (\Exception $e) {
            Log::error('SMS Error: ' . $e->getMessage());
            $msgRecord->update(['status' => 'failed']);
        }
        return $msgRecord;
    }

    public function sendBulk(Business $business, array $recipients): array
    {
        $results = [];
        foreach ($recipients as $r) {
            $results[] = $this->sendReviewRequest($business, $r['phone'], $r['name'] ?? null, $r['qr_slug'] ?? null);
            usleep(100000);
        }
        return $results;
    }
}