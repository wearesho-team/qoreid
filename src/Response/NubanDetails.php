<?php

declare(strict_types=1);

namespace Wearesho\QoreId\Response;

class NubanDetails implements \JsonSerializable
{
    private string $firstName;
    private string $lastName;
    private ?string $middleName;
    private string $accountName;
    private string $accountNumber;
    private string $dob;
    private string $accountCurrency;
    private string $mobileNumber;
    private string $bvn;
    private array $fieldMatches;

    public function __construct(
        string $firstName,
        string $lastName,
        ?string $middleName,
        string $accountName,
        string $accountNumber,
        string $dob,
        string $accountCurrency,
        string $mobileNumber,
        string $bvn,
        array $fieldMatches = []
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
        $this->accountName = $accountName;
        $this->accountNumber = $accountNumber;
        $this->dob = $dob;
        $this->accountCurrency = $accountCurrency;
        $this->mobileNumber = $mobileNumber;
        $this->bvn = $bvn;
        $this->fieldMatches = $fieldMatches;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function getAccountName(): string
    {
        return $this->accountName;
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public function getDob(): string
    {
        return $this->dob;
    }

    public function getAccountCurrency(): string
    {
        return $this->accountCurrency;
    }

    public function getMobileNumber(): string
    {
        return $this->mobileNumber;
    }

    public function getBvn(): string
    {
        return $this->bvn;
    }

    public function getFieldMatches(): array
    {
        return $this->fieldMatches;
    }

    public function jsonSerialize(): array
    {
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'middleName' => $this->middleName,
            'accountName' => $this->accountName,
            'accountNumber' => $this->accountNumber,
            'dob' => $this->dob,
            'accountCurrency' => $this->accountCurrency,
            'mobileNumber' => $this->mobileNumber,
            'bvn' => $this->bvn,
            'fieldMatches' => $this->fieldMatches,
        ];
    }
}
