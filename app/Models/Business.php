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
        'logo', 'cover_image', 'business_type', 'status',
        'block_reason', 'blocked_at', // <-- NEW
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'blocked_at'        => 'datetime', // <-- NEW
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    // ═══════════════════════════════════════
    // EXISTING RELATIONSHIPS (untouched)
    // ═══════════════════════════════════════

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
        return $this->hasOneThrough(
            Subscription::class,
            User::class,
            'id',
            'user_id',
            'user_id',
            'id'
        )->where('subscriptions.status', 'active')->latestOfMany();
    }

    public function subscriptions()
    {
        return $this->hasManyThrough(
            Subscription::class,
            User::class,
            'id',
            'user_id',
            'user_id',
            'id'
        );
    }

    // ═══════════════════════════════════════
    // NEW — Admin features ke liye
    // ═══════════════════════════════════════

    public function warnings()
    {
        return $this->hasMany(Warning::class);
    }

    // ═══════════════════════════════════════
    // HELPERS
    // ═══════════════════════════════════════

    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function getBusinessTypeLabelAttribute(): string
    {
        $types = [
            'restaurant' => 'Restaurant',
            'hotel'      => 'Hotel',
            'retail'     => 'Retail',
            'salon'      => 'Salon',
            'hospital'   => 'Hospital',
            'clinic'     => 'Clinic',
            'gym'        => 'Gym',
            'school'     => 'School',
            'other'      => 'Other',
        ];

        return $types[$this->business_type] ?? ucfirst($this->business_type);
    }


    // App\Models\Business me add karo

public function canGenerateQr(): bool
{
    return $this->status === 'active';
}

public function canBuyPlan(): bool
{
    return $this->status === 'active';
}

public function hasRequestedApproval(): bool
{
    return $this->status === 'approval_requested';
}

public function isPendingSetup(): bool
{
    return $this->status === 'pending';
}


public function aiTemplates()
{
    return $this->hasMany(AiReviewTemplate::class);
}

public function aiCredit()
{
    return $this->hasOne(AiCredit::class);
}
}