<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIReviewTemplate extends Model
{

   protected $table = 'ai_review_templates';
    protected $fillable = [
        'business_id', 'name', 'prompt_template', 'language', 'min_rating',
        'max_rating', 'is_active', 'is_default', 'usage_count'
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_default'  => 'boolean',
        'usage_count' => 'integer',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    // ✅ NAYE SCOPES ADD KIYE
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true)->where('is_active', true);
    }

    public function scopeForRating($query, int $rating)
    {
        return $query->where('min_rating', '<=', $rating)
                    ->where('max_rating', '>=', $rating);
    }
}