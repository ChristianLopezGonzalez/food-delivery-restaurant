<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract class StringValueObject implements ValueObject
{
    public function __construct(protected string $value)
    {
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isEquals(ValueObject $comparisonValue): bool
    {
        return $this->value === $comparisonValue->value();
    }

    /**
     * @param array<array-key, string> $options
     */
    public function in(array $options): bool
    {
        return in_array($this->value, $options);
    }
}
