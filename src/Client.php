<?php

declare(strict_types=1);

namespace Damax\Client;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\HttpClient;
use Psr\Http\Message\StreamInterface;

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

    public function checkPassport(string $input): PassportCheck
    {
        $response = $this->httpClient->get('/mvd/passports/check?input=' . urlencode($input), ['accept' => 'application/json']);

        $data = json_decode((string) $response->getBody(), true);

        return new PassportCheck($data);
    }

    public function downloadPassportCheck(string $input): StreamInterface
    {
        $response = $this->httpClient->get('/mvd/passports/check?input=' . urlencode($input), ['accept' => 'application/pdf']);

        return $response->getBody();
    }
}
