<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Models\EmailCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailCampaignController extends Controller
{
    public function index()
    {
        $campaigns = Auth::user()->business->emailCampaigns()->latest()->paginate(10);
        return view('campaign.email.index', compact('campaigns'));
    }

    public function create()
    {
        return view('campaign.email.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'subject' => 'required|string',
            'content' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*.email' => 'required|email',
        ]);

        $campaign = Auth::user()->business->emailCampaigns()->create([
            'name' => $request->name,
            'subject' => $request->subject,
            'content' => $request->content,
            'status' => 'draft',
        ]);

        foreach ($request->recipients as $recipient) {
            $campaign->recipients()->create([
                'email' => $recipient['email'],
                'name' => $recipient['name'] ?? null,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('campaign.email.index')->with('success', 'Campaign created.');
    }

    public function send(EmailCampaign $campaign)
    {
        if ($campaign->business_id !== Auth::user()->business->id) abort(403);

        $campaign->update(['status' => 'sending']);

        foreach ($campaign->recipients()->where('status', 'pending')->get() as $recipient) {
            // Use Laravel Mail facade here with your custom Mailable
            // Mail::to($recipient->email)->send(new ReviewRequestMail($campaign, $recipient));
            
            $recipient->update(['status' => 'sent', 'sent_at' => now()]);
            $campaign->increment('total_sent');
        }

        $campaign->update(['status' => 'completed', 'sent_at' => now()]);

        return back()->with('success', 'Campaign sent successfully!');
    }
}