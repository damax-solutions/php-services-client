<?php

declare(strict_types=1);

namespace Damax\Client\Tests;

use Damax\Client\Client;
use Damax\Client\InvalidRequestException;
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

        $request = $this->httpClient->getLastRequest();
        $this->assertEquals('/mvd/passports/check', $request->getUri()->getPath());
        $this->assertEquals('input=0123456789', $request->getUri()->getQuery());
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

        $request = $this->httpClient->getLastRequest();
        $this->assertEquals('/mvd/passports/check', $request->getUri()->getPath());
    }

    /**
     * @test
     */
    public function it_throws_exception_when_downloading_passport_check_result()
    {
        $stream = $this->createMock(StreamInterface::class);

        $response = $this->messageFactory->createResponse(400, null, [], $stream);

        $this->httpClient->addResponse($response);

        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('Invalid request.');

        $this->client->downloadPassportCheck('0123456789');
    }

    /**
     * @test
     */
    public function it_checks_rosfin()
    {
        $response = $this->messageFactory->createResponse(200, 'OK', [], json_encode([
            'id' => 123,
            'type' => 4,
            'fullName' => ['John Doe', 'Jane Doe'],
            'birthDate' => '1983-20-01',
            'birthPlace' => 'London',
        ]));

        $this->httpClient->addResponse($response);

        $result = $this->client->checkRosfin('Jane Doe', '1983-20-01');

        $this->assertEquals(123, $result->id());
        $this->assertEquals(4, $result->type());
        $this->assertEquals(['John Doe', 'Jane Doe'], $result->fullName());
        $this->assertEquals('1983-20-01', $result->birthDate());
        $this->assertEquals('London', $result->birthPlace());

        $request = $this->httpClient->getLastRequest();
        $this->assertEquals('/rosfin/catalogue/check', $request->getUri()->getPath());
        $this->assertEquals('fullName=Jane+Doe&birthDate=1983-20-01', $request->getUri()->getQuery());
    }

    /**
     * @test
     */
    public function it_throws_exception_on_invalid_rosfin_check()
    {
        $response = $this->messageFactory->createResponse(400, 'Bad request', [], json_encode([
            'message' => 'Empty full name.',
        ]));

        $this->httpClient->addResponse($response);

        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('Empty full name.');

        $this->client->checkRosfin('');
    }
}
