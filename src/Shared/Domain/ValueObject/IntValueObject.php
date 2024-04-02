<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract class IntValueObject implements ValueObject
{
    public function __construct(private int $value)
    {
    }

    public function value(): int
    {
        return $this->value;
    }

    public function between(int $min, int $max): bool
    {
        return ($min <= $this->value) && ($this->value <= $max);
    }

    public function biggerThan(int $value): bool
    {
        return $this->value() > $value;
    }
}
