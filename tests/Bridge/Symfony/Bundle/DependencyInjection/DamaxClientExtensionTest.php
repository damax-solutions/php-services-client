<?php

declare(strict_types=1);

namespace Damax\Client\Tests\Bridge\Symfony\Bundle\DependencyInjection;

use Damax\Client\Bridge\Symfony\Bundle\DependencyInjection\DamaxClientExtension;
use Damax\Client\Client;
use Damax\Client\Configuration as ClientConfiguration;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\DependencyInjection\Reference;

class DamaxClientExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     */
    public function it_registers_services()
    {
        $this->load([
            'api_key' => 'qwerty12',
            'endpoint' => 'http://domain.abc',
        ]);

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            ClientConfiguration::class,
            0,
            'http://domain.abc'
        );
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            ClientConfiguration::class,
            1,
            'qwerty12'
        );

        $this->assertContainerBuilderHasService(Client::class);
        $definition = $this->container->getDefinition(Client::class);

        $this->assertEquals([new Reference(ClientConfiguration::class), 'getClient'], $definition->getFactory());
    }

    protected function getContainerExtensions(): array
    {
        return [
            new DamaxClientExtension(),
        ];
    }
}
