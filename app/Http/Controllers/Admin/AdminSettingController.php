<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::where('group', 'general')->get()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'razorpay_key' => 'nullable|string',
            'razorpay_secret' => 'nullable|string',
            'google_places_key' => 'nullable|string',
            'openai_api_key' => 'nullable|string',
        ]);

        foreach ($request->except('_token') as $key => $value) {
            Setting::updateOrCreate(['key' => $key, 'group' => 'general'], ['value' => $value]);
        }

        return back()->with('success', 'System settings saved.');
    }
}