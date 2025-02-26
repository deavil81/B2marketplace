<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'about_us', 'business_type', 'phone', 'address', 'gst_number', 'business_license', 'catalog', 'profile_picture', 'certificates',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
