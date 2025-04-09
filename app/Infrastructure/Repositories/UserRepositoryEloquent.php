<?php

namespace App\Infrastructure\Repositories;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * User Repository Implementation
 * 
 * Uses Eloquent ORM to work with users in database
 */
class UserRepositoryEloquent implements UserRepositoryInterface
{
    /**
     * Find user by ID
     * Returns null if not found
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Find user by email
     * Returns null if not found
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Get all users with pagination
     * Default is 10 users per page
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return User::paginate($perPage);
    }

    /**
     * Create new user
     * Returns the created user
     */
    public function create(array $userData): User
    {
        return User::create($userData);
    }

    /**
     * Update existing user
     * Returns true if successful
     */
    public function update(User $user, array $userData): bool
    {
        return $user->update($userData);
    }

    /**
     * Delete a user
     * Returns true if successful
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }
} 