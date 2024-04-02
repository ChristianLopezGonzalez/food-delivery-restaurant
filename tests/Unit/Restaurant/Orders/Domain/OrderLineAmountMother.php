<?php

declare(strict_types=1);

namespace App\Tests\Unit\Restaurant\Orders\Domain;

use App\Restaurant\Orders\Domain\OrderLineAmount;
use App\Tests\Unit\Shared\Domain\MotherCreator;

final class OrderLineAmountMother
{
    public static function create(?int $value = null): OrderLineAmount
    {
        $value = $value ?? MotherCreator::random()->numberBetween(0, 10);
        return new OrderLineAmount($value);
    }
}
