<?php

declare(strict_types=1);

namespace Wearesho\QoreId\Auth;

/**
 * @internal
 */
class Response
{
    private string $accessToken;
    private \DateInterval $ttl;

    public function __construct(string $accessToken, \DateInterval $ttl)
    {
        $this->accessToken = $accessToken;
        $this->ttl = $ttl;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getTtl(): \DateInterval
    {
        return $this->ttl;
    }
}
