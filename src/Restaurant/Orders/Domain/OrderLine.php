<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Domain;

use App\Shared\Domain\ValueObject\Price;

final class OrderLine
{
    public function __construct(
        private readonly OrderLineConcept $concept,
        private readonly Price $price,
        private readonly OrderLineAmount $amount
    ) {
    }

    public function concept(): OrderLineConcept
    {
        return $this->concept;
    }

    public function price(): Price
    {
        return $this->price;
    }

    public function amount(): OrderLineAmount
    {
        return $this->amount;
    }
}
