<?php

namespace App\Http\Middleware;

use App\Models\AICredit;
use App\Models\Business;
use Closure;
use Illuminate\Http\Request;

class CheckAiCredits
{
    public function handle(Request $request, Closure $next)
    {
        // Landing page se aayega, toh business_id request me hoga
        $businessId = $request->business_id ?? null;

        if ($businessId) {
            $credit = AICredit::firstOrCreate(
                ['business_id' => $businessId],
                ['user_id' => Business::find($businessId)?->user_id, 'total_credits' => 0, 'used_credits' => 0, 'remaining_credits' => 0]
            );

            if (!$credit->hasCredits()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No AI credits remaining.',
                ], 403);
            }
        }

        return $next($request);
    }
}