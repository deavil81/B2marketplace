<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryRegistration extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'profile_picture',
        'business_name',
        'industry',
        'business_interest',
        'annual_budget',
        'document',
        'sourcing_needs',
    ];
}
