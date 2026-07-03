<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSubscription
{
    public function handle(Request $request, Closure $next, $feature = null)
    {
        $user = auth()->user();
        $business = $user->business;

        if (!$business) {
            return redirect()->route('onboarding');
        }

        $subscription = $user->activeSubscription;

        if (!$subscription || !$subscription->isActive()) {
            return redirect()->route('plans.index')->with('error', 'Please subscribe to a plan to access this feature.');
        }

        // Check specific feature limits if parameter is passed (e.g., 'subscription:whatsapp')
        if ($feature) {
            $limits = $subscription->limits ?? [];
            
            if (isset($limits[$feature]) && !$limits[$feature]) {
                return redirect()->route('plans.index')->with('error', 'Upgrade your plan to access this feature.');
            }
        }

        return $next($request);
    }
}