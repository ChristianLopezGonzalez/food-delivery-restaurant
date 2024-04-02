<?php

declare(strict_types=1);

namespace App\Tests\Unit\Restaurant\Orders\Domain;

use App\Restaurant\Orders\Domain\OrderType;

final class OrderTypeMother
{
    public static function createDelivery(): OrderType
    {
        return OrderType::createDelivery();
    }
    public static function createPickUp(): OrderType
    {
        return OrderType::createPickUp();
    }
}
