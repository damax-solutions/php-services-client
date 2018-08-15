<?php

declare(strict_types=1);

namespace Damax\Services\Client\Tests;

use Damax\Services\Client\RosfinCheck;
use Damax\Services\Client\RosfinItem;
use PHPUnit\Framework\TestCase;

class RosfinCheckTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_rosfin_check_result()
    {
        $result = RosfinCheck::fromArray([
            [
                'id' => 123,
                'type' => 4,
                'fullName' => ['John Doe', 'Jane Doe'],
            ],
            [
                'id' => 456,
                'type' => 2,
                'fullName' => ['Organization One', 'Organization Two'],
            ],
        ]);

        $items = iterator_to_array($result);
        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(RosfinItem::class, $items);
        $this->assertEquals([
            [
                'id' => 123,
                'type' => 4,
                'fullName' => ['John Doe', 'Jane Doe'],
            ],
            [
                'id' => 456,
                'type' => 2,
                'fullName' => ['Organization One', 'Organization Two'],
            ],
        ], $result->toArray());
    }
}
