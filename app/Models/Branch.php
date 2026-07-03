<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'business_id', 'name', 'address', 'city', 'state', 'phone',
        'google_place_id', 'google_review_link', 'is_main', 'status'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function qrCodes()
    {
        return $this->hasMany(QRCode::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}