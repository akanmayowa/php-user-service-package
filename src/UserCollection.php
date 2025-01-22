<?php

namespace App;

use JsonSerializable;

class UserCollection implements JsonSerializable
{
    public function __construct(
        private readonly mixed   $id,
        private readonly ?string $email = null,
        private readonly ?string $firstName = null,
        private readonly ?string $lastName = null,
        private readonly ?string $avatar = null,
        private readonly ?string $name = null,
        private readonly ?string $job = null,
        private readonly ?string $createdAt = null,
    ) {}

    /**
     * Converts the UserCollection object to
     * an associative array, filtering out any null values.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'avatar' => $this->avatar,
            'name' => $this->name,
            'job' => $this->job,
            'createdAt' => $this->createdAt,
        ], fn($value) => $value !== null);
    }

    /**
     * Serializes the UserCollection object to
     * an associative array format for JSON encoding.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}