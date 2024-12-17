<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'image_path','is_thumbnail'];

    public function product()
    {
        return $this->belongsTo(Product::class,'is_thumbnail');
    }
}