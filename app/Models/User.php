<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use  HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'google_id',
        'avatar',
        'role',       // 'super_admin', 'business_owner', 'employee'
        'status',     // 'active', 'inactive', 'suspended'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'string',
        'role' => 'string',
    ];

    // ==========================================
    // HELPER METHODS
    // ==========================================

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isBusinessOwner(): bool
    {
        return $this->role === 'business_owner';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar 
            ? asset('storage/' . $this->avatar) 
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /**
     * Get the business profile owned by this user.
     */
    public function business()
    {
        return $this->hasOne(Business::class);
    }

    /**
     * Get all subscriptions for this user.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the current active subscription for the user.
     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->latestOfMany();
    }

    /**
     * Get all payments made by this user.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all AI credits associated with this user.
     */
    public function aiCredits()
    {
        return $this->hasMany(AICredit::class);
    }

    /**
     * Get support tickets created by this user.
     */
    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    /**
     * Get support tickets assigned to this user (Admin/Agent).
     */
    public function assignedTickets()
    {
        return $this->hasMany(SupportTicket::class, 'assigned_to');
    }

    /**
     * Get QR codes assigned to this employee.
     */
    public function assignedQrCodes()
    {
        return $this->hasMany(QRCode::class, 'employee_id');
    }

    /**
     * Get performance records for this employee.
     */
    public function performances()
    {
        return $this->hasMany(EmployeePerformance::class, 'employee_id');
    }
}