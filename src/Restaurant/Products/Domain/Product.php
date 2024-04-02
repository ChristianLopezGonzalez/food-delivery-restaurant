<?php

declare(strict_types=1);

namespace App\Restaurant\Products\Domain;

use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\ValueObject\Price;

final class Product extends AggregateRoot
{
    public function __construct(private readonly ProductCode $code, private readonly Price $price)
    {
    }

    public function code(): ProductCode
    {
        return $this->code;
    }

    public function price(): Price
    {
        return $this->price;
    }
}
