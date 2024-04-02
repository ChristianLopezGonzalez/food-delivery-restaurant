<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final class Price extends Money
{
    public static function fromFloat(float $basePrice, Currency $currency = null): self
    {
        $amount = (string)(round($basePrice, 2) * 100);

        return new self($amount, $currency);
    }

    public static function fromInteger(int $basePrice, Currency $currency = null): self
    {
        return new self((string)$basePrice, $currency);
    }

    public static function zero(Currency $currency = null): self
    {
        return new self('0', $currency);
    }

    public function toFloat(): float
    {
        return $this->value() / 100;
    }
}
