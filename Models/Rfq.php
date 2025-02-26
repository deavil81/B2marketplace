<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rfq extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category_id',
        'subcategory_id',
        'quantity',
        'budget',
        'deadline',
        'user_id',
    ];

    protected $dates = ['deadline'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }    

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

     // Define the relationship
     public function proposals()
    {
        return $this->hasMany(\App\Models\Proposal::class, 'rfq_id');
    }

     public function rfq()
    {
        return $this->belongsTo(RFQ::class, 'rfq_id');
    }
    public function responses()
    {
        return $this->hasMany(Response::class, 'rfq_id');
    }

}
