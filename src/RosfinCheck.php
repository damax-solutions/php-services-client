<?php

declare(strict_types=1);

namespace Damax\Services\Client;

use ArrayIterator;
use Countable;
use Iterator;
use IteratorAggregate;

final class RosfinCheck implements IteratorAggregate, Countable
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function count(): int
    {
        return count($this->data);
    }

    /**
     * @return RosfinItem[]
     */
    public function getIterator(): Iterator
    {
        return new ArrayIterator(array_map([$this, 'itemFactory'], $this->data));
    }

    public function toArray(): array
    {
        return $this->data;
    }

    private function itemFactory(array $item): RosfinItem
    {
        return new RosfinItem($item);
    }
}
