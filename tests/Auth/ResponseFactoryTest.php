<?php

declare(strict_types=1);

namespace Wearesho\QoreId\Tests\Auth;

use PHPUnit\Framework\TestCase;
use Wearesho\QoreId\Auth\ResponseFactory;
use Wearesho\QoreId\ClientException;

final class ResponseFactoryTest extends TestCase
{
    private ResponseFactory $responseFactory;

    protected function setUp(): void
    {
        $this->responseFactory = new ResponseFactory();
    }

    public function testCreateFromApiResponse(): void
    {
        $responseDate = [
            'accessToken' => 'testToken',
            'expiresIn' => 3600 // 1 hour
        ];

        $result = $this->responseFactory->createFromApiResponse($responseDate);

        $this->assertSame($responseDate['accessToken'], $result->getAccessToken());
        $this->assertSame($responseDate['expiresIn'], $result->getTtl()->s);
    }

    public function testCreateFromApiResponseMissingAccessToken(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(101);
        $this->expectExceptionMessage('Missing accessToken in token endpoint response.');

        $responseDateWithoutAccessToken = ['expiresIn' => 3600];
        $this->responseFactory->createFromApiResponse($responseDateWithoutAccessToken);
    }

    public function testCreateFromApiResponseMissingExpiresIn(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(102);
        $this->expectExceptionMessage('Missing expiresIn in token endpoint response.');

        $responseDateWithoutExpiresIn = ['accessToken' => 'testToken'];
        $this->responseFactory->createFromApiResponse($responseDateWithoutExpiresIn);
    }
}
