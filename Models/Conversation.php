<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['user1_id', 'user2_id'];

    protected $with = ['user1', 'user2'];

    // Relationship with messages
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Relationship for User 1
    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    // Relationship for User 2
    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    // Helper to get the other user in the conversation
    public function otherUser($currentUserId)
    {
        if ($this->user1_id === $currentUserId) {
            return $this->user2;
        } elseif ($this->user2_id === $currentUserId) {
            return $this->user1;
        }

        return null;
    }

    // Scope to fetch conversations for a specific user
    public function scopeForUser($query, $userId)
    {
        return $query->where('user1_id', $userId)->orWhere('user2_id', $userId);
    }
}
