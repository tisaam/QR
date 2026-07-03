<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewReminder extends Model
{
    protected $fillable = [
        'business_id', 'scan_id', 'customer_phone', 'customer_email', 'channel', 'status', 'scheduled_at', 'sent_at'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function scan()
    {
        return $this->belongsTo(QRScan::class);
    }
}