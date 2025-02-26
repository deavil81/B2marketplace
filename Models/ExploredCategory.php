<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExploredCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
    ];

    /**
     * Relationship with User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
