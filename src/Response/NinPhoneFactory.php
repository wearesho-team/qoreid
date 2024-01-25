<?php

declare(strict_types=1);

namespace Wearesho\QoreId\Response;

class NinPhoneFactory
{
    public function createFromApiResponse(array $data): NinPhone
    {
        return new NinPhone(
            $this->getStatus($data),
            $this->getNin($data),
            $this->getFieldMatches($data),
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

    private function getStatus(array $apiData): string
    {
        if (!array_key_exists('status', $apiData) || !array_key_exists('status', $apiData['status'])) {
            throw new \InvalidArgumentException('Invalid data array: required status key is missing.');
        }
        $status = $apiData['status']['status'];
        if (!in_array($status, [NinPhone::STATUS_VERIFIED, NinPhone::STATUS_MISMATCH])) {
            throw new \InvalidArgumentException('Invalid status value: ' . $status);
        }
        return $status;
    }

    private function getFieldMatches(array $apiData): array
    {
        if (
            !array_key_exists('summary', $apiData)
            || !array_key_exists(
                'nin_check',
                $apiData['summary']
            )
            || !array_key_exists('fieldMatches', $apiData['summary']['nin_check'])
        ) {
            throw new \InvalidArgumentException('Invalid data array: required fieldMatches key is missing.');
        }
        $fieldMatches = $apiData['summary']['nin_check']['fieldMatches'];
        if (!is_array($fieldMatches)) {
            throw new \InvalidArgumentException('Invalid fieldMatches value, expected array.');
        }
        return $fieldMatches;
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
