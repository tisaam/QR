<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function revenue()
    {
        $revenue = Payment::where('status', 'completed')
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw("SUM(amount) as total"))
            ->groupBy('month')->orderBy('month')->get();

        return view('admin.reports.revenue', compact('revenue'));
    }

    public function subscriptions()
    {
        $stats = Subscription::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->get();
            
        return view('admin.reports.subscriptions', compact('stats'));
    }

    public function businesses()
    {
        $businesses = Business::withCount(['qrCodes', 'reviews'])->latest()->take(20)->get();
        return view('admin.reports.businesses', compact('businesses'));
    }
}