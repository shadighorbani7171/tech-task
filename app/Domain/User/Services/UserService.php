<?php

namespace App\Domain\User\Services;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\PhoneNumber;
use App\Domain\User\ValueObjects\Country;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * User Service
 * 
 * This service handles all user-related operations
 * It makes sure data is valid and follows business rules
 */
class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get list of users with pagination
     * Default is 10 users per page
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->userRepository->getAllPaginated($perPage);
    }

    /**
     * Find a user by their ID
     * Returns null if user not found
     */
    public function findById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Create a new user
     * Checks if email is already used
     * Validates all required fields
     */
    public function createUser(array $data): User
    {
        // Validate and create value objects
        $email = new Email($data['email']);
        $phone = new PhoneNumber($data['phone']);
        $country = new Country($data['country']);

        // Check if email is already in use
        if ($this->userRepository->findByEmail($email->getValue())) {
            throw new \InvalidArgumentException('Email is already in use');
        }

        // Prepare validated data
        $userData = [
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $email->getValue(),
            'phone' => $phone->getValue(),
            'country' => $country->getValue(),
            'gender' => $data['gender'],
            'password' => $data['password']
        ];

        // Add optional fields if present
        if (isset($data['profile_picture'])) {
            $userData['profile_picture'] = $data['profile_picture'];
        }
        if (isset($data['introduction'])) {
            $userData['introduction'] = $data['introduction'];
        }

        return $this->userRepository->create($userData);
    }

    /**
     * Update user information
     * Checks if new email is already used by another user
     * Validates all updated fields
     */
    public function updateUser(User $user, array $data): bool
    {
        $userData = [];

        // Update and validate email if provided
        if (isset($data['email'])) {
            $email = new Email($data['email']);
            $existingUser = $this->userRepository->findByEmail($email->getValue());
            if ($existingUser && $existingUser->id !== $user->id) {
                throw new \InvalidArgumentException('Email is already in use');
            }
            $userData['email'] = $email->getValue();
        }

        // Update and validate phone if provided
        if (isset($data['phone'])) {
            $phone = new PhoneNumber($data['phone']);
            $userData['phone'] = $phone->getValue();
        }

        // Update and validate country if provided
        if (isset($data['country'])) {
            $country = new Country($data['country']);
            $userData['country'] = $country->getValue();
        }

        // Update other fields
        $updateableFields = ['name', 'surname', 'gender', 'password', 'profile_picture', 'introduction'];
        foreach ($updateableFields as $field) {
            if (isset($data[$field])) {
                $userData[$field] = $data[$field];
            }
        }

        return $this->userRepository->update($user, $userData);
    }

    /**
     * Get list of all valid countries
     */
    public function getCountryList(): array
    {
        return Country::getValidCountries();
    }

    /**
     * Delete a user from the system
     */
    public function deleteUser(User $user): bool
    {
        return $this->userRepository->delete($user);
    }
} 