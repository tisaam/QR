<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QRScan extends Model
{
    protected $table = 'qr_scans'; 
    protected $fillable = [
        'qr_code_id', 'business_id', 'session_id', 'ip_address', 'device_type',
        'browser', 'os', 'location', 'latitude', 'longitude', 'converted_to_review', 'scanned_at'
    ];

    protected $casts = [
        'converted_to_review' => 'boolean',
        'scanned_at' => 'datetime',
    ];

    public function qrCode()
    {
        return $this->belongsTo(QRCode::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}