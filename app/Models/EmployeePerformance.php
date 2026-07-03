<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeePerformance extends Model
{
    protected $fillable = [
        'employee_id', 'business_id', 'date', 'qr_scans', 'reviews_generated',
        'avg_rating', 'positive_reviews', 'negative_reviews'
    ];

    protected $casts = [
        'date' => 'date',
        'avg_rating' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}