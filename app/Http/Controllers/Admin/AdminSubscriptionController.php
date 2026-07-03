<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
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
        return back()->with('success', 'Subscription forcefully cancelled.');
    }

    public function extend(Request $request, Subscription $subscription)
    {
        $request->validate(['days' => 'required|integer|min:1']);
        $newEnd = $subscription->ends_at ? $subscription->ends_at->addDays($request->days) : now()->addDays($request->days);
        $subscription->update(['ends_at' => $newEnd, 'status' => 'active']);
        return back()->with('success', 'Subscription extended by ' . $request->days . ' days.');
    }
}