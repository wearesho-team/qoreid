<?php

declare(strict_types=1);

namespace Wearesho\QoreId;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp;

/**
 * @internal
 */
class Utils
{
    public static function handleClientException(
        string $endpoint,
        GuzzleHttp\Exception\ClientException $exception
    ): void {
        $data = static::decodeResponse($exception->getResponse());
        if (!array_key_exists('statusCode', $data)) {
            throw new ClientException(
                "Missing statusCode in " . $endpoint . " failed request.",
                201
            );
        }
        if (!array_key_exists('message', $data)) {
            throw new ClientException(
                "Missing message in " . $endpoint . " failed request.",
                202
            );
        }
        throw new ClientException(
            "[" . $data['statusCode'] . '] ' . $data['message'],
            301
        );
    }

    public static function decodeResponse(ResponseInterface $response): array
    {
        return json_decode($response->getBody()->__toString(), true, JSON_THROW_ON_ERROR);
    }
}
