<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        // Safely get the current plan only if the user is logged in
        $currentPlan = null;
        if (auth()->check()) {
            $currentPlan = auth()->user()->activeSubscription?->plan;
        }

        return view('subscription.plans', compact('plans', 'currentPlan'));
    }

    public function show(Plan $plan)
    {
        return response()->json($plan);
    }
}