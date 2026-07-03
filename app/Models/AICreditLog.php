<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AICreditLog extends Model
{
    protected $fillable = [
        'ai_credit_id', 'user_id', 'type', 'amount', 'reason', 'review_id'
    ];

    public function aiCredit()
    {
        return $this->belongsTo(AICredit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}