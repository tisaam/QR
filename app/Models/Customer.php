<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'business_id', 'phone', 'email', 'name', 'visit_count', 'review_count',
        'total_spent', 'loyalty_points', 'first_visit', 'last_visit', 'metadata'
    ];

    protected $casts = [
        'total_spent' => 'decimal:2',
        'first_visit' => 'datetime',
        'last_visit' => 'datetime',
        'metadata' => 'array',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}