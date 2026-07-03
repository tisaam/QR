<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'business_owner')->count();
        $totalBusinesses = Business::count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $monthlyRevenue = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        $recentPayments = Payment::where('status', 'completed')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        // Revenue chart data (Last 6 months)
        $revenueChartData = Payment::where('status', 'completed')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw("SUM(amount) as revenue")
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalBusinesses', 'activeSubscriptions',
            'totalRevenue', 'monthlyRevenue', 'recentPayments', 'revenueChartData'
        ));
    }
}