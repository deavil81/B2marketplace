<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role', 
        'about_us', 
        'business_type', 
        'phone', 
        'address', 
        'profile_picture',
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getProfilePicturePathAttribute(): string
    {
        return $this->profile_pictures 
            ? asset('storage/' . $this->profile_picture) 
            : asset('default-avatar.png');
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles);
    }

    public function updateProfilePicture(string $newProfilePicture)
    {
        if ($this->profile_picture && file_exists(public_path('uploads/profile_pictures/' . $this->profile_picture))) {
            unlink(public_path('uploads/profile_pictures/' . $this->profile_picture));
        }

        $this->profile_picture = $newProfilePicture;
        $this->save();
    }

    /**
     * Get messages sent by the user.
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get messages received by the user.
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

   
}
