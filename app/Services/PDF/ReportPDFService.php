<?php

namespace App\Services\PDF;

use App\Models\Business;
use App\Models\Review;
use App\Models\QRScan;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportPDFService
{
    public function generateReviewReport(Business $business, string $dateFrom, string $dateTo)
    {
        $reviews = Review::where('business_id', $business->id)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->with('qrCode', 'branch')
            ->get();

        $totalScans = QRScan::where('business_id', $business->id)
            ->whereBetween('scanned_at', [$dateFrom, $dateTo])
            ->count();

        $data = [
            'business' => $business,
            'reviews' => $reviews,
            'total_scans' => $totalScans,
            'total_reviews' => $reviews->count(),
            'avg_rating' => $reviews->avg('rating') ?? 0,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ];

        // Requires a view at resources/views/reports/pdf-report.blade.php
        $pdf = Pdf::loadView('reports.pdf-report', $data)
                 ->setPaper('a4', 'portrait');

        return $pdf->download('review-report-' . $business->slug . '.pdf');
    }
}