<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\QRCode;

class CheckQRLimit
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $subscription = $user->activeSubscription;
        
        if (!$subscription) {
            return redirect()->route('plans.index')->with('error', 'No active subscription.');
        }

        $limits = $subscription->limits ?? [];
        $qrLimit = $limits['qr_codes'] ?? 1;

        // -1 means unlimited in our PlanSeeder
        if ($qrLimit !== -1) {
            $currentCount = QRCode::where('business_id', $user->business->id)->count();

            if ($currentCount >= $qrLimit) {
                return redirect()->route('qr-codes.index')
                    ->with('error', "You have reached your QR code limit ({$qrLimit}). Please upgrade your plan.");
            }
        }

        return $next($request);
    }
}