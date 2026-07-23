<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessApprovalController extends Controller
{
    public function requestApproval(Request $request)
    {
        $business = Business::where('user_id', Auth::id())->first();

        if (!$business) {
            return back()->with('error', 'No business found. Please complete onboarding first.');
        }

        if ($business->status === 'active') {
            return back()->with('info', 'Your business is already approved.');
        }

        if ($business->status === 'approval_requested') {
            return back()->with('info', 'Your approval request is already sent. Please wait for admin to review.');
        }

        if ($business->status === 'blocked') {
            return back()->with('error', 'Your business is blocked. Contact support for help.');
        }

        if ($business->status === 'suspended') {
            return back()->with('error', 'Your business is suspended. Contact support for help.');
        }

        // Update status
        $business->update(['status' => 'approval_requested']);

        // Admin ko notify
       $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type'    => 'approval_request',
                'title'   => 'QR Approval Request 📋',
                'message' => '"' . $business->name . '" is requesting access to generate QR codes.',
                'data'    => [
                    'action_url'  => route('admin.businesses.show', $business),
                    'action_text' => 'Review Request'
                ]
            ]);
        }

        // User ko confirm
        Notification::create([
            'user_id' => $business->user_id,
            'type'    => 'admin_action',
            'title'   => 'Approval Request Sent ✅',
            'message' => 'Your request to generate QR codes has been sent to the admin. You\'ll be notified once approved.',
            'data'    => [
                'action_url'  => route('dashboard'),
                'action_text' => 'Go to Dashboard'
            ]
        ]);

        return back()->with('success', 'Approval request sent successfully. We\'ll notify you once approved.');
    }
}