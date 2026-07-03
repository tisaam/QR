<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Services\Google\GooglePlaceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessProfileController extends Controller
{
    public function onboarding()
    {
        if (Auth::user()->business) {
            return redirect()->route('dashboard');
        }
        return view('business.onboarding');
    }

         public function storeOnboarding(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'business_type' => 'required|in:restaurant,hotel,retail,salon,hospital,other',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'required|string|max:10',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'google_place_id' => 'nullable|string',
        ]);

        $business = Business::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'slug' => \Str::slug($request->name) . '-' . \Str::random(6),
            'business_type' => $request->business_type,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'phone' => $request->phone,
            'email' => $request->email,
            'google_place_id' => $request->google_place_id,
            'status' => 'active',
        ]);

        // Set Google Review Link if Place ID is provided
        if ($request->google_place_id) {
            $googleService = app(GooglePlaceService::class);
            $business->google_review_link = $googleService->getReviewLink($request->google_place_id);
            $business->save();
        }

        // Assign Free Plan by default
        $freePlan = \App\Models\Plan::where('slug', 'free')->first();
        if ($freePlan) {
            \App\Models\Subscription::create([
                'user_id' => Auth::id(),
                'plan_id' => $freePlan->id,
                'business_id' => $business->id,
                'status' => 'active',
                'starts_at' => now(),
                'features' => $freePlan->features,
                'limits' => $freePlan->limits,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Business registered successfully!');
    }

    public function edit()
    {
        $business = Auth::user()->business;
        return view('business.profile.edit', compact('business'));
    }

    public function update(Request $request)
    {
        $business = Auth::user()->business;

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'google_review_link' => 'nullable|url',
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            if ($business->logo) Storage::delete('public/' . $business->logo);
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $business->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function searchGooglePlaces(Request $request)
    {
        $request->validate(['query' => 'required|string']);
        
        $googleService = app(GooglePlaceService::class);
        $results = $googleService->searchPlaces($request->query, $request->city ?? 'India');
        
        return response()->json($results);
    }
}