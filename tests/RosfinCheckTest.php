<?php

declare(strict_types=1);

namespace Damax\Client\Tests;

use Damax\Client\RosfinCheck;
use PHPUnit\Framework\TestCase;

class RosfinCheckTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_rosfin_check_result()
    {
        $result = new RosfinCheck([
            'id' => 123,
            'type' => 4,
            'fullName' => ['John Doe', 'Jane Doe'],
            'birthDate' => '1983-20-01',
            'birthPlace' => 'London',
        ]);

        $this->assertEquals(123, $result->id());
        $this->assertEquals(4, $result->type());
        $this->assertTrue($result->person());
        $this->assertEquals(['John Doe', 'Jane Doe'], $result->fullName());
        $this->assertEquals('1983-20-01', $result->birthDate());
        $this->assertEquals('London', $result->birthPlace());
        $this->assertNull($result->description());
        $this->assertNull($result->address());
        $this->assertNull($result->resolution());
        $this->assertNull($result->passport());
    }
}
