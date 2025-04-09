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
 * This service encapsulates the business logic for user management.
 * It uses Value Objects to ensure data integrity and domain rules.
 */
class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all users with pagination
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->userRepository->getAllPaginated($perPage);
    }

    /**
     * Find user by ID
     */
    public function findById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Create a new user with validated data
     * 
     * @throws \InvalidArgumentException
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
     * 
     * @throws \InvalidArgumentException
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
     * Get list of valid countries
     */
    public function getCountryList(): array
    {
        return Country::getValidCountries();
    }

    /**
     * Delete a user
     */
    public function deleteUser(User $user): bool
    {
        return $this->userRepository->delete($user);
    }
} 