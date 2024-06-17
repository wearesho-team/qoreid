<?php

declare(strict_types=1);

namespace Wearesho\QoreId\Response;

class NinPhoneFactory
{
    use StatusTrait;
    use FieldMatchesTrait;

    public function createFromApiResponse(array $data): NinPhone
    {
        return new NinPhone(
            $this->getStatus($data),
            $this->getNin($data),
            $this->getFieldMatches('nin_check', $data),
            $this->getDetails($data)
        );
    }

    private function getDetails(array $apiData): ?array
    {
        if ($this->isMismatchStatus($apiData)) {
            return null;
        }
        $details = $apiData['nin'];
        if (!is_array($details)) {
            throw new \InvalidArgumentException('Invalid details value, expected array.');
        }
        // remove the repeated 'nin' from 'details'
        unset($details['nin']);
        return $details;
    }

    private function getNin(array $apiData): ?int
    {
        if ($this->isMismatchStatus($apiData)) {
            return null;
        }
        if (!array_key_exists('nin', $apiData) || !array_key_exists('nin', $apiData['nin'])) {
            throw new \InvalidArgumentException('Invalid data array: required nin key is missing.');
        }
        $nin = $apiData['nin']['nin'];
        if (!preg_match('/^\d{11}$/', $nin)) {
            throw new \InvalidArgumentException('Invalid NIN value: ' . $nin);
        }
        return (int)$nin;
    }

    private function isMismatchStatus(array $apiData): bool
    {
        return $this->getStatus($apiData) === NinPhone::STATUS_MISMATCH;
    }
}
