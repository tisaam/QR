<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AICredit extends Model
{
    protected $fillable = [
        'user_id', 'business_id', 'total_credits', 'used_credits', 'remaining_credits', 'reset_at'
    ];

    protected $casts = [
        'reset_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function logs()
    {
        return $this->hasMany(AICreditLog::class);
    }
}