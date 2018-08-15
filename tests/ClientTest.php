<?php

declare(strict_types=1);

namespace Damax\Services\Client\Tests;

use Damax\Services\Client\Client;
use Damax\Services\Client\InvalidRequestException;
use Damax\Services\Client\PassportCheck;
use Damax\Services\Client\RosfinItem;
use Http\Client\Common\HttpMethodsClient;
use Http\Message\MessageFactory;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Mock\Client as MockClient;
use InvalidArgumentException;
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
        $this->assertEquals('/mvd/passports', $request->getUri()->getPath());
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

        $this->assertSame($response, $this->client->downloadPassportCheck('0123456789'));

        $request = $this->httpClient->getLastRequest();
        $this->assertEquals('/mvd/passports', $request->getUri()->getPath());
    }

    /**
     * @test
     */
    public function it_fails_downloading_passport_check_result()
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
            [
                'id' => 123,
                'type' => 4,
                'fullName' => ['John Doe', 'Jane Doe'],
                'birthDate' => '1983-20-01',
                'birthPlace' => 'London',
            ],
        ]));

        $this->httpClient->addResponse($response);

        $result = $this->client->checkRosfin('Jane Doe', '1983-20-01');
        $this->assertCount(1, $result);

        /** @var RosfinItem $item */
        $item = iterator_to_array($result)[0];

        $this->assertEquals(123, $item->id());
        $this->assertEquals(4, $item->type());
        $this->assertEquals(['John Doe', 'Jane Doe'], $item->fullName());
        $this->assertEquals('1983-20-01', $item->birthDate());
        $this->assertEquals('London', $item->birthPlace());

        $request = $this->httpClient->getLastRequest();
        $this->assertEquals('/rosfin/catalogue', $request->getUri()->getPath());
        $this->assertEquals('fullName=Jane+Doe&birthDate=1983-20-01', $request->getUri()->getQuery());
    }

    /**
     * @test
     */
    public function it_fails_to_perform_rosfin_check()
    {
        $response = $this->messageFactory->createResponse(400, 'Bad request', [], json_encode([
            'message' => 'Empty full name.',
        ]));

        $this->httpClient->addResponse($response);

        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('Empty full name.');

        $this->client->checkRosfin('');
    }

    /**
     * @test
     */
    public function it_fails_to_perform_multiple_passports_check_on_empty_input()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one element required.');

        $this->client->checkMultiplePassports([]);
    }

    /**
     * @test
     */
    public function it_checks_multiple_passports()
    {
        $response = $this->messageFactory->createResponse(200, 'OK', [], json_encode([
            [
                'source' => '01 23 456789',
                'code' => 2,
                'message' => 'Invalid passport',
                'ok' => false,
                'series' => '0123',
                'number' => '456789',
            ],
            [
                'source' => '98 76 543210',
                'code' => 1,
                'message' => 'Valid passport',
                'ok' => true,
                'series' => '9876',
                'number' => '543210',
            ],
        ]));

        $this->httpClient->addResponse($response);

        $result = $this->client->checkMultiplePassports(['01 23 456789', '98 76 543210']);

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(PassportCheck::class, $result);

        $this->assertEquals('01 23 456789', $result[0]->source());
        $this->assertEquals(2, $result[0]->code());
        $this->assertTrue($result[0]->failed());

        $this->assertEquals('98 76 543210', $result[1]->source());
        $this->assertEquals(1, $result[1]->code());
        $this->assertTrue($result[1]->passed());

        $request = $this->httpClient->getLastRequest();
        $this->assertEquals('/mvd/passports/multiple', $request->getUri()->getPath());
        $this->assertEquals('input=01+23+456789,98+76+543210', $request->getUri()->getQuery());
    }
}
