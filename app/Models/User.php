<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
    ];

    /**
     * Automatically hash passwords when they are set.
     *
     * @param string $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Get the products associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the full path for the user's profile picture.
     *
     * @return string
     */
    public function getProfilePicturePathAttribute(): string
    {
        return $this->profile_picture 
            ? asset('storage/' . $this->profile_picture) 
            : asset('default-avatar.png');
    }

    /**
     * Check if the user has the specified role(s).
     *
     * @param string ...$roles
     * @return bool
     */
    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Replace the user's profile picture and delete the old one if it exists.
     *
     * @param string $newProfilePicture
     */
    public function updateProfilePicture(string $newProfilePicture)
    {
        if ($this->profile_picture && file_exists(public_path('uploads/profile_pictures/' . $this->profile_picture))) {
            unlink(public_path('uploads/profile_pictures/' . $this->profile_picture));
        }

        $this->profile_picture = $newProfilePicture;
        $this->save();
    }
}
