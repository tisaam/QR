<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function current()
    {
        $subscription = Auth::user()->activeSubscription;
        return view('subscription.current', compact('subscription'));
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'coupon_code' => 'nullable|string|exists:coupons,code'
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        $business = Auth::user()->business;

        if ($plan->price == 0) {
            // Free plan activation
            $this->activateFreePlan($plan);
            return redirect()->route('dashboard')->with('success', 'Free plan activated!');
        }

        // Redirect to Razorpay payment gateway logic (handled in PaymentController)
        return redirect()->route('payment.process', [
            'plan_id' => $plan->id, 
            'coupon' => $request->coupon_code
        ]);
    }

    public function cancel()
    {
        $subscription = Auth::user()->activeSubscription;
        
        if ($subscription && $subscription->razorpay_subscription_id) {
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
            'limits' => $plan->limits,
        ]);
    }
}