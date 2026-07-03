<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SMSMessage extends Model
{
    protected $fillable = [
        'business_id', 'customer_phone', 'customer_name', 'message_content',
        'status', 'provider_message_id', 'cost', 'sent_at'
    ];

    protected $casts = [
        'cost' => 'decimal:4',
        'sent_at' => 'datetime',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}