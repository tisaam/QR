<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::where('group', 'general')->get()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method', 'site_logo', 'favicon']);
 
        foreach ($data as $key => $value) {
            if (in_array($key, ['razorpay_key_secret', 'smtp_password']) && empty($value)) {
                continue;
            }
            Setting::updateOrCreate(
                ['key' => $key, 'group' => 'general'],
                ['value' => $value]
            );
        }
 
        // ✅ SITE LOGO (Alag save hoga)
        if ($request->hasFile('site_logo')) {
            // Purana delete karo
            $oldLogo = Setting::where('key', 'site_logo')->where('group', 'general')->value('value');
            if ($oldLogo && file_exists(public_path($oldLogo))) {
                unlink(public_path($oldLogo));
            }
           
            // Naya save karo (Prefix 'logo_' laga diya, double extension nahi hoga)
            $extension = $request->file('site_logo')->getClientOriginalExtension();
            $logoName = 'logo_' . time() . '.' . $extension;
            $request->file('site_logo')->move(public_path('settings'), $logoName);
           
            Setting::updateOrCreate(
                ['key' => 'site_logo', 'group' => 'general'],
                ['value' => 'settings/' . $logoName]
            );
        }
 
        // ✅ FAVICON (Bilkul alag save hoga)
        if ($request->hasFile('favicon')) {
        // Purana delete karo
        $oldFavicon = Setting::where('key', 'favicon')->where('group', 'general')->value('value');
        if ($oldFavicon && file_exists(public_path($oldFavicon))) {
            unlink(public_path($oldFavicon));
        }
       
        // Naya save karo public/settings/ me
        $extension = $request->file('favicon')->getClientOriginalExtension();
        $faviconName = 'favicon_' . time() . '.' . $extension;
        $request->file('favicon')->move(public_path('settings'), $faviconName);
       
        // ✨ MAGIC TRICK: YEH LINE ROOT FILE KO AUTO REPLACE KAREGI
        $sourcePath = public_path('settings/' . $faviconName);
        $rootFavicon = public_path('favicon.ico');
       
        if (file_exists($sourcePath)) {
            copy($sourcePath, $rootFavicon);
        }
       
        Setting::updateOrCreate(
            ['key' => 'favicon', 'group' => 'general'],
            ['value' => 'settings/' . $faviconName]
        );
    }
 
        // Cache clear karo
        Cache::forget('app_settings');
 
        return back()->with('success', 'System settings saved.');
    }
}