<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // <-- Yeh line add karein

class Notification extends Model
{
    // Disable default timestamps because the migration uses custom ones
    // public $timestamps = false;
    
    // --- YEH LINE ADD KAREIN ---
    public $incrementing = false;
    
    // --- YEH FUNCTION ADD KAREIN ---
    protected static function booted()
    {
        static::creating(function ($notification) {
            // Agar ID nahi di gayi hai toh khud se UUID bana do
            if (empty($notification->id)) {
                $notification->id = (string) Str::uuid();
            }
        });
    }

    protected $table = 'notifications';

    protected $fillable = [
        'id', // <-- 'id' ko fillable me add kar dena
        'user_id', 'type', 'title', 'message', 'data', 'is_read', 'read_at'
    ];

    protected $casts = [
        'id' => 'string', // <-- Yeh line add karein
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}