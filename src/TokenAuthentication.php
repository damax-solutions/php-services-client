<?php

declare(strict_types=1);

namespace Damax\Services\Client;

use Http\Message\Authentication;
use Psr\Http\Message\RequestInterface;

final class TokenAuthentication implements Authentication
{
    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function authenticate(RequestInterface $request): RequestInterface
    {
        $header = sprintf('Token %s', $this->token);

        return $request->withHeader('Authorization', $header);
    }
}
