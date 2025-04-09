<?php

namespace App\Infrastructure\User;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Eloquent implementation of UserRepositoryInterface
 * 
 * This class provides the concrete implementation of user persistence
 * operations using Laravel's Eloquent ORM.
 */
class EloquentUserRepository implements UserRepositoryInterface
{
    /**
     * Find a user by their ID
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Find a user by their email
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Get all users with pagination
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return User::paginate($perPage);
    }

    /**
     * Create a new user
     * 
     * @throws \InvalidArgumentException if required fields are missing
     */
    public function create(array $userData): User
    {
        // Ensure required fields are present
        $requiredFields = ['name', 'surname', 'email', 'phone', 'country', 'gender', 'password'];
        foreach ($requiredFields as $field) {
            if (!isset($userData[$field])) {
                throw new \InvalidArgumentException("Missing required field: {$field}");
            }
        }

        // Hash the password before saving
        $userData['password'] = Hash::make($userData['password']);

        return User::create($userData);
    }

    /**
     * Update an existing user
     */
    public function update(User $user, array $userData): bool
    {
        // Hash password if it's being updated
        if (isset($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        }

        return $user->update($userData);
    }

    /**
     * Delete a user
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }
} 