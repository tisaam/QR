<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppMessage extends Model
{
    protected $fillable = [
        'business_id', 'customer_phone', 'customer_name', 'message_type',
        'message_content', 'template_id', 'status', 'whatsapp_message_id', 'response', 'sent_at'
    ];

    protected $casts = [
        'response' => 'array',
        'sent_at' => 'datetime',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}