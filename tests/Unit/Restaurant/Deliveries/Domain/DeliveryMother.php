<?php

declare(strict_types=1);

namespace App\Tests\Unit\Restaurant\Deliveries\Domain;

use App\Restaurant\Deliveries\Domain\Delivery;
use App\Tests\Unit\Shared\Domain\ValueObjets\PriceMother;

final class DeliveryMother
{
    public static function create(?int $value = null): Delivery
    {
        return new Delivery(price: PriceMother::create($value));
    }
}
