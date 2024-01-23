<?php

declare(strict_types=1);

namespace Wearesho\QoreId\Response;

class NinPhoneFactory
{
    public function createFromApiResponse(array $data): NinPhone
    {
        if (!array_key_exists('status', $data) || !array_key_exists('status', $data['status'])) {
            throw new \InvalidArgumentException('Invalid data array: required status key is missing.');
        }

        if (!array_key_exists('nin', $data) || !array_key_exists('nin', $data['nin'])) {
            throw new \InvalidArgumentException('Invalid data array: required nin key is missing.');
        }

        if (!array_key_exists('summary', $data) || !array_key_exists('nin_check', $data['summary']) || !array_key_exists('fieldMatches', $data['summary']['nin_check'])) {
            throw new \InvalidArgumentException('Invalid data array: required fieldMatches key is missing.');
        }

        $status = $data['status']['status'];
        $nin = filter_var($data['nin']['nin'], FILTER_VALIDATE_INT);

        if ($nin === false) {
            throw new \InvalidArgumentException('Invalid nin value, expected integer.');
        }

        if (!is_array($data['summary']['nin_check']['fieldMatches'])) {
            throw new \InvalidArgumentException('Invalid fieldMatches value, expected array.');
        }

        $fieldMatches = $data['summary']['nin_check']['fieldMatches'];
        $details = $data['nin'];

        // remove the repeated 'nin' from 'details'
        unset($details['nin']);

        if (!is_array($details)) {
            throw new \InvalidArgumentException('Invalid details value, expected array.');
        }

        return new NinPhone($status, $nin, $fieldMatches, $details);
    }
}
