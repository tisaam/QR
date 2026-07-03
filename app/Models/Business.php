<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'slug', 'description', 'address', 'city', 'state', 'pincode',
        'phone', 'email', 'website', 'google_place_id', 'google_review_link',
        'logo', 'cover_image', 'business_type', 'status'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function qrCodes()
    {
        return $this->hasMany(QRCode::class);
    }

    public function scans()
    {
        return $this->hasMany(QRScan::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function employees()
    {
        return $this->hasMany(User::class, 'id')->where('role', 'employee');
    }

    public function activeSubscription()
    {
        return $this->hasOneThrough(Subscription::class, User::class, 'id', 'user_id', 'user_id', 'id')
                    ->where('subscriptions.status', 'active')
                    ->latestOfMany();
    }

    // ---> ADD THIS MISSING METHOD <---
    public function subscriptions()
    {
        return $this->hasManyThrough(Subscription::class, User::class, 'id', 'user_id', 'user_id', 'id');
    }
}