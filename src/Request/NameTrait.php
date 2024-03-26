<?php

declare(strict_types=1);

namespace Wearesho\QoreId\Request;

trait NameTrait
{
    protected string $firstName;
    protected string $lastName;

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
