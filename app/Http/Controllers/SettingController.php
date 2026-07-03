<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::where('group', 'business')->get()->pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'auto_reply_enabled' => 'nullable|boolean',
            'reminder_enabled' => 'nullable|boolean',
        ]);

        foreach ($request->except('_token') as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key, 'group' => 'business'],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Settings saved.');
    }
}