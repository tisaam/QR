<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\QRScan;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BusinessDashboardController extends Controller
{
    public function index()
    {
        $business = Auth::user()->business;
        if (!$business) return redirect()->route('onboarding');

        $totalScans = QRScan::where('business_id', $business->id)->count();
        $totalReviews = Review::where('business_id', $business->id)->count();
        $avgRating = Review::where('business_id', $business->id)->avg('rating') ?? 0;
        
        $conversionRate = $totalScans > 0 ? round(($totalReviews / $totalScans) * 100, 2) : 0;

        // Monthly Growth Calculation
        $lastMonthScans = QRScan::where('business_id', $business->id)
            ->whereBetween('scanned_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();
        $thisMonthScans = QRScan::where('business_id', $business->id)
            ->whereBetween('scanned_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();
        $monthlyGrowth = $lastMonthScans > 0 ? round((($thisMonthScans - $lastMonthScans) / $lastMonthScans) * 100, 2) : 0;

        // Recent Reviews
        $recentReviews = Review::where('business_id', $business->id)
            ->latest()
            ->take(5)
            ->get();

        // Chart Data (Last 7 days)
        $chartData = QRScan::where('business_id', $business->id)
            ->select(DB::raw('DATE(scanned_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('scanned_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('business.dashboard', compact(
            'totalScans', 'totalReviews', 'avgRating', 'conversionRate',
            'monthlyGrowth', 'recentReviews', 'chartData'
        ));
    }
}