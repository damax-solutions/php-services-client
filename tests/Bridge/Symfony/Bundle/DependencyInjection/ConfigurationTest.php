<?php

declare(strict_types=1);

namespace Damax\Services\Client\Tests\Bridge\Symfony\Bundle\DependencyInjection;

use Damax\Services\Client\Bridge\Symfony\Bundle\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * @test
     */
    public function it_processes_empty_config()
    {
        $config = [
            'api_key' => 'qwerty12',
        ];

        $this->assertProcessedConfigurationEquals([$config], [
            'api_key' => 'qwerty12',
            'endpoint' => 'https://product.damax.solutions/api',
        ]);
    }

    /**
     * @test
     */
    public function it_processes_config()
    {
        $config = [
            'api_key' => 'qwerty12',
            'endpoint' => 'domain.abc',
        ];

        $this->assertProcessedConfigurationEquals([$config], [
            'api_key' => 'qwerty12',
            'endpoint' => 'domain.abc',
        ]);
    }

    protected function getConfiguration(): ConfigurationInterface
    {
        return new Configuration();
    }
}
