<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * User Repository Interface
 * 
 * Following DDD principles, this interface defines the contract for user persistence operations.
 * It abstracts the persistence layer from the domain layer.
 */
interface UserRepositoryInterface
{
    /**
     * Find a user by their ID
     */
    public function findById(int $id): ?User;

    /**
     * Find a user by their email
     */
    public function findByEmail(string $email): ?User;

    /**
     * Get all users with optional pagination
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator;

    /**
     * Create a new user
     */
    public function create(array $userData): User;

    /**
     * Update an existing user
     */
    public function update(User $user, array $userData): bool;

    /**
     * Delete a user
     */
    public function delete(User $user): bool;
} 