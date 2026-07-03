<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppMessage;
use App\Models\Business;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private $apiUrl;
    private $accessToken;
    private $phoneNumberId;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url');
        $this->accessToken = config('services.whatsapp.access_token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
    }

    public function sendReviewRequest(Business $business, string $phone, ?string $name = null, ?string $qrSlug = null): WhatsAppMessage
    {
        $link = $qrSlug ? url('/r/' . $qrSlug) : $business->google_review_link;
        $message = ($name ? "Hi {$name}! " : "Hello! ") . "Thank you for visiting {$business->name}. Please share your experience: {$link}";

        $msgRecord = WhatsAppMessage::create(['business_id' => $business->id, 'customer_phone' => $this->formatPhone($phone), 'customer_name' => $name, 'message_content' => $message, 'status' => 'pending']);

        try {
            $response = $this->sendMessage($this->formatPhone($phone), $message);
            $msgRecord->update(['status' => 'sent', 'whatsapp_message_id' => $response['messages'][0]['id'] ?? null, 'sent_at' => now()]);
        } catch (\Exception $e) {
            Log::error('WhatsApp Error: ' . $e->getMessage());
            $msgRecord->update(['status' => 'failed']);
        }
        return $msgRecord;
    }

    public function sendBulkReviewRequests(Business $business, array $customers): array
    {
        $results = [];
        foreach ($customers as $c) {
            $results[] = $this->sendReviewRequest($business, $c['phone'], $c['name'] ?? null, $c['qr_slug'] ?? null);
            usleep(200000); // 200ms delay to prevent rate limit
        }
        return $results;
    }

    private function sendMessage(string $phone, string $message): array
    {
        return Http::withHeaders(['Authorization' => 'Bearer ' . $this->accessToken])->post($this->apiUrl . '/' . $this->phoneNumberId . '/messages', [
            'messaging_product' => 'whatsapp', 'to' => $phone, 'type' => 'text', 'text' => ['body' => $message]
        ])->throw()->json();
    }

    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);
        return strlen($phone) === 10 ? '91' . $phone : $phone;
    }
}