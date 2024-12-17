<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Attributes that can be mass-assigned
    protected $fillable = [
        'title', 'description', 'price', 'category_id', 'user_id', 'image_path','is_thumbnail'
    ];

    /**
     * Relationship: Product belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Product belongs to a Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Product has many images
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Relationship: Product has a single thumbnail image
     */
    public function thumbnail()
    {
        return $this->hasOne(ProductImage::class)->where('is_thumbnail', true);
    }
    
    /**
     * Relationship: Product has many reviews
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }
}
