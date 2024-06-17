<?php

declare(strict_types=1);

namespace Wearesho\QoreId\Auth;

use Wearesho\QoreId;
use Psr\SimpleCache;
use GuzzleHttp;

/**
 * @internal
 */
class Client
{
    private const CACHE_KEY_PREFIX = 'qoreid.';
    private const CACHE_KEY_AUTHORIZATION = 'auth.v1';

    private const ENDPOINT_TOKEN = 'token';

    private QoreId\ConfigInterface $config;
    private GuzzleHttp\ClientInterface $client;
    private SimpleCache\CacheInterface $cache;

    public function __construct(
        QoreId\ConfigInterface $config,
        GuzzleHttp\ClientInterface $client,
        SimpleCache\CacheInterface $cache
    ) {
        $this->config = $config;
        $this->client = $client;
        $this->cache = $cache;
    }

    public function getAuthToken(): string
    {
        $cacheKey = $this->generateAuthCacheKey();
        $token = $this->cache->get($cacheKey);
        if (!empty($token)) {
            return base64_decode($token);
        }

        $response = $this->requestAuthToken();

        $this->cache->set(
            $cacheKey,
            base64_encode($response->getAccessToken()),
            $response->getTtl()
        );
        return $response->getAccessToken();
    }

    private function generateAuthCacheKey(): string
    {
        return static::CACHE_KEY_PREFIX . sha1(
            strrev(
                implode(
                    ',',
                    [
                            static::CACHE_KEY_AUTHORIZATION,
                            $this->config->getClientId(),
                            $this->config->getClientSecret()
                        ]
                )
            )
        );
    }

    private function requestAuthToken(): Response
    {
        try {
            $response = $this->client->request(
                'POST',
                QoreId\ConfigInterface::BASE_URL . static::ENDPOINT_TOKEN,
                [
                    GuzzleHttp\RequestOptions::JSON => [
                        'clientId' => $this->config->getClientId(),
                        'secret' => $this->config->getClientSecret(),
                    ],
                ]
            );
        } catch (GuzzleHttp\Exception\ClientException $exception) {
            QoreId\Utils::handleClientException(static::ENDPOINT_TOKEN, $exception);
        }

        $data = QoreId\Utils::decodeResponse($response);
        $factory = new ResponseFactory();
        return $factory->createFromApiResponse($data);
    }
}
