<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'business_id', 'branch_id', 'qr_code_id', 'scan_id', 'employee_id',
        'rating', 'review_text', 'ai_suggested_review', 'source', 'status',
        'google_review_id', 'customer_name', 'customer_phone', 'customer_email',
        'is_returning_customer', 'metadata'
    ];

    protected $casts = [
        'is_returning_customer' => 'boolean',
        'metadata' => 'array',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function qrCode()
    {
        return $this->belongsTo(QRCode::class);
    }

    public function scan()
    {
        return $this->belongsTo(QRScan::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}