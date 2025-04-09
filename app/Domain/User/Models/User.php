<?php

namespace App\Domain\User\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\Domain\User\Models\UserFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * User Entity - Root Aggregate
 * 
 * This class represents the User aggregate root in our domain.
 * It encapsulates all user-related business rules and behaviors.
 * This class now implements JWTSubject for JWT authentication.
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, Notifiable, HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     * Following DDD principles, we explicitly define what properties can be set.
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
     * The attributes that should be hidden for serialization.
     * Security concern: Never expose sensitive data.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     * Ensures proper data type handling.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Validate gender value against allowed options
     * Domain rule: Gender must be one of: male, female, other
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
     * Domain behavior: Combining first name and surname
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->name} {$this->surname}";
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     * For JWT authentication.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     * For JWT authentication.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
} 