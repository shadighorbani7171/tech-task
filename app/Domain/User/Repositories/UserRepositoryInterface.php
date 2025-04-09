<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * User Repository Interface
 * 
 * Defines how we work with user data
 * Keeps database details separate from business logic
 */
interface UserRepositoryInterface
{
    /**
     * Find user by ID
     * Returns null if not found
     */
    public function findById(int $id): ?User;

    /**
     * Find user by email
     * Returns null if not found
     */
    public function findByEmail(string $email): ?User;

    /**
     * Get all users with pagination
     * Default is 10 users per page
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator;

    /**
     * Create new user
     * Returns the created user
     */
    public function create(array $userData): User;

    /**
     * Update existing user
     * Returns true if successful
     */
    public function update(User $user, array $userData): bool;

    /**
     * Delete a user
     * Returns true if successful
     */
    public function delete(User $user): bool;
} 