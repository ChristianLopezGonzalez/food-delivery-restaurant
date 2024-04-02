<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract class ArrayValueObject implements ValueObject
{
    public function __construct(protected array $values)
    {
    }

    public function value(): array
    {
        return $this->values;
    }
}
