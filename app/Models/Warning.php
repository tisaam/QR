<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warning extends Model
{
    protected $fillable = [
        'business_id',
        'user_id',
        'given_by',
        'reason',
        'severity',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ── Relationships ──

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function givenBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'given_by');
    }

    // ── Helpers ──

    public function getSeverityBadgeClass(): string
    {
        return match($this->severity) {
            'low'    => 'severity-low',
            'medium' => 'severity-medium',
            'high'   => 'severity-high',
            default  => 'severity-low',
        };
    }

    public function getSeverityIcon(): string
    {
        return match($this->severity) {
            'low'    => '⚠️',
            'medium' => '🔶',
            'high'   => '🔴',
            default  => '⚠️',
        };
    }

    public function getSeverityLabel(): string
    {
        return match($this->severity) {
            'low'    => 'Low',
            'medium' => 'Medium',
            'high'   => 'High',
            default  => 'Unknown',
        };
    }
}