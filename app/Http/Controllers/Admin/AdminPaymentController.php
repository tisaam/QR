<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Payment\RazorpayService;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('user')->latest()->paginate(20);
        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        return view('admin.payments.show', compact('payment'));
    }

    public function refund(Payment $payment)
    {
        // Use RazorpayService to process refund
        // $razorpay = app(RazorpayService::class);
        // $razorpay->processRefund($payment->razorpay_payment_id);
        
        $payment->update(['status' => 'refunded']);
        return back()->with('success', 'Payment marked as refunded.');
    }
}