<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Domain;

use App\Restaurant\Orders\Domain\Errors\DeliveryOrderRequiresEqualTotalPrice;
use App\Restaurant\Orders\Domain\Errors\LineItemAmountIsNotBetweenRequiredValues;
use App\Restaurant\Orders\Domain\Errors\RequiredConceptsNotFound;
use App\Shared\Domain\ValueObject\Price;

final class OrderWithDelivery extends AbstractOrder
{
    /**
     * @throws DeliveryOrderRequiresEqualTotalPrice
     * @throws LineItemAmountIsNotBetweenRequiredValues
     * @throws RequiredConceptsNotFound
     */
    public static function create(OrderId $id, OrderLines $orderLines, Price $deliveryPrice, Price $totalPrice): self
    {
        parent::validateOrderLines($orderLines);
        self::validateTotalPrice($orderLines, $deliveryPrice, $totalPrice);

        return new self(
            $id,
            OrderType::createDelivery(),
            $orderLines,
            $deliveryPrice,
            $totalPrice,
        );
    }

    /**
     * @throws DeliveryOrderRequiresEqualTotalPrice
     */
    private static function validateTotalPrice(OrderLines $orderLines, Price $deliveryPrice, Price $totalPrice): void
    {
        $expectedPrice = $orderLines->totalPrice()->add($deliveryPrice);

        if (!$expectedPrice->equals($totalPrice)) {
            throw new DeliveryOrderRequiresEqualTotalPrice($expectedPrice->toFloat());
        }
    }
}
