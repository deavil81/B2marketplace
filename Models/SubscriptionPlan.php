<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    // Table name (optional if following Laravel naming convention)
    protected $table = 'subscription_plans';

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'razorpay_plan_id',
        'price'
    ];

    /**
     * Relationship: A SubscriptionPlan can have multiple UserSubscriptions.
     */
    public function userSubscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class, 'subscription_plan_id');
    }
}
