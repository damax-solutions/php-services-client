<?php

declare(strict_types=1);

namespace Damax\Client;

final class PassportCheck
{
    private const PASSED = 1;
    private const FAILED = 2;
    private const MALFORMED = 3;

    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function source(): string
    {
        return $this->data['source'];
    }

    public function code(): int
    {
        return $this->data['code'];
    }

    public function passed(): bool
    {
        return self::PASSED === $this->code();
    }

    public function failed(): bool
    {
        return self::FAILED === $this->code();
    }

    public function malformed(): bool
    {
        return self::MALFORMED === $this->code();
    }

    public function message(): string
    {
        return $this->data['message'];
    }

    public function series(): ?string
    {
        return $this->data['series'] ?? null;
    }

    public function number(): ?string
    {
        return $this->data['number'] ?? null;
    }
}
