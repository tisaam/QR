<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Models\AIReviewTemplate;
use Illuminate\Http\Request;

class AIReviewTemplateController extends Controller
{
    public function index()
    {
        $business = auth()->user()->business;
        if (!$business) return redirect()->route('onboarding');

        $templates = AIReviewTemplate::where('business_id', $business->id)
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();

        return view('ai-templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $business = auth()->user()->business;
        if (!$business) return back()->with('error', 'Business not found.');

        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'prompt_template' => 'required|string|max:1000',
            'language'       => 'required|in:en,hi,gu',
            'min_rating'     => 'required|integer|min:1|max:5',
            'max_rating'     => 'required|integer|min:1|max:5',
            'is_default'     => 'nullable|in:1,0',
        ]);

        $validated['business_id'] = $business->id;
        $validated['is_active'] = true;

        // Agar default hai toh purane ko hatao
        if (!empty($validated['is_default'])) {
            AIReviewTemplate::where('business_id', $business->id)
                ->update(['is_default' => false]);
        }

        // Agar pehla template hai toh automatically default
        $count = AIReviewTemplate::where('business_id', $business->id)->count();
        if ($count === 0) {
            $validated['is_default'] = true;
        } else {
            $validated['is_default'] = $validated['is_default'] ?? false;
        }

        AIReviewTemplate::create($validated);

        return back()->with('success', 'Template created.');
    }

    public function update(Request $request, AIReviewTemplate $template)
    {
        if ($template->business_id !== auth()->user()->business?->id) abort(403);

        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'prompt_template' => 'required|string|max:1000',
            'language'       => 'required|in:en,hi,gu',
            'min_rating'     => 'required|integer|min:1|max:5',
            'max_rating'     => 'required|integer|min:1|max:5',
        ]);

        $template->update($validated);

        return back()->with('success', 'Template updated.');
    }

    public function setDefault(AIReviewTemplate $template)
    {
        if ($template->business_id !== auth()->user()->business?->id) abort(403);

        AIReviewTemplate::where('business_id', $template->business_id)
            ->update(['is_default' => false]);

        $template->update(['is_default' => true]);

        return back()->with('success', 'Default template set.');
    }
}