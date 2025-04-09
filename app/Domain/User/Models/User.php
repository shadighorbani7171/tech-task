<?php

namespace App\Domain\User\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\Domain\User\Models\UserFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * User Model
 * 
 * Handles all user-related data and behavior
 * Uses JWT for authentication
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, Notifiable, HasFactory;

    /**
     * Used for creating test users
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }

    /**
     * Fields that can be mass assigned
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone',
        'country',
        'gender',
        'password',
        'profile_picture',
        'introduction',
    ];

    /**
     * Sensitive fields that should be hidden
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Automatic data type casting
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Validate gender value
     * Only male, female, or other are allowed
     */
    public function setGenderAttribute($value)
    {
        $allowedGenders = ['male', 'female', 'other'];
        if (!in_array($value, $allowedGenders)) {
            throw new \InvalidArgumentException('Invalid gender value');
        }
        $this->attributes['gender'] = $value;
    }

    /**
     * Get user's full name
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->name} {$this->surname}";
    }

    /**
     * Get JWT identifier
     * Used for authentication
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get additional data for JWT token
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
} 