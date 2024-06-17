<?php

declare(strict_types=1);

namespace Wearesho\QoreId;

use Psr\SimpleCache;
use GuzzleHttp;

class Client
{
    private const ENDPOINT_NIN_WITH_PHONE_NUMBER = 'v1/ng/identities/nin-phone';
    private const ENDPOINT_NIN_IDENTIFY = 'v1/ng/identities/nin';
    private const ENDPOINT_NUBAN_IDENTIFY = 'v1/ng/identities/nuban';

    private Auth\Client $authClient;
    private GuzzleHttp\ClientInterface $client;

    public function __construct(
        Auth\Client $authClient,
        GuzzleHttp\ClientInterface $client,
        SimpleCache\CacheInterface $cache
    ) {
        $this->authClient = $authClient;
        $this->client = $client;
    }

    public function identifyNinPhone(Request\NinPhone $request): Response\NinPhone
    {
        $response = $this->request(
            $this->getIdentifyNinPhoneEndpoint($request->getPhone()),
            'POST',
            $request->jsonSerialize()
        );

        $factory = new Response\NinPhoneFactory();
        try {
            return $factory->createFromApiResponse($response);
        } catch (\InvalidArgumentException $exception) {
            throw new ClientException(
                "Invalid IdentifyNinPhone response: " . $exception->getMessage(),
                201,
                $exception
            );
        }
    }

    private function getIdentifyNinPhoneEndpoint(string $phone): string
    {
        return static::ENDPOINT_NIN_WITH_PHONE_NUMBER . '/' . $phone;
    }

    public function identifyNin(Request\NinIdentify $request): Response\NinPhone
    {
        $response = $this->request(
            $this->getIdentifyNinEndpoint($request->getNin()),
            'POST',
            $request->jsonSerialize()
        );

        $factory = new Response\NinPhoneFactory();
        try {
            return $factory->createFromApiResponse($response);
        } catch (\InvalidArgumentException $exception) {
            throw new ClientException(
                "Invalid IdentifyNinPhone response: " . $exception->getMessage(),
                201,
                $exception
            );
        }
    }

    /**
     * @param Request\Nuban $request
     * @return Response\NubanDetails
     * @throws ClientException
     * @throws NoMatchException
     */
    public function nuban(Request\Nuban $request): Response\NubanDetails
    {
        $response = $this->request(
            static::ENDPOINT_NUBAN_IDENTIFY,
            'POST',
            $request->jsonSerialize()
        );
        $factory = new Response\NubanDetailsFactory();
        try {
            return $factory->createFromApiResponse($response);
        } catch (\InvalidArgumentException $exception) {
            throw new ClientException(
                "Invalid IdentifyNuban response: " . $exception->getMessage(),
                201,
                $exception
            );
        }
    }

    private function getIdentifyNinEndpoint(int $nin): string
    {
        return static::ENDPOINT_NIN_IDENTIFY . '/' . $nin;
    }

    private function request(string $endpoint, string $method, array $data): array
    {
        try {
            $response = $this->client->request(
                $method,
                ConfigInterface::BASE_URL . ltrim($endpoint, '/'),
                [
                    GuzzleHttp\RequestOptions::JSON => $data,
                    GuzzleHttp\RequestOptions::HEADERS => $this->getDefaultRequestHeaders(),
                ]
            );
        } catch (GuzzleHttp\Exception\ClientException $exception) {
            Utils::handleClientException($method, $exception);
        }

        return Utils::decodeResponse($response);
    }

    private function getDefaultRequestHeaders(): array
    {
        return [
            'authorization' => 'Bearer ' . $this->authClient->getAuthToken(),
        ];
    }
}
