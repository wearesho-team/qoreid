<?php

declare(strict_types=1);

namespace Wearesho\QoreId\Request;

class NinPhone implements \JsonSerializable
{
    private string $phone;
    private string $firstName;
    private string $lastName;

    public function __construct(string $phone, string $firstName, string $lastName)
    {
        $this->phone = $phone;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function jsonSerialize(): array
    {
        return [
            'firstname' => $this->getFirstName(),
            'lastname' => $this->getLastName(),
        ];
    }
}
