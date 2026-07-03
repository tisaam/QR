<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Payment\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        $plan = \App\Models\Plan::findOrFail($request->plan_id);
        $user = Auth::user();

        $razorpayService = app(RazorpayService::class);
        
        // Creates Razorpay Order and returns API keys to frontend
        $orderData = $razorpayService->createOrder(
            $plan, 
            $user, 
            $request->coupon
        );

        return view('payment.process', compact('orderData', 'plan'));
    }

    public function success(Request $request)
    {
        $razorpayService = app(RazorpayService::class);
        
        // Verify payment signature
        $verified = $razorpayService->verifyPayment($request->all());

        if (!$verified) {
            return redirect()->route('plans.index')->with('error', 'Payment verification failed!');
        }

        $user = Auth::user();
        $plan = \App\Models\Plan::findOrFail($request->plan_id);

        // Save Payment Record
        Payment::create([
            'user_id' => $user->id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_signature' => $request->razorpay_signature,
            'amount' => $plan->price,
            'status' => 'completed',
        ]);

        // Activate Subscription (Simplified for direct one-time payments)
        Subscription::where('user_id', $user->id)->update(['status' => 'expired']);
        
        $user->subscriptions()->create([
            'plan_id' => $plan->id,
            'business_id' => $user->business->id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(), // Or year based on plan
            'features' => $plan->features,
            'limits' => $plan->limits,
        ]);

        return redirect()->route('dashboard')->with('success', 'Payment successful! Plan upgraded.');
    }

    public function webhook(Request $request)
    {
        $razorpayService = app(RazorpayService::class);
        $razorpayService->handleWebhook($request->all());
        
        return response()->json(['status' => 'success']);
    }
}