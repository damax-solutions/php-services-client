<?php

declare(strict_types=1);

namespace Damax\Services\Client\Tests\Bridge\Symfony\Console\Command;

use Damax\Services\Client\Bridge\Symfony\Console\Command\MvdLookupCommand;
use Damax\Services\Client\Client;
use Damax\Services\Client\InvalidRequestException;
use Damax\Services\Client\PassportCheck;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group integration
 * @group console
 */
class MvdLookupCommandTest extends KernelTestCase
{
    /**
     * @var Client|MockObject
     */
    private $client;

    /**
     * @var CommandTester
     */
    private $tester;

    protected function setUp()
    {
        static::bootKernel();

        $this->client = $this->createMock(Client::class);

        $command = new MvdLookupCommand($this->client);
        $command->setApplication(new Application(self::$kernel));

        $this->tester = new CommandTester($command);
    }

    /**
     * @test
     */
    public function it_checks_passport()
    {
        $this->client
            ->expects($this->once())
            ->method('checkPassport')
            ->with('0000000001')
            ->willReturn(PassportCheck::fromArray([
                'source' => '0000000001',
                'code' => 1,
                'message' => 'Valid passport',
                'ok' => true,
                'series' => '0000',
                'number' => '000001',
            ]))
        ;

        $code = $this->tester->execute(['command' => 'damax:mvd:passport:lookup', 'number' => '0000000001']);

        $output = <<<CONSOLE

 --------- ---------------- 
  Field     Value           
 --------- ---------------- 
  source    0000000001      
  code      1               
  message   Valid passport  
  ok        +               
  series    0000            
  number    000001          
 --------- ---------------- 


CONSOLE;

        $this->assertSame(0, $code);
        $this->assertEquals($output, $this->tester->getDisplay());
    }

    /**
     * @test
     */
    public function it_fails_to_perform_check()
    {
        $this->client
            ->expects($this->once())
            ->method('checkPassport')
            ->with('0000000001')
            ->willThrowException(new InvalidRequestException('Connection problem.'))
        ;

        $code = $this->tester->execute(['command' => 'damax:mvd:passport:lookup', 'number' => '0000000001']);

        $this->assertSame(1, $code);
        $this->assertEquals('[ERROR] Connection problem.', trim($this->tester->getDisplay()));
    }
}
