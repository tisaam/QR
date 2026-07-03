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

        $currentPlan = auth()->user()->activeSubscription?->plan;

        return view('subscription.plans', compact('plans', 'currentPlan'));
    }

    public function show(Plan $plan)
    {
        return response()->json($plan);
    }
}