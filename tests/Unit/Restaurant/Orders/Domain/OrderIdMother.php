<?php

declare(strict_types=1);

namespace App\Tests\Unit\Restaurant\Orders\Domain;

use App\Restaurant\Orders\Domain\OrderId;
use App\Tests\Unit\Shared\Domain\MotherCreator;

final class OrderIdMother
{
    public static function create(?string $value = null): OrderId
    {
        $value = $value ?? MotherCreator::random()->uuid();
        return new OrderId($value);
    }
}
