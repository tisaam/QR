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
            'status' => 'pending',
        ]);

        // Set Google Review Link if Place ID is provided
        if ($request->google_place_id) {
            $googleService = app(GooglePlaceService::class);
            $business->google_review_link = $googleService->getReviewLink($request->google_place_id);
            $business->save();
        }
          
        // Admin ko notify
        \App\Models\Notification::create([
            'user_id' => 1, // Admin ka ID
            'type'    => 'new_business',
            'title'   => 'New Business Registered 🆕',
            'message' => '"' . $business->name . '" has completed onboarding and is pending approval.',
            'data'    => [
                'action_url'  => route('admin.businesses.show', $business),
                'action_text' => 'Review Business'
            ]
        ]);

        // User ko notify
        \App\Models\Notification::create([
            'user_id' => Auth::id(),
            'type'    => 'admin_action',
            'title'   => 'Business Registered! ✅',
            'message' => 'Your business "' . $business->name . '" has been registered. Go to QR Codes page to request admin approval for generating QR codes.',
            'data'    => [
                'action_url'  => route('qr-codes.index'),
                'action_text' => 'Go to QR Codes'
            ]
        ]);

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