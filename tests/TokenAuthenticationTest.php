<?php

declare(strict_types=1);

namespace Damax\Client\Tests;

use Damax\Client\TokenAuthentication;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use PHPUnit\Framework\TestCase;

class TokenAuthenticationTest extends TestCase
{
    /**
     * @test
     */
    public function it_authenticates_request()
    {
        $request = (new GuzzleMessageFactory())->createRequest('GET', '/');

        $authenticatedRequest = (new TokenAuthentication('XYZ'))->authenticate($request);

        $this->assertTrue($authenticatedRequest->hasHeader('authorization'));
        $this->assertEquals('Token XYZ', $authenticatedRequest->getHeader('authorization')[0]);
    }
}
