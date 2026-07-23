<?php
 
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
 
if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        static $settings = null;
 
        if ($settings === null) {
            // Pehle Cache check karo, nahi hai toh DB se lo aur cache me 24 hr rakh do
            $settings = Cache::remember('app_settings', 86400, function () {
                return Setting::pluck('value', 'key')->toArray();
            });
        }
 
        return $settings[$key] ?? $default;
    }
}