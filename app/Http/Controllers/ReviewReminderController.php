<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ReviewReminder;
use App\Models\QRScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewReminderController extends Controller
{
    public function index()
    {
        $reminders = ReviewReminder::where('business_id', Auth::user()->business->id)
            ->latest()
            ->paginate(15);
            
        return view('reminders.index', compact('reminders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'scan_id' => 'required|exists:qr_scans,id',
            'channel' => 'required|in:whatsapp,sms,email',
            'scheduled_at' => 'required|date|after:now',
        ]);

        $scan = QRScan::find($request->scan_id);
        
        ReviewReminder::create([
            'business_id' => Auth::user()->business->id,
            'scan_id' => $scan->id,
            'customer_phone' => $scan->location ?? 'unknown', // Adjust based on how you capture phone
            'channel' => $request->channel,
            'status' => 'pending',
            'scheduled_at' => $request->scheduled_at,
        ]);

        return back()->with('success', 'Reminder scheduled.');
    }
}