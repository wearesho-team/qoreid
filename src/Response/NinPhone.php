<?php

declare(strict_types=1);

namespace Wearesho\QoreId\Response;

class NinPhone
{
    private string $status;
    private int $nin;

    private array $fieldMatches;
    private array $details;

    public function __construct(string $status, int $nin, array $fieldMatches, array $details)
    {
        $this->status = $status;
        $this->nin = $nin;
        $this->fieldMatches = $fieldMatches;
        $this->details = $details;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getNin(): int
    {
        return $this->nin;
    }

    public function getFieldMatches(): array
    {
        return $this->fieldMatches;
    }

    public function getDetails(): array
    {
        return $this->details;
    }
}
