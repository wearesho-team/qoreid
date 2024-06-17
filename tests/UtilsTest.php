<?php

declare(strict_types=1);

namespace Wearesho\QoreId\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp;
use Wearesho\QoreId\Utils;
use Wearesho\QoreId\ClientException;

final class UtilsTest extends TestCase
{
    private GuzzleHttp\Psr7\Response $response;

    protected function setUp(): void
    {
        $this->response = new GuzzleHttp\Psr7\Response();
    }

    public function testDecodeResponse(): void
    {
        $bodyArray = ['key' => 'value'];
        $stream = GuzzleHttp\Psr7\Utils::streamFor(json_encode($bodyArray));

        $response = $this->response->withBody($stream);

        $result = Utils::decodeResponse($response);

        $this->assertSame($bodyArray, $result);
    }

    public function testHandleClientExceptionWithoutStatusCode(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(201);
        $this->expectExceptionMessage('Missing statusCode in endPoint failed request.');

        $clientException = new GuzzleHttp\Exception\ClientException(
            'Error',
            new GuzzleHttp\Psr7\Request('POST', 'app'),
            $this->response->withBody(GuzzleHttp\Psr7\Utils::streamFor("{}"))
        );

        Utils::handleClientException('endPoint', $clientException);
    }

    public function testHandleClientExceptionWithoutMessage(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(202);
        $this->expectExceptionMessage('Missing message in endPoint failed request.');

        $clientException = new GuzzleHttp\Exception\ClientException(
            'Error',
            new GuzzleHttp\Psr7\Request('POST', 'app'),
            $this->response->withBody(GuzzleHttp\Psr7\Utils::streamFor('{"statusCode": 100}'))
        );

        Utils::handleClientException('endPoint', $clientException);
    }
}
