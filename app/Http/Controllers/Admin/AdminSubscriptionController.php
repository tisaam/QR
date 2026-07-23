<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Notification; // <-- Added
use Illuminate\Http\Request;

class AdminSubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['user', 'plan', 'business'])->latest()->paginate(15);
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function show(Subscription $subscription)
    {
        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function cancel(Subscription $subscription)
    {
        $subscription->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        // --- Notification to Business ---
        Notification::create([
            'user_id' => $subscription->user_id,
            'type'    => 'alert',
            'title'   => 'Subscription Cancelled ⚠️',
            'message' => 'Your subscription has been forcefully cancelled by an admin. Some features may now be restricted.',
            'data'    => [
                'action_url'  => route('subscription.current'),
                'action_text' => 'View Subscription'
            ]
        ]);

        return back()->with('success', 'Subscription forcefully cancelled.');
    }

    public function extend(Request $request, Subscription $subscription)
    {
        $request->validate(['days' => 'required|integer|min:1']);
        
        $newEnd = $subscription->ends_at ? $subscription->ends_at->addDays($request->days) : now()->addDays($request->days);
        $subscription->update(['ends_at' => $newEnd, 'status' => 'active']);

        // --- Notification to Business ---
        Notification::create([
            'user_id' => $subscription->user_id,
            'type'    => 'admin_action',
            'title'   => 'Subscription Extended! ✨',
            'message' => 'An admin has extended your subscription by ' . $request->days . ' days. Your new expiry date is ' . $newEnd->format('M d, Y') . '.',
            'data'    => [
                'action_url'  => route('subscription.current'),
                'action_text' => 'View My Plan'
            ]
        ]);

        return back()->with('success', 'Subscription extended by ' . $request->days . ' days.');
    }
}