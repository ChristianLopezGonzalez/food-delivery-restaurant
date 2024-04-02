<?php

declare(strict_types=1);

namespace App\Tests\Unit\Restaurant\Orders\Domain;

use App\Restaurant\Orders\Domain\OrderWithDelivery;
use App\Restaurant\Orders\Domain\OrderWithPickUp;
use App\Tests\Unit\Shared\Domain\ValueObjets\PriceMother;

final class OrderMother
{
    public static function createWithDelivery(
        ?string $id = null,
        ?array $orderLines = null,
        ?int $deliveryPrice = null,
        ?int $totalPrice = null
    ): OrderWithDelivery {
        $orderLines = $orderLines ? OrderLinesMother::create($orderLines) : OrderLinesMother::createWithRandomLines();
        $deliveryPrice = PriceMother::create($deliveryPrice);
        $totalPrice = $totalPrice ? PriceMother::create($totalPrice) : $orderLines->totalPrice()->add($deliveryPrice);
        return OrderWithDelivery::create(
            OrderIdMother::create($id),
            $orderLines,
            $deliveryPrice,
            $totalPrice,
        );
    }
    public static function createWithPickup(
        ?string $id = null,
        ?array $orderLines = null,
        ?int $totalPrice = null
    ): OrderWithPickUp {
        $orderLines = $orderLines ? OrderLinesMother::create($orderLines) : OrderLinesMother::createWithRandomLines();
        $totalPrice = $totalPrice ? PriceMother::create($totalPrice) : $orderLines->totalPrice();

        return OrderWithPickUp::create(
            OrderIdMother::create($id),
            $orderLines,
            $totalPrice,
        );
    }
}
