<?php

declare(strict_types=1);

namespace Damax\Client\Tests;

use Damax\Client\Client;
use Http\Client\Common\HttpMethodsClient;
use Http\Message\MessageFactory;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Mock\Client as MockClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

class ClientTest extends TestCase
{
    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var MockClient
     */
    private $httpClient;

    /**
     * @var Client
     */
    private $client;

    protected function setUp()
    {
        $this->messageFactory = new GuzzleMessageFactory();
        $this->httpClient = new MockClient($this->messageFactory);
        $this->client = new Client(new HttpMethodsClient($this->httpClient, $this->messageFactory));
    }

    /**
     * @test
     */
    public function it_retrieves_http_client()
    {
        $this->assertInstanceOf(HttpMethodsClient::class, $this->client->getHttpClient());
    }

    /**
     * @test
     */
    public function it_checks_passport()
    {
        $response = $this->messageFactory->createResponse(200, 'OK', [], json_encode([
            'source' => '0123456789',
            'code' => 2,
            'message' => 'Invalid passport',
            'ok' => false,
            'series' => '7405',
            'number' => '558551',
        ]));

        $this->httpClient->addResponse($response);

        $result = $this->client->checkPassport('0123456789');

        $this->assertFalse($result->passed());
        $this->assertTrue($result->failed());
        $this->assertEquals(2, $result->code());
        $this->assertEquals('0123456789', $result->source());
        $this->assertEquals('Invalid passport', $result->message());
        $this->assertEquals('7405', $result->series());
        $this->assertEquals('558551', $result->number());
    }

    /**
     * @test
     */
    public function it_downloads_passport_check_result()
    {
        $stream = $this->createMock(StreamInterface::class);

        $response = $this->messageFactory->createResponse(200, 'OK', [], $stream);

        $this->httpClient->addResponse($response);

        $this->assertSame($stream, $this->client->downloadPassportCheck('0123456789'));
    }
}
