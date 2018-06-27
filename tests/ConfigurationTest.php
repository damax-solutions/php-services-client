<?php

declare(strict_types=1);

namespace Damax\Services\Client\Tests;

use Damax\Services\Client\Configuration;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Message\RequestFactory;
use Http\Mock\Client as MockClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Debug\BufferingLogger;

class ConfigurationTest extends TestCase
{
    /**
     * @var RequestFactory
     */
    private $messageFactory;

    /**
     * @var BufferingLogger
     */
    private $logger;

    protected function setUp()
    {
        $this->messageFactory = new GuzzleMessageFactory();
        $this->logger = new BufferingLogger();
    }

    /**
     * @test
     */
    public function it_creates_client()
    {
        (new Configuration('https://api.damax.solutions/services', 'XYZ'))
            ->setLogger($this->logger)
            ->setHttpClient($httpClient = new MockClient($this->messageFactory))
            ->getClient()
            ->getHttpClient()
            ->sendRequest($this->messageFactory->createRequest('GET', '/foo/bar'))
        ;

        $request = $httpClient->getLastRequest();

        $this->assertEquals('https', $request->getUri()->getScheme());
        $this->assertEquals('api.damax.solutions', $request->getUri()->getHost());
        $this->assertEquals('/services/foo/bar', $request->getUri()->getPath());
        $this->assertTrue($request->hasHeader('authorization'));

        $logs = $this->logger->cleanLogs();
        $this->assertCount(2, $logs);
    }
}
