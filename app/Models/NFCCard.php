<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NFCCard extends Model
{
    protected $fillable = [
        'business_id', 'qr_code_id', 'card_uid', 'name', 'design', 'status', 'tap_count'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function qrCode()
    {
        return $this->belongsTo(QRCode::class);
    }
}