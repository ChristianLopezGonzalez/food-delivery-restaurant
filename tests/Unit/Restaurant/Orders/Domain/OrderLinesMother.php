<?php

declare(strict_types=1);

namespace App\Tests\Unit\Restaurant\Orders\Domain;

use App\Restaurant\Orders\Domain\OrderLines;
use App\Tests\Unit\Shared\Domain\MotherCreator;

final class OrderLinesMother
{
    public static function create(?array $values = null): OrderLines
    {
        $values = array_map(
            static fn(array $line)=> OrderLineMother::create($line['concept'], $line['price'], $line['amount']),
            $values,
        );

        return new OrderLines(...$values);
    }

    public static function createWithRandomLines(): OrderLines
    {
        $qty = MotherCreator::random()->numberBetween(1, 2);

        $values = [];
        for ($i = 0; $i <= $qty; $i++) {
            $values[] = OrderLineMother::create();
        }

        return new OrderLines(...$values);
    }
}
