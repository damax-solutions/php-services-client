<?php

declare(strict_types=1);

namespace Damax\Services\Client\Tests\Bridge\Symfony\Console\Command;

use Damax\Services\Client\Bridge\Symfony\Console\Command\RosfinLookupCommand;
use Damax\Services\Client\Client;
use Damax\Services\Client\InvalidRequestException;
use Damax\Services\Client\RosfinCheck;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RosfinLookupCommandTest extends KernelTestCase
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

        $command = new RosfinLookupCommand($this->client);
        $command->setApplication(new Application(self::$kernel));

        $this->tester = new CommandTester($command);
    }

    /**
     * @test
     */
    public function it_fails_to_perform_check()
    {
        $this->client
            ->expects($this->once())
            ->method('checkRosfin')
            ->with('John Doe', '1983-01-20')
            ->willThrowException(new InvalidRequestException('Connection problem.'))
        ;

        $code = $this->tester->execute(['command' => 'damax:rosfin:lookup', 'fullName' => 'John Doe', 'birthDate' => '1983-01-20']);

        $this->assertSame(1, $code);
        $this->assertEquals('[ERROR] Connection problem.', trim($this->tester->getDisplay()));
    }

    /**
     * @test
     */
    public function it_finds_no_results()
    {
        $this->client
            ->expects($this->once())
            ->method('checkRosfin')
            ->with('John Doe', '1983-01-20')
            ->willReturn(RosfinCheck::fromArray([]))
        ;

        $code = $this->tester->execute(['command' => 'damax:rosfin:lookup', 'fullName' => 'John Doe', 'birthDate' => '1983-01-20']);

        $this->assertSame(0, $code);
        $this->assertEquals('[OK] Not found.', trim($this->tester->getDisplay()));
    }

    /**
     * @test
     */
    public function it_finds_results()
    {
        $this->client
            ->expects($this->once())
            ->method('checkRosfin')
            ->with('John Doe', '1983-01-20')
            ->willReturn(RosfinCheck::fromArray([
                [
                    'id' => 3302081139,
                    'type' => 4,
                    'fullName' => ['СОКОЛОВСКИЙ РУСЛАН ГЕННАДЬЕВИЧ'],
                ],
                [
                    'id' => 1,
                    'type' => 3,
                    'fullName' => ['John Doe', 'Jane Doe'],
                    'birthDate' => '1983-01-20',
                    'birthPlace' => 'Riga',
                    'description' => 'Extremely dangerous.',
                    'address' => 'Military base.',
                    'resolution' => 'Wanted by FBI.',
                    'passport' => '0000000001',
                ],
            ]))
        ;

        $code = $this->tester->execute(['command' => 'damax:rosfin:lookup', 'fullName' => 'John Doe', 'birthDate' => '1983-01-20']);

        $output = <<<CONSOLE

 ------------- -------------------------------- 
  Field         Value                           
 ------------- -------------------------------- 
  ID            3302081139                      
  Type          4                               
  Full name     СОКОЛОВСКИЙ РУСЛАН ГЕННАДЬЕВИЧ  
  Birth date    -                               
  Birth place   -                               
  Description   -                               
  Address       -                               
  Resolution    -                               
  Passport      -                               
 ------------- -------------------------------- 


 ------------- ---------------------- 
  Field         Value                 
 ------------- ---------------------- 
  ID            1                     
  Type          3                     
  Full name     John Doe              
                Jane Doe              
  Birth date    1983-01-20            
  Birth place   Riga                  
  Description   Extremely dangerous.  
  Address       Military base.        
  Resolution    Wanted by FBI.        
  Passport      0000000001            
 ------------- ---------------------- 


CONSOLE;

        $this->assertSame(0, $code);
        $this->assertEquals($output, $this->tester->getDisplay());
    }
}
