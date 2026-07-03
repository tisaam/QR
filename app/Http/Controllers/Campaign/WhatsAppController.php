<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsAppController extends Controller
{
    public function index()
    {
        // Note: Protected by 'subscription:whatsapp' middleware in routes
        $business = Auth::user()->business;
        $messages = $business->whatsappMessages()->latest()->paginate(15);
        
        return view('campaign.whatsapp.index', compact('messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'customer_phone' => 'required|string',
            'customer_name' => 'nullable|string',
            'qr_slug' => 'nullable|string|exists:qr_codes,slug',
        ]);

        $business = Auth::user()->business;
        $whatsappService = app(WhatsAppService::class);

        try {
            $message = $whatsappService->sendReviewRequest(
                $business,
                $request->customer_phone,
                $request->customer_name,
                $request->qr_slug
            );

            return back()->with('success', 'WhatsApp message sent successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send message: ' . $e->getMessage());
        }
    }

    public function bulkSend(Request $request)
    {
        $request->validate([
            'customers' => 'required|array',
            'customers.*.phone' => 'required|string',
        ]);

        $business = Auth::user()->business;
        $whatsappService = app(WhatsAppService::class);

        $results = $whatsappService->sendBulkReviewRequests($business, $request->customers);

        return response()->json([
            'success' => true,
            'sent_count' => count($results)
        ]);
    }
}