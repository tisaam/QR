<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AICredit extends Model
{
   protected $table = 'ai_credits'; 
   
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
        return $this->hasMany(AICreditLog::class, 'ai_credit_id', 'id');
    }

    public function hasCredits(int $amount = 1): bool
    {
        return $this->remaining_credits >= $amount;
    }

    public function deduct(int $amount = 1, string $reason = 'Review generated', int $reviewId = null): void
    {
        $this->decrement('remaining_credits', $amount);
        $this->increment('used_credits', $amount);

        $this->logs()->create([
            'user_id'     => $this->user_id,
            'type'        => 'debit',
            'amount'      => $amount,
            'reason'      => $reason,
            'review_id'   => $reviewId,
        ]);
    }

    public function grant(int $amount, string $reason = 'Granted by admin'): void
    {
        $this->increment('remaining_credits', $amount);
        $this->increment('total_credits', $amount);

        $this->logs()->create([
            'user_id' => $this->user_id,
            'type'    => 'credit',
            'amount'  => $amount,
            'reason'  => $reason,
        ]);
    }
}