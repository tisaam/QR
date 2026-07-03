<?php

namespace App\Services\Email;

use App\Models\EmailCampaign;
use App\Models\EmailCampaignRecipient;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReviewRequestMail; // You'll need to create this Mailable

class EmailCampaignService
{
    public function sendCampaign(EmailCampaign $campaign)
    {
        $campaign->update(['status' => 'sending']);

        $recipients = $campaign->recipients()->where('status', 'pending')->get();

        foreach ($recipients as $recipient) {
            try {
                // Note: You must create app/Mail/ReviewRequestMail.php
                Mail::to($recipient->email)->send(new ReviewRequestMail($campaign, $recipient));
                
                $recipient->update(['status' => 'sent', 'sent_at' => now()]);
                $campaign->increment('total_sent');
            } catch (\Exception $e) {
                $recipient->update(['status' => 'failed']);
            }
        }

        $campaign->update(['status' => 'completed', 'sent_at' => now()]);
    }

    public function trackOpen(string $recipientId)
    {
        $recipient = EmailCampaignRecipient::find($recipientId);
        if ($recipient && !$recipient->opened_at) {
            $recipient->update(['status' => 'opened', 'opened_at' => now()]);
            $recipient->campaign->increment('total_opened');
        }
        return response()->file(public_path('images/pixel.png')); // 1x1 transparent pixel
    }

    public function trackClick(string $recipientId)
    {
        $recipient = EmailCampaignRecipient::find($recipientId);
        if ($recipient && !$recipient->clicked_at) {
            $recipient->update(['status' => 'clicked', 'clicked_at' => now()]);
            $recipient->campaign->increment('total_clicked');
        }
        return redirect($recipient->campaign->business->google_review_link ?? '/');
    }
}