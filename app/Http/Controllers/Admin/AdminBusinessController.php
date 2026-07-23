<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Notification;
use App\Models\Warning;
use Illuminate\Http\Request;

class AdminBusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::with([
            'user',
            'subscriptions.plan',
            'qrCodes',
            'reviews',
            'warnings',
        ])->latest()->paginate(15);

        return view('admin.businesses.index', compact('businesses'));
    }

    public function show(Business $business)
    {
        $business->load([
            'user',
            'subscriptions.plan',
            'qrCodes',
            'reviews',
            'warnings' => fn($q) => $q->latest()->take(10),
        ]);

        // Active subscription with plan
       $activeSubscription = $business->subscriptions()
    ->where('subscriptions.status', 'active')
    ->with('plan')
    ->latest()
    ->first();

        $plan = $activeSubscription?->plan;

        // Usage calculations
        $qrUsed = $business->qrCodes->count();
        $qrLimit = $plan->qr_limit ?? 0;
        $revUsed = $business->reviews->count();
        $revLimit = $plan->review_limit ?? 0;

        $usage = [
            'qr_codes'     => ['used' => $qrUsed, 'limit' => $qrLimit],
            'reviews'      => ['used' => $revUsed, 'limit' => $revLimit],
            'warnings'     => $business->warnings->count(),
            'is_blocked'   => $business->status === 'blocked',
            'block_reason' => $business->block_reason,
        ];

        return view('admin.businesses.show', compact('business', 'activeSubscription', 'plan', 'usage'));
    }

    public function approve(Business $business)
    {
        $business->update([
            'status'       => 'active',
            'block_reason' => null,
            'blocked_at'   => null,
        ]);

        Notification::create([
            'user_id' => $business->user_id,
            'type'    => 'admin_action',
            'title'   => 'Business Approved! 🎉',
            'message' => 'Your business "' . $business->name . '" has been approved by the admin.',
            'data'    => ['action_url' => route('dashboard'), 'action_text' => 'Go to Dashboard'],
        ]);

        return back()->with('success', 'Business approved successfully.');
    }

    public function reject(Business $business)
    {
        $business->update(['status' => 'rejected']);

        Notification::create([
            'user_id' => $business->user_id,
            'type'    => 'alert',
            'title'   => 'Business Rejected ⚠️',
            'message' => 'Your business "' . $business->name . '" has been rejected. Please review our terms or contact support.',
            'data'    => ['action_url' => route('settings.index'), 'action_text' => 'Contact Support'],
        ]);

        return back()->with('success', 'Business rejected.');
    }

    public function warn(Request $request, Business $business)
    {
        $validated = $request->validate([
            'reason'   => 'required|string|max:500',
            'severity' => 'required|in:low,medium,high',
        ]);

        Warning::create([
            'business_id' => $business->id,
            'user_id'     => $business->user_id,
            'given_by'    => auth()->id(),
            'reason'      => $validated['reason'],
            'severity'    => $validated['severity'],
        ]);

        $icons = ['low' => '⚠️', 'medium' => '🔶', 'high' => '🔴'];

        Notification::create([
            'user_id' => $business->user_id,
            'type'    => 'alert',
            'title'   => 'Warning from Admin ' . ($icons[$validated['severity']] ?? ''),
            'message' => $validated['reason'],
            'data'    => ['action_url' => route('settings.index'), 'action_text' => 'View Details'],
        ]);

        return back()->with('success', 'Warning sent to business owner.');
    }

    public function block(Request $request, Business $business)
    {
        $validated = $request->validate([
            'block_reason' => 'required|string|max:500',
        ]);

        $business->update([
            'status'       => 'blocked',
            'block_reason' => $validated['block_reason'],
            'blocked_at'   => now(),
        ]);

        Notification::create([
            'user_id' => $business->user_id,
            'type'    => 'alert',
            'title'   => 'Business Blocked 🚫',
            'message' => 'Your business "' . $business->name . '" has been blocked. Reason: ' . $validated['block_reason'],
            'data'    => ['action_url' => route('settings.index'), 'action_text' => 'View Details'],
        ]);

        return back()->with('success', 'Business blocked successfully.');
    }

    public function unblock(Business $business)
    {
        $business->update([
            'status'       => 'active',
            'block_reason' => null,
            'blocked_at'   => null,
        ]);

        Notification::create([
            'user_id' => $business->user_id,
            'type'    => 'admin_action',
            'title'   => 'Business Unblocked ✅',
            'message' => 'Your business "' . $business->name . '" has been unblocked. You can resume using all features.',
            'data'    => ['action_url' => route('dashboard'), 'action_text' => 'Go to Dashboard'],
        ]);

        return back()->with('success', 'Business unblocked successfully.');
    }

    public function suspend(Business $business)
    {
        $business->update(['status' => 'suspended']);

        Notification::create([
            'user_id' => $business->user_id,
            'type'    => 'alert',
            'title'   => 'Account Suspended 🚫',
            'message' => 'Your business "' . $business->name . '" has been temporarily suspended.',
            'data'    => ['action_url' => route('settings.index'), 'action_text' => 'View Details'],
        ]);

        return back()->with('success', 'Business suspended.');
    }


    // ══════════════════════════════════════
// APPROVE QR GENERATION REQUEST
// ══════════════════════════════════════
public function approveRequest(Business $business)
{
    // Sirf approval_requested status wale ko approve kar sakte
    if (!in_array($business->status, ['approval_requested', 'pending', 'rejected'])) {
        return back()->with('error', 'This business cannot be approved for QR generation.');
    }

    $business->update(['status' => 'active']);

    Notification::create([
        'user_id' => $business->user_id,
        'type'    => 'admin_action',
        'title'   => 'QR Access Approved! 🎉',
        'message' => 'Your request to generate QR codes has been approved. You can now create QR codes and subscribe to a plan.',
        'data'    => [
            'action_url'  => route('qr-codes.index'),
            'action_text' => 'Create QR Code'
        ]
    ]);

    return back()->with('success', 'QR generation access approved.');
}
}