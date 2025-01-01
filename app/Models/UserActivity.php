<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_activities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',        // ID of the user performing the activity
        'activity_type',  // Type of activity (e.g., 'view', 'explore')
        'activity_data',  // Additional data related to the activity, stored as JSON
    ];

    /**
     * Define the relationship with the User model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor for decoding the JSON 'activity_data' column.
     *
     * @return array|null
     */
    public function getActivityDataAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Mutator for encoding the 'activity_data' column as JSON.
     *
     * @param array $value
     * @return void
     */
    public function setActivityDataAttribute($value)
    {
        $this->attributes['activity_data'] = json_encode($value);
    }
}
