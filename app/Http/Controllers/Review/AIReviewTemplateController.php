<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Models\AIReviewTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AIReviewTemplateController extends Controller
{
    public function index()
    {
        $templates = AIReviewTemplate::where('business_id', Auth::user()->business->id)->get();
        return view('ai-templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'prompt_template' => 'required|string',
            'language' => 'required|in:en,hi,gu',
        ]);

        Auth::user()->business->aiTemplates()->create($request->all());

        return back()->with('success', 'Template created.');
    }

    public function update(Request $request, AIReviewTemplate $template)
    {
        if ($template->business_id !== Auth::user()->business->id) abort(403);

        $template->update($request->only('name', 'prompt_template', 'is_active'));
        return back()->with('success', 'Template updated.');
    }

    public function setDefault(AIReviewTemplate $template)
    {
        if ($template->business_id !== Auth::user()->business->id) abort(403);

        Auth::user()->business->aiTemplates()->update(['is_default' => false]);
        $template->update(['is_default' => true]);

        return back()->with('success', 'Default template set.');
    }
}