<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIReviewTemplate extends Model
{
    protected $fillable = [
        'business_id', 'name', 'prompt_template', 'language', 'min_rating',
        'max_rating', 'is_active', 'is_default', 'usage_count'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}