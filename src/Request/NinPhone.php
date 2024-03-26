<?php

declare(strict_types=1);

namespace Wearesho\QoreId\Request;

class NinPhone implements \JsonSerializable
{
    use NameTrait;

    private string $phone;

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
}
