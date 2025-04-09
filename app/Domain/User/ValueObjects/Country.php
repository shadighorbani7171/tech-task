<?php

namespace App\Domain\User\ValueObjects;

/**
 * Country Value Object
 * 
 * Encapsulates country validation and provides a predefined list of valid countries.
 * Ensures country data integrity throughout the domain.
 */
class Country
{
    private string $value;

    // Predefined list of countries (این لیست می‌تواند از یک سرویس یا فایل کانفیگ خوانده شود)
    private static array $validCountries = [
        'IR' => 'Iran',
        'US' => 'United States',
        'GB' => 'United Kingdom',
        'DE' => 'Germany',
        'FR' => 'France',
        // ... other countries can be added
    ];

    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(string $countryCode)
    {
        $countryCode = strtoupper($countryCode);
        if (!array_key_exists($countryCode, self::$validCountries)) {
            throw new \InvalidArgumentException('Invalid country code');
        }
        $this->value = $countryCode;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getName(): string
    {
        return self::$validCountries[$this->value];
    }

    public static function getValidCountries(): array
    {
        return self::$validCountries;
    }

    public function equals(Country $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
} 