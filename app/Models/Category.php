<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Specify the table name (optional if it matches the model name in plural)
    protected $table = 'categories';

    // Specify the fillable fields
    protected $fillable = ['name', 'image'];

    // Define the relationship with the Product model
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
