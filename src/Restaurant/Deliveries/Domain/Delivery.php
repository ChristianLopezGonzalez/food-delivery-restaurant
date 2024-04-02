<?php

declare(strict_types=1);

namespace App\Restaurant\Deliveries\Domain;

use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\ValueObject\Price;

final class Delivery extends AggregateRoot
{
    private const FLAT_RATE_PRICE = 150;

    public function __construct(private readonly Price $price)
    {
    }

    public static function createFlatRate(): self
    {
        return new Delivery(
            Price::fromInteger(self::FLAT_RATE_PRICE),
        );
    }

    public function price(): Price
    {
        return $this->price;
    }
}
