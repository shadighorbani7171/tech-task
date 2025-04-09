<?php

namespace App\Domain\User\ValueObjects;

/**
 * Email Value Object
 * 
 * Encapsulates email validation and formatting rules.
 * Ensures email integrity throughout the domain.
 */
class Email
{
    private string $value;

    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }
        $this->value = strtolower($email);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
} 