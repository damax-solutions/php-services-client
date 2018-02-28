<?php

declare(strict_types=1);

namespace Damax\Client;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\LoggerPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpAsyncClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Psr\Log\LoggerInterface;

final class Configuration
{
    private $plugins = [];
    private $httpClient;

    public function __construct(string $endpoint, string $token)
    {
        $this->plugins[] = new BaseUriPlugin((UriFactoryDiscovery::find())->createUri($endpoint));

        $this->plugins[] = new AuthenticationPlugin(new TokenAuthentication($token));
    }

    public function setLogger(LoggerInterface $logger): self
    {
        $this->plugins[] = new LoggerPlugin($logger);

        return $this;
    }

    public function setHttpClient(HttpClient $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    public function getClient(): Client
    {
        $httpClient = $this->httpClient ?? HttpAsyncClientDiscovery::find();

        return new Client(new HttpMethodsClient(new PluginClient($httpClient, $this->plugins), MessageFactoryDiscovery::find()));
    }
}
