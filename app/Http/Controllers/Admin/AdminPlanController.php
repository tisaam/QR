<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('sort_order')->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'annual_price' => 'nullable|numeric|min:0',
            'trial_days' => 'nullable|integer|min:0',
            'billing_cycle' => 'required|in:monthly,yearly,one_time',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'features' => 'nullable|string',
        ]);

        // Process features from newline-separated string to array
        $features = null;
        if ($request->filled('features')) {
            $features = array_filter(array_map('trim', explode("\n", $request->features)));
        }

        // Process limits
        $limits = [
            'qr_codes' => $request->limits['qr_codes'] ?? 0,
            'reviews_per_month' => $request->limits['reviews_per_month'] ?? 0,
            'branches' => $request->limits['branches'] ?? 0,
            'employees' => $request->limits['employees'] ?? 0,
            'ai_credits' => $request->limits['ai_credits'] ?? 0,
            'analytics_days' => $request->limits['analytics_days'] ?? 7,
            'whatsapp' => $request->has('limits.whatsapp'),
            'sms' => $request->has('limits.sms'),
            'nfc' => $request->has('limits.nfc'),
            'white_label' => $request->has('limits.white_label'),
            'custom_branding' => $request->has('limits.custom_branding'),
            'remove_branding' => $request->has('limits.remove_branding'),
        ];

        Plan::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'annual_price' => $request->annual_price,
            'trial_days' => $request->trial_days,
            'features' => $features,
            'limits' => $limits,
            'billing_cycle' => $request->billing_cycle,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully.');
    }

    public function show(Plan $plan)
    {
        $plan->loadCount('subscriptions');
        return view('admin.plans.show', compact('plan'));
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'annual_price' => 'nullable|numeric|min:0',
            'trial_days' => 'nullable|integer|min:0',
            'billing_cycle' => 'required|in:monthly,yearly,one_time',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'features' => 'nullable|string',
        ]);

        $features = null;
        if ($request->filled('features')) {
            $features = array_filter(array_map('trim', explode("\n", $request->features)));
        }

        $limits = [
            'qr_codes' => $request->limits['qr_codes'] ?? 0,
            'reviews_per_month' => $request->limits['reviews_per_month'] ?? 0,
            'branches' => $request->limits['branches'] ?? 0,
            'employees' => $request->limits['employees'] ?? 0,
            'ai_credits' => $request->limits['ai_credits'] ?? 0,
            'analytics_days' => $request->limits['analytics_days'] ?? 7,
            'whatsapp' => $request->has('limits.whatsapp'),
            'sms' => $request->has('limits.sms'),
            'nfc' => $request->has('limits.nfc'),
            'white_label' => $request->has('limits.white_label'),
            'custom_branding' => $request->has('limits.custom_branding'),
            'remove_branding' => $request->has('limits.remove_branding'),
        ];

        $plan->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'annual_price' => $request->annual_price,
            'trial_days' => $request->trial_days,
            'features' => $features,
            'limits' => $limits,
            'billing_cycle' => $request->billing_cycle,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        // Prevent deleting if it has active subscriptions
        if ($plan->subscriptions()->where('status', 'active')->exists()) {
            return back()->with('error', 'Cannot delete plan with active subscriptions.');
        }

        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted successfully.');
    }
}