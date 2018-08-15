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

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function getIterator(): Iterator
    {
        return new ArrayIterator(array_map([RosfinItem::class, 'fromArray'], $this->data));
    }

    public function toArray(): array
    {
        return $this->data;
    }

    private function __construct(array $data)
    {
        $this->data = $data;
    }
}
