<?php

declare(strict_types=1);

namespace Damax\Services\Client\Tests;

use Damax\Services\Client\PassportCheck;
use PHPUnit\Framework\TestCase;

class PassportCheckTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_passport_check_result()
    {
        $result = PassportCheck::fromArray([
            'source' => '74 05 558551',
            'code' => 2,
            'message' => 'Invalid passport',
            'series' => '7405',
            'number' => '558551',
        ]);

        $this->assertEquals('74 05 558551', $result->source());
        $this->assertEquals(2, $result->code());
        $this->assertEquals('Invalid passport', $result->message());
        $this->assertEquals('7405', $result->series());
        $this->assertEquals('558551', $result->number());
        $this->assertTrue($result->failed());
        $this->assertFalse($result->passed());
    }

    /**
     * @test
     */
    public function it_creates_malformed_passport_check_result()
    {
        $result = PassportCheck::fromArray([
            'source' => '743',
            'code' => 3,
            'message' => 'Malformed passport',
        ]);

        $this->assertEquals('743', $result->source());
        $this->assertEquals(3, $result->code());
        $this->assertEquals('Malformed passport', $result->message());
        $this->assertTrue($result->malformed());
        $this->assertFalse($result->passed());
        $this->assertNull($result->series());
        $this->assertNull($result->number());
        $this->assertEquals([
            'source' => '743',
            'code' => 3,
            'message' => 'Malformed passport',
        ], $result->toArray());
    }
}
