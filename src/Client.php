<?php

declare(strict_types=1);

namespace Damax\Services\Client;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\HttpClient;
use InvalidArgumentException;
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
        $response = $this->httpClient->get('/mvd/passports?input=' . urlencode($input), ['accept' => 'application/json']);

        return PassportCheck::fromArray($this->parseResponse($response));
    }

    /**
     * @return PassportCheck[]
     *
     * @throws InvalidArgumentException
     * @throws InvalidRequestException
     */
    public function checkMultiplePassports(array $inputs): array
    {
        if (!count($inputs)) {
            throw new InvalidArgumentException('At least one element required.');
        }

        $input = implode(',', array_map('urlencode', $inputs));

        $response = $this->httpClient->get('/mvd/passports/multiple?input=' . $input, ['accept' => 'application/json']);

        return array_map([PassportCheck::class, 'fromArray'], $this->parseResponse($response));
    }

    /**
     * @throws InvalidRequestException
     */
    public function downloadPassportCheck(string $input): ResponseInterface
    {
        $response = $this->httpClient->get('/mvd/passports?input=' . urlencode($input), ['accept' => 'application/pdf']);

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
        $response = $this->httpClient->get('/rosfin/catalogue?' . http_build_query([
            'fullName' => $fullName,
            'birthDate' => $birthDate,
        ]));

        return RosfinCheck::fromArray($this->parseResponse($response));
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
