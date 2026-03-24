<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorefrontSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'banner_title', 'short_description', 'main_image', 'banner_badge',
        'featured_1', 'featured_2', 'featured_3', 'featured_4',
        'featured_badge_1', 'featured_badge_2', 'featured_badge_3', 'featured_badge_4',
    ];

    public function productOne()
    {
        return $this->belongsTo(Product::class, 'featured_1');
    }

    public function productTwo()
    {
        return $this->belongsTo(Product::class, 'featured_2');
    }

    public function productThree()
    {
        return $this->belongsTo(Product::class, 'featured_3');
    }

    public function productFour()
    {
        return $this->belongsTo(Product::class, 'featured_4');
    }
}