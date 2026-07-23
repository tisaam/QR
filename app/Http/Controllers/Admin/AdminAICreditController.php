<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AICredit;
use App\Models\AICreditLog;
use Illuminate\Http\Request;

class AdminAICreditController extends Controller
{
    public function index()
    {
        $credits = AICredit::with('business')->paginate(15);
        return view('admin.ai-credits.index', compact('credits'));
    }

   public function grant(Request $request)
{
    $request->validate([
        'business_id' => 'required|exists:businesses,id',
        'amount' => 'required|integer|min:1',
        'reason' => 'required|string'
    ]);

    $credit = AICredit::firstOrCreate(['business_id' => $request->business_id]);
    
    // user_id set karo agar nahi hai
    if (!$credit->user_id) {
        $credit->update(['user_id' => $credit->business->user_id]);
    }
    
    $credit->increment('total_credits', $request->amount);
    $credit->increment('remaining_credits', $request->amount);

    AICreditLog::create([
        'ai_credit_id' => $credit->id,
        'user_id' => $credit->user_id,
        'type' => 'credit',
        'amount' => $request->amount,
        'reason' => 'Admin Grant: ' . $request->reason
    ]);

    // ✅ NOTIFICATION ADD KIYA
    \App\Models\Notification::create([
        'user_id' => $credit->user_id,
        'type'    => 'admin_action',
        'title'   => 'AI Credits Granted 🤖',
        'message' => 'Admin has granted you ' . $request->amount . ' AI credits. Reason: ' . $request->reason,
        'data'    => [
            'action_url'  => route('ai-credits.index'),
            'action_text' => 'View Credits'
        ]
    ]);

    return back()->with('success', 'Credits granted successfully.');
}
}