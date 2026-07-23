<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Show the current active subscription details
     */
    public function current()
    {
        $subscription = Auth::user()->activeSubscription;
        return view('subscription.current', compact('subscription'));
    }

    /**
     * Subscribe to a new plan
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'coupon_code' => 'nullable|string|exists:coupons,code'
        ]);

        $plan = Plan::findOrFail($request->plan_id);

        // ==========================================
        // 🔥 TESTING MODE: Bypass Razorpay
        // TODO: Remove this block when connecting real Razorpay API
        // ==========================================
        $this->activateTestPlan($plan);
        return redirect()->route('dashboard')->with('success', 'Test Mode: ' . $plan->name . ' activated successfully for 30 days!');
        // ==========================================


        /* ==========================================
        // 💳 ORIGINAL CODE (Uncomment this block when ready for real payments)
        // ==========================================
        
        $business = Auth::user()->business;

        if ($plan->price == 0) {
            $this->activateFreePlan($plan);
            return redirect()->route('dashboard')->with('success', 'Free plan activated!');
        }

        // Redirect to Razorpay payment gateway logic (handled in PaymentController)
        return redirect()->route('payment.process', [
            'plan_id' => $plan->id, 
            'coupon' => $request->coupon_code
        ]);
        
        ========================================== */
    }

    /**
     * Cancel the current active subscription
     */
    public function cancel()
    {
        $subscription = Auth::user()->activeSubscription;
        
        if (!$subscription) {
            return back()->with('error', 'No active subscription found.');
        }

        if ($subscription->razorpay_subscription_id) {
            // Cancel via Razorpay API in a real scenario
            // $razorpayService = app(RazorpayService::class);
            // $razorpayService->cancelSubscription($subscription->razorpay_subscription_id);
        }

        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);

        return back()->with('success', 'Subscription cancelled. You can use features until the billing period ends.');
    }

    /**
     * Activate a free plan (Price = 0)
     */
    private function activateFreePlan(Plan $plan)
    {
        $user = Auth::user();
        
        // Deactivate old subscriptions
        Subscription::where('user_id', $user->id)->update(['status' => 'expired']);

        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'business_id' => $user->business->id,
            'status' => 'active',
            'starts_at' => now(),
            'features' => $plan->features,
            // Note: 'limits' is NOT added here because it doesn't exist in the subscriptions table.
            // Limits are read directly from the Plan model via $user->activeSubscription->plan->limits
        ]);
    }

    /**
     * Activate a plan for testing (Bypasses Payment)
     * TODO: Delete this entire method when going live.
     */
    private function activateTestPlan(Plan $plan)
    {
        $user = Auth::user();
        
        // Deactivate old subscriptions
        Subscription::where('user_id', $user->id)->update(['status' => 'expired']);

        // Create subscription with a 30-day expiry for testing limits
        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'business_id' => $user->business->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays(30), // Gives you 30 days to test limits
            'features' => $plan->features,
        ]);
    }
}