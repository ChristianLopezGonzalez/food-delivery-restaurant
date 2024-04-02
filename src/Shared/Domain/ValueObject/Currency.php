<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final class Currency extends StringValueObject
{
    private const EUR = 'EUR';

    private function __construct(?string $value = null)
    {
        parent::__construct($value ?? self::EUR);
    }

    public static function euro(): self
    {
        return new self(self::EUR);
    }
}
