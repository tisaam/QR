<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Services\SMS\SMSService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SMSController extends Controller
{
    public function index()
    {
        $messages = Auth::user()->business->smsMessages()->latest()->paginate(15);
        return view('campaign.sms.index', compact('messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'customer_phone' => 'required|string|max:15',
            'customer_name' => 'nullable|string',
        ]);

        $smsService = app(SMSService::class);

        try {
            $smsService->sendReviewRequest(
                Auth::user()->business,
                $request->customer_phone,
                $request->customer_name
            );
            return back()->with('success', 'SMS sent successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send SMS: ' . $e->getMessage());
        }
    }

    public function bulkSend(Request $request)
    {
        $request->validate(['customers' => 'required|array']);
        
        $smsService = app(SMSService::class);
        $results = $smsService->sendBulk(Auth::user()->business, $request->customers);

        return response()->json(['success' => true, 'sent_count' => count($results)]);
    }
}