<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Domain;

use App\Shared\Domain\ValueObject\StringValueObject;

final class OrderType extends StringValueObject
{
    private const PICKUP = 'pickup';
    private const DELIVERY = 'delivery';

    private function __construct(string $value)
    {
        parent::__construct($value);
    }

    public static function createPickUp(): self
    {
        return new self(self::PICKUP);
    }

    public static function createDelivery(): self
    {
        return new self(self::DELIVERY);
    }

    public function isDelivery(): bool
    {
        return $this->isEquals(self::createDelivery());
    }
}
