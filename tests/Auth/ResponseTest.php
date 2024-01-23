<?php

declare(strict_types=1);

namespace Wearesho\QoreId\Tests\Auth;

use PHPUnit\Framework\TestCase;
use Wearesho\QoreId\Auth\Response;

final class ResponseTest extends TestCase
{
    public function testGetAccessToken(): void
    {
        $accessToken = 'testToken';
        $ttl = new \DateInterval('PT1H');
        $response = new Response($accessToken, $ttl);

        $this->assertSame($accessToken, $response->getAccessToken());
    }

    public function testGetTtl(): void
    {
        $accessToken = 'testToken';
        $ttl = new \DateInterval('PT1H');
        $response = new Response($accessToken, $ttl);

        $this->assertSame($ttl, $response->getTtl());
    }
}
