<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;

class AdminBusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::with('user', 'subscriptions')->latest()->paginate(15);
        return view('admin.businesses.index', compact('businesses'));
    }

    public function show(Business $business)
    {
        $business->load(['user', 'subscriptions', 'qrCodes', 'reviews']);
        return view('admin.businesses.show', compact('business'));
    }

    public function approve(Business $business)
    {
        $business->update(['status' => 'active']);
        return back()->with('success', 'Business approved.');
    }

    public function reject(Business $business)
    {
        $business->update(['status' => 'rejected']);
        return back()->with('success', 'Business rejected.');
    }

    public function suspend(Business $business)
    {
        $business->update(['status' => 'suspended']);
        return back()->with('success', 'Business suspended.');
    }
}