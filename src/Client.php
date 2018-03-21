<?php

declare(strict_types=1);

namespace Damax\Services\Client;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\HttpClient;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private $httpClient;

    public function __construct(HttpMethodsClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getHttpClient(): HttpClient
    {
        return $this->httpClient;
    }

    /**
     * @throws InvalidRequestException
     */
    public function checkPassport(string $input): PassportCheck
    {
        $response = $this->httpClient->get('/mvd/passports/check?input=' . urlencode($input), ['accept' => 'application/json']);

        return new PassportCheck($this->parseResponse($response));
    }

    /**
     * @throws InvalidRequestException
     */
    public function downloadPassportCheck(string $input): ResponseInterface
    {
        $response = $this->httpClient->get('/mvd/passports/check?input=' . urlencode($input), ['accept' => 'application/pdf']);

        if ($response->getStatusCode() >= 400) {
            throw new InvalidRequestException('Invalid request.');
        }

        return $response;
    }

    /**
     * @throws InvalidRequestException
     */
    public function checkRosfin(string $fullName, string $birthDate = null): RosfinCheck
    {
        $response = $this->httpClient->get('/rosfin/catalogue/check?' . http_build_query([
            'fullName' => $fullName,
            'birthDate' => $birthDate,
        ]));

        return new RosfinCheck($this->parseResponse($response));
    }

    /**
     * @throws InvalidRequestException
     */
    private function parseResponse(ResponseInterface $response): array
    {
        $data = json_decode((string) $response->getBody(), true);

        if ($response->getStatusCode() >= 400) {
            throw new InvalidRequestException($data['message'] ?? 'Invalid request.');
        }

        return $data;
    }
}
