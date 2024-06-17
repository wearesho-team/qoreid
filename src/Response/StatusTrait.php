<?php

declare(strict_types=1);

namespace Wearesho\QoreId\Response;

trait StatusTrait
{
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
}
