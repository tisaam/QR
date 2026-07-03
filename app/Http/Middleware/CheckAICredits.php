<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AICredit;

class CheckAICredits
{
    public function handle(Request $request, Closure $next)
    {
        $business = auth()->user()->business;
        $credit = AICredit::where('business_id', $business->id)->first();

        if (!$credit || $credit->remaining_credits <= 0) {
            return response()->json([
                'success' => false, 
                'message' => 'No AI credits remaining. Please purchase more or upgrade your plan.'
            ], 403);
        }

        return $next($request);
    }
}