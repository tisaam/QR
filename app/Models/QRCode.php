<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class QRCode extends Model
{
    use SoftDeletes;
 protected $table = 'qr_codes'; 
    protected $fillable = [
        'business_id', 'branch_id', 'employee_id', 'name', 'slug', 'type',
        'identifier', 'qr_image_path', 'qr_svg_path', 'landing_page_url',
        'scan_count', 'review_count', 'is_active', 'settings'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function scans()
    {
        return $this->hasMany(QRScan::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getPngUrlAttribute()
    {
        return $this->qr_image_path ? Storage::url($this->qr_image_path) : null;
    }

    public function getSvgUrlAttribute()
    {
        return $this->qr_svg_path ? Storage::url($this->qr_svg_path) : null;
    }
}