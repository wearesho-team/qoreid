<?php

declare(strict_types=1);

namespace Wearesho\QoreId\Auth;

use Wearesho\QoreId;

/**
 * @internal
 */
class ResponseFactory
{
    /**
     * @throws QoreId\ClientException
     */
    public function createFromApiResponse(array $data): Response
    {
        $this->validateData($data);

        $accessToken = $data['accessToken'];
        $ttl = new \DateInterval('PT' . $data['expiresIn'] . 'S');

        return new Response($accessToken, $ttl);
    }

    /**
     * @throws QoreId\ClientException
     */
    private function validateData(array $data): void
    {
        if (!array_key_exists('accessToken', $data)) {
            throw new QoreId\ClientException(
                "Missing accessToken in token endpoint response.",
                101
            );
        }
        if (!array_key_exists('expiresIn', $data)) {
            throw new QoreId\ClientException(
                "Missing expiresIn in token endpoint response.",
                102
            );
        }
    }
}
