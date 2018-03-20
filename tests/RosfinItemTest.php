<?php

declare(strict_types=1);

namespace Damax\Services\Client\Tests;

use Damax\Services\Client\RosfinItem;
use PHPUnit\Framework\TestCase;

class RosfinItemTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_rosfin_item()
    {
        $result = new RosfinItem([
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
        $this->assertEquals([
            'id' => 123,
            'type' => 4,
            'fullName' => ['John Doe', 'Jane Doe'],
            'birthDate' => '1983-20-01',
            'birthPlace' => 'London',
        ], $result->toArray());
    }
}
