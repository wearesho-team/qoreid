<?php

declare(strict_types=1);

namespace Wearesho\QoreId\Request;

class NinIdentify
{
    use NameTrait;

    private int $nin;

    public function __construct(int $nin, string $firstName, string $lastName)
    {
        $this->nin = $nin;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getNin(): int
    {
        return $this->nin;
    }
}
