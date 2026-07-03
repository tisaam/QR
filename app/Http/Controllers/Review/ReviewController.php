<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $business = Auth::user()->business;
        
        $reviews = Review::where('business_id', $business->id)
            ->with(['qrCode', 'branch', 'employee'])
            ->when($request->rating, fn($q, $r) => $q->where('rating', $r))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15);

        return view('reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        if ($review->business_id !== Auth::user()->business->id) abort(403);
        
        return view('reviews.show', compact('review'));
    }

    public function export()
    {
        $business = Auth::user()->business;
        $reviews = Review::where('business_id', $business->id)->get();
        
        // In a real app, use Maatwebsite Excel here:
        // return Excel::download(new ReviewsExport($reviews), 'reviews.xlsx');
        
        return response()->json($reviews);
    }
}