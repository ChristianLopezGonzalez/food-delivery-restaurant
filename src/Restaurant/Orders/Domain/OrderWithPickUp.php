<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Domain;

use App\Restaurant\Orders\Domain\Errors\LineItemAmountIsNotBetweenRequiredValues;
use App\Restaurant\Orders\Domain\Errors\PickUpOrderRequiresReachTotalPrice;
use App\Restaurant\Orders\Domain\Errors\RequiredConceptsNotFound;
use App\Shared\Domain\ValueObject\Price;

final class OrderWithPickUp extends AbstractOrder
{
    /**
     * @throws PickUpOrderRequiresReachTotalPrice
     * @throws LineItemAmountIsNotBetweenRequiredValues
     * @throws RequiredConceptsNotFound
     */
    public static function create(OrderId $id, OrderLines $orderLines, Price $totalPrice): self
    {
        parent::validateOrderLines($orderLines);
        self::validateTotalPrice($orderLines, $totalPrice);

        return new self(
            $id,
            OrderType::createPickUp(),
            $orderLines,
            Price::zero(),
            $totalPrice,
        );
    }

    /**
     * @throws PickUpOrderRequiresReachTotalPrice
     */
    private static function validateTotalPrice(OrderLines $orderLines, Price $totalPrice): void
    {
        $expectedPrice = $orderLines->totalPrice();

        if ($expectedPrice->lessThan($totalPrice)) {
            throw new PickUpOrderRequiresReachTotalPrice($expectedPrice->toString());
        }
    }
}
