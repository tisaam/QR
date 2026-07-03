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
        $credit->increment('total_credits', $request->amount);
        $credit->increment('remaining_credits', $request->amount);

        AICreditLog::create([
            'ai_credit_id' => $credit->id,
            'user_id' => $credit->user_id,
            'type' => 'credit',
            'amount' => $request->amount,
            'reason' => 'Admin Grant: ' . $request->reason
        ]);

        return back()->with('success', 'Credits granted successfully.');
    }
}