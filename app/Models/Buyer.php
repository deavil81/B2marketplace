<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'name',
        'about_us', 
        'business_name', // Add this field
        'industry',      // Add this field
        'business_interest', // Add this field
        'sourcing_needs', 
        'annul_budget',        // Correct field name if necessary
        'phone', 
        'address', 
        'profile_picture', 
        'annul_budget',  // Add if this is used
        'company_document', // Add this field
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
