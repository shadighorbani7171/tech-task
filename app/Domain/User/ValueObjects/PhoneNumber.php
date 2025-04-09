<?php

namespace App\Domain\User\ValueObjects;

/**
 * PhoneNumber Value Object
 * 
 * Encapsulates phone number validation and formatting rules.
 * Ensures phone number integrity throughout the domain.
 */
class PhoneNumber
{
    private string $value;

    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(string $phone)
    {
        // Remove any non-digit characters
        $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Basic validation: must be between 10 and 15 digits
        if (!preg_match('/^\+?[0-9]{10,15}$/', $cleanPhone)) {
            throw new \InvalidArgumentException('Invalid phone number format');
        }

        $this->value = $cleanPhone;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(PhoneNumber $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
} 