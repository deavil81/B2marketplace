<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['name', 'image', 'parent_id'];

    // Self-referencing relationship for children
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Self-referencing relationship for parent
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class,);
    }

    public function rfqs()
    {
        return $this->hasMany(RFQ::class, 'category_id');
    }


}
