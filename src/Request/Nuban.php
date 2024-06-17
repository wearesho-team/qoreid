<?php

declare(strict_types=1);

namespace Wearesho\QoreId\Request;

class Nuban implements \JsonSerializable
{
    use NameTrait {
        jsonSerialize as nameJsonSerialize;
    }

    private string $accountNumber;
    private string $bankCode;

    public function __construct(string $bankCode, string $accountNumber, string $firstName, string $lastName)
    {
        $this->bankCode = $bankCode;
        $this->accountNumber = $accountNumber;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public function getBankCode(): string
    {
        return $this->bankCode;
    }

    public function jsonSerialize(): array
    {
        return [
            'firstname' => $this->firstName,
            'lastname' => $this->lastName,
            'accountNumber' => $this->accountNumber,
            'bankCode' => $this->bankCode,
        ];
    }
}
