<?php

namespace App\Services\Payment;

use Razorpay\Api\Api;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Plan;
use App\Models\User;
use App\Models\Coupon;

class RazorpayService
{
    private $api;

    public function __construct()
    {
        $this->api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));
    }

    public function createOrder(Plan $plan, User $user, ?string $couponCode = null): array
    {
        $amount = $this->calculateAmount($plan, $couponCode);
        $order = $this->api->order->create([
            'receipt' => 'rcpt_' . uniqid(),
            'amount' => $amount * 100,
            'currency' => 'INR',
            'notes' => ['user_id' => $user->id, 'plan_id' => $plan->id],
        ]);

        return [
            'order_id' => $order['id'], 'amount' => $amount, 'currency' => 'INR',
            'key' => config('services.razorpay.key_id'), 'name' => config('app.name'),
            'description' => "Subscription: {$plan->name}",
            'prefill' => ['name' => $user->name, 'email' => $user->email],
        ];
    }

    public function verifyPayment(array $data): bool
    {
        try {
            $this->api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $data['razorpay_order_id'],
                'razorpay_payment_id' => $data['razorpay_payment_id'],
                'razorpay_signature' => $data['razorpay_signature'],
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function handleWebhook(array $payload)
    {
        $event = $payload['event'];
        if ($event === 'payment.captured') {
            $payment = $payload['payload']['payment']['entity'];
            Payment::where('razorpay_payment_id', $payment['id'])->update(['status' => 'completed']);
        }
    }

    private function calculateAmount(Plan $plan, ?string $couponCode): float
    {
        $amount = $plan->price;
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->where('is_active', true)->first();
            if ($coupon) {
                $discount = $coupon->discount_type === 'percentage' ? $amount * ($coupon->discount_value / 100) : $coupon->discount_value;
                $amount -= $coupon->max_discount ? min($discount, $coupon->max_discount) : $discount;
            }
        }
        return max($amount, 0);
    }
}