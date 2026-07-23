<?php

namespace App\Http\Controllers;

use App\Models\AICredit;
use App\Models\AICreditLog;
use Illuminate\Support\Facades\Auth;

class AICreditController extends Controller
{
   public function index()
{
    $credit = AICredit::firstOrCreate(
        ['business_id' => Auth::user()->business->id],
        ['user_id' => Auth::id(), 'total_credits' => 0, 'used_credits' => 0, 'remaining_credits' => 0]
    );

    $logs = $credit->logs()->latest()->paginate(20);

    return view('ai-credits.index', compact('credit', 'logs'));
}

    public function purchase()
    {
        // Redirect to Razorpay to buy a pack of 100/500 AI credits
        // Implementation depends on how you want to package AI credits (one-time payments)
        return redirect()->route('plans.index')->with('info', 'Upgrade your plan to get more AI credits!');
    }
}